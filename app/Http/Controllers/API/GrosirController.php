<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\GrosirTransformer;
use App\{Grosir, Product};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Auth;

class GrosirController extends Controller
{
    // use Auth;
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->model = new Grosir();
        $this->transformer = new GrosirTransformer();
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

        $prices = request('prices');
        $mins = request('mins');

        if ( count ($prices) > 0) {
            $dataGrosirs = [];
            foreach ($prices as $i => $price) {
                // jika tidak ada dalam array yg lama, maka unlink
                $dataGrosirs[] = new Grosir(['min' => $mins[$i], 'price' => $price]);
            }

            $product->grosirs()->delete();
            $productGrosirs = $product->grosirs()->saveMany($dataGrosirs);
            return response()->json([
                'success' => true,
                'process' => 'store',
                'data' => fractal($productGrosirs, $this->transformer)->toArray()['data'],
                'message' => 'Success store product grosirs',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'process' => 'store',
                'data' => [],
                'message' => 'Failed store product grosirs',
            ]);
        }
    }
}
