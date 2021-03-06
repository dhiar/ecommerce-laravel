<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\ProductBrandTransformer;
use App\{ProductBrand, ProductCategory, CategoryBrand};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Auth;

class ProductBrandController extends Controller
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
            'cloneBrand'
        ]]);
        $this->model = new ProductBrand();
        $this->transformer = new ProductBrandTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommonRequest $request)
    {
        return $request->index($this->model, $this->transformer, 'name', 'ASC', 100);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonRequest $request)
    {
        $validated = $request->validate([
            'name' => "required|min:5|max:30|unique:product_brands,name,{$this->model->id}",
            'slug' => "required|min:5|max:30|unique:product_brands,slug,{$this->model->id}",
        ]);
        
        DB::beginTransaction();
        try {
            $brand = ProductBrand::create(request()->except(['id_category']));
            $category = ProductCategory::find(MainHasher::decode(request('id_category')));
            $category->brands()->attach($brand->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'process' => 'Create',
                'data' => $brand,
                'message' => 'Success create Brand',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'process' => 'Create',
                'data' => [],
                'message' => 'Failed create Brand',
            ]);
            DB::rollback();
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommonRequest $request, $id)
	{
        $brand = ProductBrand::find($id);

        DB::beginTransaction();
        try {
            $brand->update(request()->except(['id_category']));

            $categoryBrand = CategoryBrand::where('id_brand',$id)->first();
            $category = ProductCategory::find($categoryBrand->id_category);
            $category->brands()->detach($id);

            $category = ProductCategory::find(MainHasher::decode(request('id_category')));
            $category->brands()->attach($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'process' => 'Update',
                'data' => $brand,
                'message' => 'Success update Brand',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'process' => 'Update',
                'data' => [],
                'message' => 'Failed update Brand',
            ]);
            DB::rollback();
        }
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

    public function cloneBrand(CommonRequest $request, $id){
        $model = $this->model->find($id);
        $newModel = $model->replicate();
        $newModel->name = $model->name.' - copy';
        $newModel->slug = $model->slug.'-copy';

        $newModel->save();

        return response()->json([
            'success' => true,
            'process' => 'clone',
            'data' => fractal($newModel, $this->transformer)->toArray()['data'],
            'message' => "Success clone category product",
        ]);
    }
}
