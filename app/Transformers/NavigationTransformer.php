<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Navigation;
use App\Hashers\MainHasher;

class NavigationTransformer extends TransformerAbstract
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
    public function transform(Navigation $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'name' => $model->name,
        ];
    }
}
