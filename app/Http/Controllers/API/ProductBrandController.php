<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\ProductBrandTransformer;
use App\ProductBrand;
use Illuminate\Support\Facades\DB;
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
		return $request->update($id, $this->model, $this->transformer, request()->all());
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
