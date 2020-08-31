<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\{Address, User, UserTypes};
use App\Hashers\MainHasher;

class UserTransformer extends TransformerAbstract
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
    public function transform(User $user)
    {
			return [
				'id' => MainHasher::encode($user->id),
				'name' => $user->name,
				'gender' => $user->gender,
				'phone' => $user->phone,
				'email' => $user->email,
				'address' => Address::find($user->id_address)->name,
				'id_user_type' => $user->id_user_type,
				'user_type_name' => UserTypes::find($user->id_user_type)->name,
				'created_at' => $user->created_at,
			];
    }
}
