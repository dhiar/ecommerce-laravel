<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\ProductCategoryTransformer;
use App\ProductCategory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->category = new ProductCategory();
        $this->categoryTransformer = new ProductCategoryTransformer();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(CommonRequest $request)
    {
        $this->middleware('guest');
        $categories = $request->index($this->category, $this->categoryTransformer, 'name', 'ASC', 100);
        $categories = $categories->getData()->data;

        return view('home', [
            'categories' => $categories
        ]);
    }

    public function home(CommonRequest $request)
    {
        $this->middleware('auth');
        $categories = $request->index($this->category, $this->categoryTransformer, 'name', 'ASC', 100);
        $categories = $categories->getData()->data;
        return view('home', [
            'categories' => $categories
        ]);
    }
}
