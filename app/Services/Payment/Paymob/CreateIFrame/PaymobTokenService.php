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
            ['api_key' => 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRBNU5qZ3lOQ3dpYm1GdFpTSTZJakUzTmpjMk1UY3lOemt1T0RNek5UazNJbjAuNGdXLXVid2dqbGRTQ1I5VjVVQWg0dm1KNlpvSUNvelNKRnNpUW85VFhmOHhXTXlIUFRfaGJEc05ERWRkeEJ6aTZrSGY5VVFjS1ZCWmRrekpKd2Q1dmc='],
            'json'
        );
        $data = $response->json();
        return $data['token'] ;
    }
}
