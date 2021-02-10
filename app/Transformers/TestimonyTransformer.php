<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Testimony;
use App\Hashers\MainHasher;

class TestimonyTransformer extends TransformerAbstract
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
    public function transform(Testimony $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'content' => $model->content,
            'relationships' => [
                'user' => $model->testimonyable
            ]
        ];
    }
}
