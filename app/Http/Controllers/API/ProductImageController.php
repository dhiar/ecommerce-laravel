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
        $productId = MainHasher::decode(request('id_product'));
        $product = Product::find($productId);
        
        // Remove existing file-image if not used in product-images
        $existImages = $product->images->pluck('image')->toArray();
        $storage_path_images = request('storage_path_images');
        foreach($existImages as $exist) {
            if ( !in_array($exist, $storage_path_images)) {
                $pathCurrentImage = storage_path('app/'.$exist);
                if (file_exists($pathCurrentImage)) {
                    @unlink($pathCurrentImage);
                }
            }
        }
        
        if ( count ($storage_path_images) > 0) {
            $pathImages = [];
            foreach ($storage_path_images as $key => $path) {
                // jika tidak ada dalam array yg lama, maka unlink
                $pathImages[] = new ProductImage(['image' => $path]);
            }
        }

        $product->images()->delete();
        $productImages = $product->images()->saveMany($pathImages);
        return response()->json([
            'success' => true,
            'process' => 'store',
            'data' => fractal($productImages, $this->transformer)->toArray()['data'],
            'message' => 'Success store product images',
        ]);
    }
}
