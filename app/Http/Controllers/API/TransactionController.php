<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommonRequest;
use App\Transformers\{TransactionAddressTransformer, TransactionTransformer, AdminTransformer};
use App\{Product, Transaction, TransactionDetail, Address};
use Illuminate\Support\Facades\DB;
use App\Hashers\MainHasher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\PaginationFormat;
use App\Http\Transformers\IlluminatePaginatorAdapter;
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

        $this->middleware('auth:admin-api', ['only' => [
            'index'
        ]]);
    }

    public function index(CommonRequest $request)
    {
        $model = $this->model;
        $admin = fractal(Auth::user(), AdminTransformer::class)->toArray()['data'];
        $adminId = Auth::id();
        $uTypeId = $admin["relationships"]["user_type"]["id"];
        $uTypeId = is_numeric($uTypeId) ? $uTypeId : MainHasher::decode($uTypeId);

        if (request('q')) { // admin page
            $search = request('q');
            
            $paginator = $this->model->whereHas('transaction_details', function($query) use($search) { 
                // transaction_details
                $query->whereHas('product',function($qproduct) use($search) {
                    $qproduct->whereHas('admin',function($qadmin) use($search){
                        $qadmin->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                    })
                    ->orWhere('name', 'LIKE', "%$search%")
                    ->orWhere('slug', 'LIKE', "%$search%")
                    ->orWhere('image', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
                });
            })
            ->orWhereHas('delivery_status', function($query) use($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->orWhereHas('address', function($query) use($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->paginate(10);
        }

        // admin page
        if (request('number_of_tabs')) {
            $number_of_tabs = request('number_of_tabs');
            $modelByAdmin = $model::whereHas('transaction_details', function($qTransDetails) use ($adminId){ 
                $qTransDetails->whereHas('product', function($qProduct) use ($adminId) {
                    $qProduct->whereHas('admin', function($qAdmin) use ($adminId) {
                        $qAdmin->where('id', $adminId);
                    });
                });
            });

            if ($uTypeId != 3) {
                $modelByAdmin = $model;
            }

            if ($number_of_tabs == '1') {
                $paginator = $modelByAdmin->whereHas('address', function($query) { 
                    $query->whereNull('province_id')->whereNull('city_id')
                    ->whereNull('district_id'); 
                })
                ->whereNull('phone_number')->paginate(10);
            } else if ($number_of_tabs == '2') {
                $paginator = $modelByAdmin->whereHas('address', function($query) { 
                    $query->whereNotNull('province_id')->whereNotNull('city_id')
                    ->whereNotNull('district_id'); 
                })
                ->whereNotNull('phone_number')
                ->where('shipping_cost','=', '0')
                ->whereNull('ekspedisi_name')
                ->paginate(10);
            } else if ($number_of_tabs == '3') {
                $paginator = $modelByAdmin->where('shipping_cost','>', '0')
                ->whereNotNull('ekspedisi_name')
                ->whereNull('payment_image')
                ->orWhere(function($query) use ($adminId, $uTypeId) {
                    $query->whereHas('transaction_details', function($qTransDetails)  use ($adminId, $uTypeId) { 
                        $qTransDetails->whereHas('product', function($qProduct) use ($adminId, $uTypeId) {
                            if ($uTypeId == 3) {
                                $qProduct->whereHas('admin', function($qAdmin) use ($adminId) {
                                    $qAdmin->where('id', $adminId);
                                });
                            }
                        });
                    })
                    ->where('shipping_cost','>', '0')
                    ->whereNotNull('ekspedisi_name')
                    ->whereNull('delivery_number');
                })
                ->orWhere(function($query) use ($adminId, $uTypeId) {
                    $query->whereHas('transaction_details', function($qTransDetails)  use ($adminId, $uTypeId) { 
                        $qTransDetails->whereHas('product', function($qProduct) use ($adminId, $uTypeId) {
                            if ($uTypeId == 3) {
                                $qProduct->whereHas('admin', function($qAdmin) use ($adminId) {
                                    $qAdmin->where('id', $adminId);
                                });
                            }
                        });
                    })
                    ->where('shipping_cost','>', '0')
                    ->whereNotNull('ekspedisi_name')
                    ->whereNotNull('delivery_number')
                    ->whereNotNull('payment_image')
                    ->where('id_delivery_status', '=', '1');
                })
                ->paginate(10);
            } else if ($number_of_tabs == '4') {
                $paginator = $modelByAdmin
                ->where('id_delivery_status', '<=', '3')
                ->whereNotNull('payment_image')
                ->whereNotNull('delivery_number')
                ->paginate(10);
            } else if ($number_of_tabs == '5') {
                $paginator = $modelByAdmin->whereHas('delivery_status', function($query) { 
                    $query->where('id','>', '3'); 
                })
                ->whereNotNull('payment_image')
                ->whereNotNull('delivery_number')
                ->paginate(10);
            }
        }

        if (request('q') || request('number_of_tabs'))
        {
            $result = $paginator->getCollection();
                $response = fractal()
                ->collection($result,  $this->transformer)
                ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                ->toArray();

            return PaginationFormat::commit($paginator, $response);
        }
        
        return $request->index($model, $this->transformer);
    }

    public function indexByInvoice(CommonRequest $request)
    {
        $model = $this->model;
        if (request('invoice')){ // guest page
            $paginator = $model->where('invoice',request('invoice'))->paginate(1);
            
            $result = $paginator->getCollection();
            $response = fractal()
            ->collection($result,  $this->transformer)
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();

            return PaginationFormat::commit($paginator, $response);
        }
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
        if (request('storage_payment_image'))
        {
            $obj = $this->model->find($id);
            $pathCurrentImage = storage_path('app/'.$obj->payment_image);

			if (file_exists($pathCurrentImage)) {
                @unlink($pathCurrentImage);
            }
            request()->request->add(['payment_image' => request('storage_payment_image')]);
            request()->request->remove('storage_payment_image');
        } else {
            request()->request->remove('payment_image');
        }

		return $request->update($id, $this->model, $this->transformer);
	}
}
