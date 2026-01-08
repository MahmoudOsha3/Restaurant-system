<?php

namespace App\Services\Payment\Paymob\CreateIFrame ;
use Illuminate\Support\Facades\Cache;

class PaymobTokenService
{
    public static function getCachedToken()
    {
        $cacheKey = 'paymob_auth_token';
        
        return Cache::remember($cacheKey, 3600, function () {
            return self::generateToken() ;
        });
    }

    public static function generateToken()
    {
        $response = BuildRequestService::request(
            'POST',
            config('services.paymob.base_url') . '/api/auth/tokens',
            ['api_key' => config('services.paymob_api_key') ],
            'json'
        );
        $data = $response->json();
        return $data['token'] ;
    }
}
