<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\{ProductTransformer, ProductImageTransformer, GrosirTransformer, ProductTagTransformer};
use App\Transformers\{AdminTransformer, TagsTransformer};
use App\{Admin, Product, ProductImage, Grosir, CategoryBrand, ProductCategory};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Schema;
use Spatie\Tags\Tag;
use Auth;

class ProductController extends Controller
{
    // use Auth;
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->middleware('auth:admin-api', ['only' => [
            'store',
            'update',
            'destroy',
            'indexAdmin'
        ]]);
        $this->middleware('guest', ['only' => [
            'index',
            'products'
        ]]);
        $this->model = new Product();
        $this->table = $this->model->getTable();
        $this->transformer = new ProductTransformer();
        $this->transformer = new ProductTransformer();
        $this->transformer_images = new ProductImageTransformer();
        $this->transformer_grosirs = new GrosirTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommonRequest $request)
    {
        if (request('is_promo') == "0" || request('is_promo') == "1") {
            $model = $this->model::whereIsPromo(request('is_promo'));
        } else if (request('slug')) {
            $model = $this->model::whereSlug(request('slug'));
        } else if (request('id_brand') || request('id_category')  )  {
            $brandId = is_numeric(request('id_brand')) ? request('id_brand') : MainHasher::decode(request('id_brand'));
            $categoryId = is_numeric(request('id_category')) ? request('id_category') : MainHasher::decode(request('id_category'));

            if (request('id_category') && request('id_brand')) {
                $model = Product::whereHas('category_brand', function($query) use($categoryId, $brandId) { 
                    $query->where('id_category', $categoryId)->where('id_brand', $brandId); 
                });
            } else {
                $model = Product::whereHas('category_brand', function($query) use($categoryId) { 
                    $query->where('id_category', $categoryId); 
                });
            }
        }
        else {
            $model = $this->model;
        }

        $fieldOrder = $request->fieldOrder ?? "id";
        $sort = $request->sort ?? "ASC";
        $limit = request('limit') ?? 10;

        return $request->index($model, $this->transformer, $fieldOrder, $sort, $limit, $this->table);
    }

    public function indexAdmin(CommonRequest $request){

        $admin = fractal(Auth::user(), AdminTransformer::class)->toArray()['data'];
        $adminId = Auth::id();
        $uTypeId = $admin["relationships"]["user_type"]["id"];
        $uTypeId = is_numeric($uTypeId) ? $uTypeId : MainHasher::decode($uTypeId);

        if (request('is_promo') == "0" || request('is_promo') == "1") {
            $model = $this->model::whereIsPromo(request('is_promo'));
        } else if (request('slug')) {
            $model = $this->model::whereSlug(request('slug'));
        } else if (request('id_brand') || request('id_category')  )  {
            $brandId = is_numeric(request('id_brand')) ? request('id_brand') : MainHasher::decode(request('id_brand'));
            $categoryId = is_numeric(request('id_category')) ? request('id_category') : MainHasher::decode(request('id_category'));

            if (request('id_category') && request('id_brand')) {
                $model = Product::whereHas('category_brand', function($query) use($categoryId, $brandId) { 
                    $query->where('id_category', $categoryId)->where('id_brand', $brandId); 
                });
            } else {
                $model = Product::whereHas('category_brand', function($query) use($categoryId) { 
                    $query->where('id_category', $categoryId); 
                });
            }
        }
        else {
            $model = $this->model;
        }

        if($uTypeId == 3) {
            $model = $model->whereHas('admin', function($qAdmin) use ($adminId) {
                $qAdmin->where('id', $adminId);
            });
        }

        $fieldOrder = $request->fieldOrder ?? "id";
        $sort = $request->sort ?? "ASC";
        $limit = request('limit') ?? 10;

        return $request->index($model, $this->transformer, $fieldOrder, $sort, $limit, $this->table);
    }

    public function filterProducts(CommonRequest $request){
        $model = $this->model;
        if (request('id_brand') || request('id_category')  )  {
            $brandId = is_numeric(request('id_brand')) ? request('id_brand') : MainHasher::decode(request('id_brand'));
            $categoryId = is_numeric(request('id_category')) ? request('id_category') : MainHasher::decode(request('id_category'));

            if (request('id_category') && request('id_brand')) {
                $model = $model->whereHas('category_brand', function($query) use($categoryId, $brandId) { 
                    $query->where('id_category', $categoryId)->where('id_brand', $brandId); 
                });
            } else {
                $model = $model->whereHas('category_brand', function($query) use($categoryId) { 
                    $query->where('id_category', $categoryId); 
                });
            }
        }
        else if (
            request('province_id') || request('city_id')  || request('district_id') || request('name')
        ) {
            $model = $model->whereHas('admin', function($query) { 
                $query->whereHas('address',  function($qAddress) {
                    $name = request('name') ? request('name') : "";
                    $province_id = request('province_id') ? request('province_id') : "";
                    $city_id = request('city_id') ? request('city_id') : "";
                    $district_id = request('district_id') ? request('district_id') : "";

                    $qAddress->where('name', 'LIKE', "%".$name."%")
                    ->where('province_id', 'LIKE', "%$province_id%")
                    ->where('city_id', 'LIKE', "%$city_id%")
                    ->where('district_id', 'LIKE', "%$district_id%");
                }); 
            });
        }
        else {
            $model = $this->model;
        }

        if (request('product_tags')) {
            $tags = collect(request('product_tags'))->pluck('name')->toArray();
            $model = $model::withAllTags($tags, 'product');
        }

        if (request('category_slug')) {
            $category_slug = request('category_slug');
            $category = ProductCategory::where('slug', $category_slug)->first();
            if($category->count() > 0) {
                $categoryId = $category->id;
                $model = $model->whereHas('category_brand', function($query) use($categoryId) { 
                    $query->where('id_category', $categoryId); 
                });
            }
        }

        if ($request->store_slug) {
            $storeSlug = $request->store_slug;
            if ($model->count() > 0) {
                $model = $model->whereHas('admin', function($query) use ($storeSlug) { 
                    $query->where('store_slug', $storeSlug);
                });
            }
        }

        $fieldOrder = $request->fieldOrder ?? "id";
        $sort = $request->sort ?? "ASC";
        $limit = request('limit') ?? 10;

        return $request->index($model, $this->transformer, $fieldOrder, $sort, $limit, $this->table);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonRequest $request)
    {
        $adminId = Auth::id();
        
        request()->request->add(['image' => request('storage_path_image')]);
        request()->request->add(['id_admin' => $adminId]);

        $brandId = is_numeric(request('id_brand')) ? request('id_brand') : MainHasher::decode(request('id_brand'));
        $categoryBrand = CategoryBrand::where('id_brand', $brandId)->first();
        request()->request->add(['id_category_brand' => $categoryBrand->id]);

        return $request->store($this->model, request()->all(), $this->transformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, CommonRequest $request)
	{
        if(request('action') == 'tags') {
            return $request->show($id, $this->model, new ProductTagTransformer());
        }
		return $request->show($id, $this->model, $this->transformer);
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommonRequest $request, $id)
	{
		$params = [];
        $product = $this->model->find($id);

        if (request('storage_path_image')) {
            $pathCurrentIcon = storage_path('app/'.$product->image);

			if (file_exists($pathCurrentIcon)) {
                @unlink($pathCurrentIcon);
            }

            request()->request->add(['image' => request('storage_path_image')]);
            $params = request()->except(['storage_path_image']);
        } else {
            $params = request()->except(['image']);
        }

        if(request('id_brand')) {
            $brandId = is_numeric(request('id_brand')) ? request('id_brand') : MainHasher::decode(request('id_brand'));
            $categoryBrand = CategoryBrand::where('id_brand', $brandId)->first();
            $params['id_category_brand'] = $categoryBrand->id;
        }
        
        if (\request('product_tags')) {
            $collect_product_tags = collect(request('product_tags'));
            $plucked_product_tags = $collect_product_tags->pluck('name');
            $product->syncTagsWithType($plucked_product_tags, 'product'); 

            unset($params['tags'],$params['product_tags'],$params['category'],$params['categories'],$params['brand'],$params['brands']);
        }

        unset($params['id_admin']);

		return $request->update($id, $this->model, $this->transformer, $params);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,CommonRequest $request)
	{
		return $request->destroy($this->model, $id);
	}

    public function listImages(Product $product)
	{
        $productImages = ProductImage::whereIdProduct($product->id)->get();
        return response()->json([
            'success' => true,
            'process' => 'list',
            'data' => fractal($productImages, $this->transformer_images)->toArray()['data'],
            'message' => 'Success show list product images',
        ]);
	}

    public function listGrosirs(CommonRequest $request, Product $product)
	{
        $model = Grosir::whereIdProduct($product->id);

        $fieldOrder = $request->fieldOrder ?? "id";
        $sort = $request->sort ?? "ASC";

        $grosir = new Grosir();
        $table = $grosir->getTable();

        return $request->index($model, $this->transformer_grosirs, $fieldOrder, $sort, 10, $table);
	}

    public function cloneProduct(CommonRequest $request, $id){
        $model = $this->model->find($id);
        $newModel = $model->replicate();
        $newModel->name = $model->name.' - copy';
        $newModel->slug = $model->slug.'-copy';

        $newModel->save();

        return response()->json([
            'success' => true,
            'process' => 'clone',
            'data' => fractal($newModel, $this->transformer)->toArray()['data'],
            'message' => "Success clone product",
        ]);
    }

    public function searchTags(){
        if (request('q')) {
            $q = request('q');
            $tags = Tag::where('name', 'LIKE', "%$q%")->orderBy('name', 'ASC')->limit(100)->get();
        } else {
            $tags = Tag::orderBy('name', 'ASC')->limit(100)->get();
        }
        return response()->json([
            'success' => true,
            'process' => 'tags',
            'data' => fractal($tags, new TagsTransformer())->toArray()['data'],
            'message' => "Success search tags",
        ]);
    }
}
