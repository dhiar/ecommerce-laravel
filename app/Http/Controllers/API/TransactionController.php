<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\TransactionTransformer;
use App\{Product, Transaction, TransactionDetail, Address};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;

class TransactionController extends Controller
{
    // use Auth;
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->model = new Transaction();
        $this->model_details = new TransactionDetail();
        $this->transformer = new TransactionTransformer();
    }

    public function index(CommonRequest $request)
    {
        return $request->index($this->model, $this->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonRequest $request)
    {
        if (\request('is_wa') == "1") {
            $slugName = Str::slug(request('name'),"-");
            $invoice = "wa_" .$slugName.'_'.Carbon::now()->timestamp;
            $count = (int)request('count');
            $product = request('product');
            
            DB::beginTransaction();
            try {
                $address = Address::whereName(request('address'));
                if ($address->count() == 0)
                {
                    $address = Address::create([
                        'name' => request('address')
                    ]);
                } else {
                    $address = $address->first();
                }

                $transaction = $this->model::create([
                    'invoice' => $invoice,
                    'total_weight'  => $product['weight'] * $count ,
                    'total_price' => $product['price'] * $count,
                    'id_address' => $address->id,
                    'id_delivery_status' => 1,
                ]);

                for ($i=0; $i < $count ; $i++) {
                    $productId = is_numeric($product['id']) ? $product['id'] : MainHasher::decode($product['id']);
                    $this->model_details::create([
                        'id_transaction' => $transaction->id,
                        'id_product' => $productId,
                        'count' => 1,
                        'subtotal_weight' => $product['weight'] ,
                        'subtotal_price' => $product['price']
                    ]);
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'process' => 'store',
                    'data' => fractal($transaction, $this->transformer)->toArray()['data'],
                    'message' => 'Success store transaction',
                ]);
            }
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'process' => 'store',
                    'data' => [],
                    'message' => 'Failed store transaction',
                ]);
                DB::rollback();
            }
        }
    }

    public function show($id, CommonRequest $request)
	{
		return $request->show($id, $this->model, $this->transformer);
	}

    public function update(CommonRequest $request, $id)
	{
		return $request->update($id, $this->model, $this->transformer);
	}
}
