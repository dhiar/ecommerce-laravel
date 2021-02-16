<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\ProductImageTransformer;
use App\{ProductImage, Product};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Auth;

class ProductImageController extends Controller
{
    // use Auth;
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->model = new ProductImage();
        $this->transformer = new ProductImageTransformer();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonRequest $request)
    {
        // dd(\request()->all());

        // "id_product" => "ND"
        // "images" => array:2 [
        //     0 => "blob:http://localhost:3000/7c6d0728-b1b7-43df-877e-7c34672cacfd"
        //     1 => "blob:http://localhost:3000/0a5cb8f1-d62c-4a2c-9532-5df22a0b4b09"
        // ]
        // "storage_path_images" => array:2 [
        //     0 => "public/images/7kVzAuyb7KFtlAjc8iWSMwS56AWYxKFqTcqLuyY0.png"
        //     1 => "public/images/96pDcCEWMYK1Yeu3mM8gk5OSw9HHolrB0czqlNUR.png"
        // ]


        $productId = MainHasher::decode(request('id_product'));
        $product = Product::find($productId);

        $storage_path_images = request('storage_path_images');
        
        if ( count ($storage_path_images) > 0) {
            $pathImages = [];
            foreach ($storage_path_images as $key => $path) {
                // jika tidak ada dalam array yg lama, maka unlink

                $pathImages[] = new ProductImage(['image' => $path]);
            }
        }

        $productImages = $product->images()->saveMany($pathImages);
        return response()->json([
            'success' => true,
            'process' => 'store',
            'data' => fractal($productImages, $this->transformer)->toArray()['data'],
            'message' => 'Success store product images',
        ]);
    }
}
