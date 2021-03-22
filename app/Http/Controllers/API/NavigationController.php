<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\NavigationTransformer;
use App\Navigation;


class NavigationController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->model = new Navigation();
        $this->transformer = new NavigationTransformer;
		// $this->middleware(['auth:admin-api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommonRequest $request)
    {
        return $request->index($this->model, $this->transformer, 'id', 'ASC', 100); // number is limit
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
        $validated = $request->validate([
            'title' => 'required|min:5|max:30',
            'content' => 'required|min:5',
            'slug' => 'required|min:5|max:25',
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
		return $request->update($id, $this->model, $this->transformer);
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
