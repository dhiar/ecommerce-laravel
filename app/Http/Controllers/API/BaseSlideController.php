<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\SlideTransformer;
use App\Slide;


class BaseSlideController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->model = new Slide();
        $this->transformer = new SlideTransformer();
		$this->middleware(['auth:admin-api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommonRequest $request)
    {
        return $request->index($this->model, $this->transformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonRequest $request)
    {
        request()->request->add(['image' => request('storage_path_image')]);
        $validated = $request->validate([
            'title' => "required|min:5|max:30|unique:slides,title,{$this->model->id}",
            'url' => 'required|min:1|max:50',
            'image' => "required|min:5|max:200|unique:slides,image,{$this->model->id}",
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
            $pathCurrentImage = storage_path('app/'.$obj->image);

			if (file_exists($pathCurrentImage)) {
                @unlink($pathCurrentImage);
            }

            request()->request->add(['image' => request('storage_path_image')]);
            $params = request()->except(['storage_path_image']);
        } else {
            $params = request()->except(['image']);
            
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
}
