<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;
use App\Transformers\{AddressTransformer, DeliveryStatusTransformer, TransactionOnlyProductTransformer};
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $model)
    {
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["payment_image"] = $model->payment_image ? env('APP_URL').Storage::url($model->payment_image) : "";

        $result["relationships"] = [
            'address' => fractal($model->address, new AddressTransformer())->toArray()['data'],
            'delivery_status' => fractal($model->delivery_status, new DeliveryStatusTransformer())->toArray()['data'],
            'transaction_details' => fractal($model->transaction_details, new TransactionOnlyProductTransformer())->toArray()['data']
        ];

        unset($result['id_address']);
        // unset($result['id_delivery_status']);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
