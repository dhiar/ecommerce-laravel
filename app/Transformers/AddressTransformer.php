<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Address;
use App\Hashers\MainHasher;
use App\Http\Controllers\API\ShippingController;
use App\Http\Requests\API\{ShippingRequest};

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class AddressTransformer extends TransformerAbstract
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
    public function transform(Address $model)
    {
        $result = $model->toArray();
        $result["id"] = MainHasher::encode($result["id"]);

        $province = $city = $district = null;
        if(is_numeric($result['province_id'])) {
            $province = \Indonesia::findProvince($result['province_id']);
        }
        if(is_numeric($result['city_id'])) {
            $city = \Indonesia::findCity($result['city_id']);
        }
        if(is_numeric($result['district_id'])) {
            $district = \Indonesia::findDistrict($result['district_id']);
        }
        $result["province"] = $province->name ?? $province;
        $result["city"] = $city->name ?? $city;
        $result["district"] = $district->name ?? $district;

        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
