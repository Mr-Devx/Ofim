<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('cinet:paiement', function () {
    $api_key = "1417473556633731bb262d55.43502492";
    $site_id = "844847";
    $secret_key = "276335878633ee708bfbec7.83578782";
    $api_url = "https://api-checkout.cinetpay.com/v2/payment";

    $response = Http::post($api_url, [
        'apikey' => $api_key,
        'site_id' => $site_id,
        'transaction_id' => Date('Y-m-d-h-i-s'),
        'amount' => 5000,
        'currency' => "XOF",
        'notify_url' => "http://127.0.0.1:8000",
        'return_url' => "http://127.0.0.1:8000",
        'channels' => "ALL",
        'description' => "RESERVATION VEHICULE de location",
    ]);

    if($response->successful()){
        $data_response = $response->throw()->json();

        dd($data_response);
    }

    dd("FALL");
});