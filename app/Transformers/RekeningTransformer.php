<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Rekening;
use App\Hashers\MainHasher;

class RekeningTransformer extends TransformerAbstract
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
    public function transform(Rekening $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'name' => $model->name,
            'rekening' => $model->rekening,
            'number' => $model->number,
        ];
    }
}
