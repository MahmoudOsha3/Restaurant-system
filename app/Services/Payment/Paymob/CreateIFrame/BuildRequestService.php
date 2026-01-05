<?php

namespace App\Services\Payment\Paymob\CreateIFrame ;

use Illuminate\Support\Facades\Http;

class BuildRequestService
{
    public static function request($method, $url , $data = null ,$type='json')
    {
        try {
            $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    ])->send($method,  $url, [
                $type => $data
            ]);

        return $response;

        } catch (\Exception $e) {

            throw new \Exception("HTTP request failed: " . $e->getMessage());
        }
    }
}
