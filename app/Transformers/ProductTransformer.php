<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;
use App\Hashers\MainHasher;

class ProductTransformer extends TransformerAbstract
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
    public function transform(Product $model)
    {
        return $model->toArray();
        // return [
        //     'id' => MainHasher::encode($model->id),
        //     'content' => $model->content,
        //     'relationships' => [
        //         'user' => $model->testimonyable
        //     ],
        //     'created_at' => $model->created_at,
        // ];
    }
}
