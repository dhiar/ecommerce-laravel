<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\{ProductCategoryTransformer, SlideTransformer};
use App\{Product, ProductCategory, Slide};

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

        $this->slide = new Slide();
        $this->slideTransformer = new SlideTransformer();

        $request = new CommonRequest();
        $categories = $request->index($this->category, $this->categoryTransformer, 'name', 'ASC', 100);
        $this->categories = $categories->getData()->data;

        $slides = $request->index($this->slide, $this->slideTransformer, 'id', 'ASC', 10);
        $this->slides = $slides->getData()->data;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(CommonRequest $request)
    {
        $this->middleware('guest');

        return view('home', [
            'categories' => $this->categories,
            'slides' => $this->slides,
        ]);
    }

    public function home(CommonRequest $request)
    {
        $this->middleware('auth');
        
        return view('home', [
            'categories' => $this->categories
        ]);
    }

    public function productDetail(CommonRequest $request, $slug){
        $this->middleware('guest');
        $product = Product::whereSlug($slug)->first();
        return view('product', [
            'product' => $product,
            'categories' => $this->categories,
            'slides' => $this->slides,
        ]);
    }
}
