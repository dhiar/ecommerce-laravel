<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\ProductCategoryTransformer;
use App\ProductCategory;
use Illuminate\Support\Facades\DB;
use Auth;

class ProductCategoryController extends Controller
{
    // use Auth;
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        // $this->middleware('auth:admin-api', ['store', 'destroy']);
        // $this->middleware('auth:api', ['store']);
        $this->model = new ProductCategory();
        $this->transformer = new ProductCategoryTransformer();
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
        request()->request->add(['icon' => request('storage_path_icon')]);
        $validated = $request->validate([
            'name' => "required|min:5|max:30|unique:product_categories,name,{$this->model->id}",
            'slug' => "required|min:5|max:30|unique:product_categories,slug,{$this->model->id}",
        ]);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommonRequest $request, $id)
	{
        $params = [];
        if (request('storage_path_icon')) {
            $obj = $this->model->find($id);
            $pathCurrentIcon = storage_path('app/'.$obj->icon);

			if (file_exists($pathCurrentIcon)) {
                @unlink($pathCurrentIcon);
            }

            request()->request->add(['icon' => request('storage_path_icon')]);
            $params = request()->except(['storage_path_icon']);
        } else {
            $params = request()->except(['icon']);
        }

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

    public function cloneCategory(CommonRequest $request, $id){
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
