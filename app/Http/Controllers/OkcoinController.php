<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OkcoinController extends Controller
{
    public function index()
    {
        $time = Http::get('https://www.okcoin.com/api/general/v3/time')->json();
        $method = 'POST'; // or POST
        $query = '/api/spot/v3/orders'; // query destination
        $body = ['side'=>'buy', 'instrument_id'=>'BTC-USDT']; // body of query, if exists'[{"side":"buy","instrument_id":"BTC-USD"}]'
        $body=json_encode($body);
        $sign = base64_encode(
            hash_hmac(
                'sha256',
                $time['iso'] .
                $method .
                $query .
                $body,
                '7E448CD61A3D7D4EF70D09A7DFB93FED',
                true
            )
        );
        $wallet = Http::withHeaders([
            'Content-Type' => 'application/json',
            'OK-ACCESS-KEY' => '302f7866-c52b-4cf5-a4f8-c190c3bf9f64', // api-key
            'OK-ACCESS-SIGN' => $sign, // hueta
            'OK-ACCESS-TIMESTAMP' => $time['iso'], // timestamp from first query
            'OK-ACCESS-PASSPHRASE' => '64646290b', // password where created key
        ])->get('https://www.okcoin.com' . $query)->json();
        dd($wallet);
    }
}
