<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ShippingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function getListArea($endpoint)
    {
        $client = new Client();
        $headers = [
            'Authorization' => \env('API_RUANGAPI'),
            'Accept' => 'application/json',
        ];

        try{
            $response = $client->request('GET', $endpoint, array(
                'headers' => $headers,
            ));
            return response()->json([
                'success' => true,
                'data' => json_decode($response->getBody()->getContents()),
                'code' => $response->getStatusCode(),
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            return response()->json([
                'success' => false,
                'code' => $response->getStatusCode(), // 422
                'data' => json_decode($response->getBody()),
            ]);
        }
    }
}
