<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;
use App\Transformers\{AllAddressTransformer};
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;

class TransactionAddressTransformer extends TransformerAbstract
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
            'address' => fractal($model->address, new AllAddressTransformer())->toArray()['data'],
        ];

        unset($result['id_address']);
        // unset($result['id_delivery_status']);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
