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

        // $request = new ShippingRequest();
        $endpoint_province = env('URL_RUANGAPI')."/provinces?id=".$result["province_id"];
        $endpoint_city = env('URL_RUANGAPI')."/cities?id=".$result["city_id"];
        $endpoint_district = env('URL_RUANGAPI')."/districts?city=".$result["city_id"]."&id=".$result["district_id"];

        $province = $this->getListArea($endpoint_province);
        $city = $this->getListArea($endpoint_city);
        $district = $this->getListArea($endpoint_district);

        $result["province"] = $province["data"]->data->results->name;
        $result["city"] = $city["data"]->data->results->name;
        $result["district"] = $district["data"]->data->results->name;

        unset($result['created_at']);
        unset($result['updated_at']);
        unset($result['deleted_at']);
        return $result;
    }

    public function getListArea($endpoint)
    {
        $client = new Client();
        $headers = [
            'Authorization' => \env('APIKEY_RUANGAPI'),
            'Accept' => 'application/json',
        ];

        try{
            $response = $client->request('GET', $endpoint, array(
                'headers' => $headers,
            ));
            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents()),
                'code' => $response->getStatusCode(),
            ];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            return [
                'success' => false,
                'code' => $response->getStatusCode(), // 422
                'data' => json_decode($response->getBody())
            ];
        }
    }
}
