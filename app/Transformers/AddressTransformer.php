<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Address;
use App\Hashers\MainHasher;
use App\Http\Controllers\API\ShippingController;
use App\Http\Requests\API\{ShippingRequest};

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

        $request = new ShippingRequest();
        $endpoint_province = env('URL_RUANGAPI')."/provinces?id=".$result["province_id"];
        $endpoint_city = env('URL_RUANGAPI')."/cities?id=".$result["city_id"];
        $endpoint_district = env('URL_RUANGAPI')."/districts?city=".$result["city_id"]."&id=".$result["district_id"];
        
        $province = $request->getListArea($endpoint_province)->original["data"]->data->results->name;
        $city = $request->getListArea($endpoint_city)->original["data"]->data->results->name;
        $district = $request->getListArea($endpoint_district)->original["data"]->data->results->name;
        
        $result["province"] = $province;
        $result["city"] = $city;
        $result["district"] = $district;
        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }
}
