<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Address;
use App\Hashers\MainHasher;

class AddressTransformer extends TransformerAbstract
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
    public function transform(Address $model)
    {
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
