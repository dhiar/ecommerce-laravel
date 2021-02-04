<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Cod;
use App\Hashers\MainHasher;

class CodTransformer extends TransformerAbstract
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
    public function transform(Cod $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'location' => $model->location,
            'url_gmaps' => $model->url_gmaps,
            'short_url_gmaps' => $model->short_url_gmaps
        ];
    }
}
