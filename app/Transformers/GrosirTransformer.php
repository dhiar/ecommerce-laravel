<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Grosir;
use App\Hashers\MainHasher;

class GrosirTransformer extends TransformerAbstract
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
    public function transform(Grosir $model)
    {
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);
        $result["id_product"] = MainHasher::encode($result["id_product"]);
        $result["relationships"] = [
            'product' => $model->product,
        ];
        return $result;
    }
}
