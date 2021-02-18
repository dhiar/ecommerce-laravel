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

        DB::beginTransaction();
		try {
			$dataGrosir = Grosir::create([
                'id_product' => $productId,
                'min'  => request('min'),
                'price' => request('price')
            ]);

			DB::commit();
			return response()->json([
                'success' => true,
                'process' => 'store',
                'data' => fractal($dataGrosir, $this->transformer)->toArray()['data'],
                'message' => 'Success store product grosirs',
            ]);
		}
		catch (\Exception $e) {
			return response()->json([
                'success' => false,
                'process' => 'store',
                'data' => [],
                'message' => 'Failed store product grosirs',
            ]);
			DB::rollback();
		}
    }

    public function show($id, CommonRequest $request)
	{
		return $request->show($id, $this->model, $this->transformer);
	}

    public function update(CommonRequest $request, $id)
	{
        // relationships
        
        $productId = MainHasher::decode(request('id_product'));
        request()->request->add(['id_product' => $productId]);
        $params = request()->except(['relationships','data','id_grosir','current_min', 'updated_at', 'created_at']);

		return $request->update($id, $this->model, $this->transformer, $params);
	}

    public function destroy($id,CommonRequest $request)
	{
		return $request->destroy($this->model, $id);
	}
}
