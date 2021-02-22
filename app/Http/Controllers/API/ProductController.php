<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\{ProductTransformer, ProductImageTransformer, GrosirTransformer};
use App\{Admin, Product, ProductImage, Grosir};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Schema;
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
        $this->middleware('auth:admin-api', ['store', 'update', 'destroy']);
        $this->model = new Product();
        $this->table = $this->model->getTable();
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
        } 
        else {
            $model = $this->model;
        }

        return $request->index($model, $this->transformer, 'id', 'ASC', 10, $this->table);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('create');
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

        $categoryId = MainHasher::decode(request('id_product_category'));
        request()->request->add(['id_product_category' => $categoryId]);

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
		return $request->show($id, $this->model, $this->transformer);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('edit');
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
        if (request('storage_path_image')) {
            $obj = $this->model->find($id);
            $pathCurrentIcon = storage_path('app/'.$obj->image);

			if (file_exists($pathCurrentIcon)) {
                @unlink($pathCurrentIcon);
            }

            request()->request->add(['image' => request('storage_path_image')]);
            $params = request()->except(['storage_path_image']);
        } else {
            $params = request()->except(['image']);
        }

        if (request('id_product_category')) {
            $params['id_product_category'] = MainHasher::decode(request('id_product_category'));
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

    public function listGrosirs(Product $product)
	{
        $productGrosirs = Grosir::whereIdProduct($product->id)->get();
        return response()->json([
            'success' => true,
            'process' => 'list',
            'data' => fractal($productGrosirs, $this->transformer_grosirs)->toArray()['data'],
            'message' => 'Success show list product grosir prices',
        ]);
	}
}
