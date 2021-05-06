<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Admin;
use App\Transformers\{AddressTransformer,UserTypeTransformer, RekeningTransformer};
use App\Hashers\MainHasher;
use Illuminate\Support\Facades\Storage;

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
        $userPhoto = env('APP_URL').'/img/profile/'.$admin->photo;
        $pathPhoto = public_path('img/profile/').$admin->photo;

        if (!file_exists($pathPhoto)) {
            $userPhoto = $admin->photo;
        }

        return [
            'id' => MainHasher::encode($admin->id),
            'name' => $admin->name,
            'phone' => $admin->phone,
            'whatsapp' => $this->hp($admin->phone),
            'email' => $admin->email,
            'job_title' => $admin->job_title,
            // 'photo' => public_path('img/profile/').$admin->photo,
            'photo' => $userPhoto,
            'store_name' => $admin->store_name,
            'store_slug' => $admin->store_slug,
            'created_at' => $admin->created_at,
            'relationships' => [
                'address' => is_object($admin->address) ? fractal($admin->address, new AddressTransformer())->toArray()['data'] : "",
                'rekenings' => $admin->rekenings ? fractal($admin->rekenings, new RekeningTransformer())->toArray()['data'] : "",
                'user_type' => $admin->usertype ? fractal($admin->usertype, new UserTypeTransformer())->toArray()['data'] : ""
            ]
        ];
    }

    function hp($nohp) {
        // kadang ada penulisan no hp 0811 239 345
        $nohp = str_replace(" ","",$nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace("(","",$nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace(")","",$nohp);
        // kadang ada penulisan no hp 0811.239.345
        $nohp = str_replace(".","",$nohp);
    
        // cek apakah no hp mengandung karakter + dan 0-9
        if(!preg_match('/[^+0-9]/',trim($nohp))){
            // cek apakah no hp karakter 1-3 adalah +62
            if(substr(trim($nohp), 0, 3)=='+62'){
                $hp = trim($nohp);
            }
            else if(substr(trim($nohp), 0, 2)=='62'){
                $hp = trim($nohp);
            }
            // cek apakah no hp karakter 1 adalah 0
            elseif(substr(trim($nohp), 0, 1)=='0'){
                $hp = '+62'.substr(trim($nohp), 1);
            }
        }
        return $hp;
    }
}
