<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\{ShippingRequest};


class ShippingController extends Controller
{
    public function __construct()
	{
        // $this->URL_RUANGAPI = env('URL_RUANGAPI');
		// $this->middleware(['auth:admin-api']);
    }

    public function listProvince(ShippingRequest $request)
    {
        $data = \Indonesia::allProvinces();
        return response()->json([
            'success' => true,
            'data' => $data,
            'code' => 200,
        ]);
        // $endpoint = $this->URL_RUANGAPI."/provinces";
        // return $request->getListArea($endpoint);
    }

    public function listCity(ShippingRequest $request)
    {
        $provinceId = request()->province;
        $data = \Indonesia::findProvince($provinceId, ['cities']);
        return response()->json([
            'success' => true,
            'data' => $data->cities,
            'code' => 200,
        ]);
    }

    public function listDistrict(ShippingRequest $request)
    {
        $cityId = request()->city;
        $data = \Indonesia::findCity($cityId, ['districts']);
        return response()->json([
            'success' => true,
            'data' => $data->districts,
            'code' => 200,
        ]);
    }

    public function showProvince(ShippingRequest $request, String $id)
    {
        $data = \Indonesia::findProvince($id);
        return response()->json([
            'success' => true,
            'data' => $data,
            'code' => 200,
        ]);
    }

    public function showCity(ShippingRequest $request, String $id)
    {
        $data = \Indonesia::findCity($id);
        return response()->json([
            'success' => true,
            'data' => $data,
            'code' => 200,
        ]);
    }

    public function showDistrict(ShippingRequest $request, String $id)
    {
        $data = \Indonesia::findDistrict($id);
        return response()->json([
            'success' => true,
            'data' => $data,
            'code' => 200,
        ]);

        // districts?city=1&id=
        // $endpoint = $this->URL_RUANGAPI."/districts?id=".$id;
        // return $request->getListArea($endpoint);
    }
}