<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\{Admin};
use App\Hashers\MainHasher;

class AdminTransformer extends TransformerAbstract
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
    public function transform(Admin $admin)
    {
			return [
				'id' => MainHasher::encode($admin->id),
				'name' => $admin->name,
				'phone' => $admin->phone,
                'email' => $admin->email,
                'job_title' => $admin->job_title,
				'photo' => $admin->photo,
				'created_at' => $admin->created_at,
			];
    }
}
