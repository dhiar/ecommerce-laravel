<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Hashers\MainHasher;

class TagsTransformer extends TransformerAbstract
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
    public function transform($model)
    {
			return [
				'id' => $model->id,
                'name' => $model->name,
			];
    }
}
