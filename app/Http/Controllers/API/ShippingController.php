<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\{ShippingRequest};


class ShippingController extends Controller
{
    public function __construct()
	{
        $this->URL_RUANGAPI = env('URL_RUANGAPI');
		// $this->middleware(['auth:admin-api']);
    }

    public function listProvince(ShippingRequest $request)
    {
        $endpoint = $this->URL_RUANGAPI."/provinces";
        return $request->getListArea($endpoint);
    }

    public function listCity(ShippingRequest $request)
    {
        $provinceId = request()->province;
        $endpoint = $this->URL_RUANGAPI."/cities?province=".$provinceId;
        return $request->getListArea($endpoint);
    }

    public function listDistrict(ShippingRequest $request)
    {
        $cityId = request()->city;
        $endpoint = $this->URL_RUANGAPI."/districts?city=".$cityId;
        return $request->getListArea($endpoint);
    }

    public function showProvince(ShippingRequest $request, String $id)
    {
        $endpoint = $this->URL_RUANGAPI."/provinces?id=".$id;
        return $request->getListArea($endpoint);
    }

    public function showCity(ShippingRequest $request, String $id)
    {
        $endpoint = $this->URL_RUANGAPI."/cities?id=".$id;
        return $request->getListArea($endpoint);
    }

    // public function showDistrict(ShippingRequest $request, String $id)
    // {
        // districts?city=1&id=
        // $endpoint = $this->URL_RUANGAPI."/districts?id=".$id;
        // return $request->getListArea($endpoint);
    // }
}