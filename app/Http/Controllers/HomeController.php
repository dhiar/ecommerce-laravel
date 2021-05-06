<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\{ProductCategoryTransformer, SlideTransformer};
use App\{Product, ProductCategory, Slide};
use App\Http\Controllers\API\ProductController;

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
            'categories' => $this->categories,
            'slides' => $this->slides,
        ]);
    }

    public function products(CommonRequest $request){
        $this->middleware('guest');
        $cProduct = new ProductController();
        request()->request->add(['limit' => 9]);

        return view('products', [
            'products' => $cProduct->index($request),
            'categories' => $this->categories,
        ]);
    }

    public function productDetail(CommonRequest $request, $slug){
        $this->middleware('guest');
        return view('product', [
            'categories' => $this->categories,
        ]);
    }

    public function categoryProduct(CommonRequest $request, $slug){
        $this->middleware('guest');
        return view('product', [
            'categories' => $this->categories,
        ]);
    }

    public function sellers(CommonRequest $request){
        $this->middleware('guest');
        return view('product', [
            'categories' => $this->categories,
        ]);
    }

    public function storeProducts(CommonRequest $request){
        $this->middleware('guest');
        return view('product', [
            'categories' => $this->categories,
        ]);
    }

    public function orders(CommonRequest $request, $invoice = null){
        $this->middleware('guest');
        return view('orders', [
            'categories' => $this->categories,
            'invoice' => $invoice
        ]);
    }
}
