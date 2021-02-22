<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\PromoRequest;
use App\Promo;
use Carbon\Carbon;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promo = Promo::first();
        $now = Carbon::now()->toDateTimeString();
        
        if ($promo) {
            $promo = Promo::first()->toArray();
            $promo = array_merge($promo, [
                'now' => $now ,
                'msg_time' => $promo["promo_time"] > $now ? "Promo Time is Available" : "Promo Time is Not available",
                'is_available_time' => $promo["promo_time"] > $now ? "1": "0",
                'msg_active' => $promo["is_active"] == 1 ? "Status Promo is Enable" : "Status Promo is Disable",
            ]);
        } else {
            $promo = [
                'now' => $now ,
                'msg_time' => "Promo Time is Not available",
                'is_available_time' => "0",
                'is_active' => "0",
                'msg_active' => "Status Promo is Disable",
            ];
        }
        // is_available // 0/1
        // Promo Time is Available
        // Promo Time is Not available
        return $promo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromoRequest $request)
    {
        return $request->store();
    }
}
