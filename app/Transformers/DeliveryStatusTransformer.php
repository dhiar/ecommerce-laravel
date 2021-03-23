<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\DeliveryStatus;
use App\Hashers\MainHasher;

class DeliveryStatusTransformer extends TransformerAbstract
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
    public function transform(DeliveryStatus $model)
    {
        $result = $model->toArray();
        // $result["id"] = MainHasher::encode($result["id"]);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
