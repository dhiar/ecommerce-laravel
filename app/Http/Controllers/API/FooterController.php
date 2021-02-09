<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\{CommonRequest, FooterRequest};
use App\Transformers\{FooterNavTransformer, FooterTransformer};
use App\Footer;
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;


class FooterController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->model = new Footer();
        $this->transformer = new FooterTransformer;
        $this->footer_nav_transformer = new FooterNavTransformer;
		// $this->middleware(['auth:admin-api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FooterRequest $request)
    {
        $object = DB::table('footers')
                 ->select('navigation_id', DB::raw('count(*) as total'))
                 ->groupBy('navigation_id')
                 ->get();
        return $request->index($object, $this->footer_nav_transformer, 'asc');
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
            'page_id' => 'required',
            'navigation_id' => 'required',
        ]);

        // unique data footer , if is-exist, return error

        $pageId = MainHasher::decode(request('page_id'));
        $navigationId = MainHasher::decode(request('navigation_id'));

        request()->request->add(['page_id' => $pageId]);
        request()->request->add(['navigation_id' => $navigationId]);

        $count = Footer::wherePageId($pageId)
                ->whereNavigationId($navigationId)->count();

        if ($count > 0) {
            return response()->json([
                'success' => false,
                'process' => 'create',
                'data' => null,
                'message' => 'Failed create footer, because data is exist.',
            ]);
        } else {
            // dd(request()->all());
            return $request->store($this->model, request()->all(), $this->transformer);
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
        $pageId = MainHasher::decode(request('page_id'));
        $navigationId = MainHasher::decode(request('navigation_id'));
        request()->request->add(['page_id' => $pageId]);
        request()->request->add(['navigation_id' => $navigationId]);
        $params = \request()->all();
        
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
