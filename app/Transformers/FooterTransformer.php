<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\{Footer, Navigation, Page};
use App\Hashers\MainHasher;

class FooterTransformer extends TransformerAbstract
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
    public function transform(Footer $model)
    {
        return [
            'id' => MainHasher::encode($model->id),
            'page_id' => MainHasher::encode($model->page_id),
            'navigation_id' => MainHasher::encode($model->navigation_id),
        ];
    }
}
