<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Slide;
use App\Hashers\MainHasher;

class SlideTransformer extends TransformerAbstract
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
    public function transform(Slide $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'title' => $model->title,
            'url' => $model->url,
            'image' => $model->image,
            'active' => $model->active,
        ];
    }
}
