<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Page;
use App\Hashers\MainHasher;

class PageTransformer extends TransformerAbstract
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
    public function transform(Page $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'title' => $model->title,
            'content' => $model->content,
            'slug' => $model->slug
        ];
    }
}
