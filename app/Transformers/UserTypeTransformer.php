<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\{UserTypes};
use App\Hashers\MainHasher;

class UserTypeTransformer extends TransformerAbstract
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
    public function transform(UserTypes $model)
    {
			return [
				'id' => MainHasher::encode($model->id),
				'name' => $model->name
			];
    }
}
