<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;
use App\Transformers\AddressTransformer;
use App\Hashers\MainHasher;

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
        $result["relationships"] = [
            'address' => fractal($model->address, new AddressTransformer())->toArray()['data'],
            'delivery_status' => $model->delivery_status,
            'transaction_details' => $model->transaction_details
        ];

        unset($result['id_address']);
        unset($result['id_delivery_status']);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
