<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\TransactionDetail;
use App\Transformers\AdminProductTransformer;
use App\Hashers\MainHasher;

class TransactionOnlyProductTransformer extends TransformerAbstract
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
    public function transform(TransactionDetail $model)
    {
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["relationships"] = [
            "product" => fractal($model->product, new AdminProductTransformer())->toArray()['data']
        ];
        unset($result['id_transaction']);
        unset($result['id_product']);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
