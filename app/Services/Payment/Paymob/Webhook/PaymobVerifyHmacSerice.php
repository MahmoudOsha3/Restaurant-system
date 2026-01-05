<?php

namespace App\Services\Payment\Paymob\Webhook ;


class PaymobVerifyHmacSerice
{
    public static function verifyHmac($request)
    {
        $receivedHmac = $request->input('hmac');
        $data = [
            'amount_cents' => $request->amount_cents,
            'created_at' => $request->created_at,
            'currency' => $request->currency,
            'error_occured' => $request->error_occured,
            'has_parent_transaction' => $request->has_parent_transaction,
            'id' => $request->id,
            'integration_id' => $request->integration_id,
            'is_3d_secure' => $request->is_3d_secure,
            'is_auth' => $request->is_auth,
            'is_capture' => $request->is_capture,
            'is_refunded' => $request->is_refunded,
            'is_standalone_payment' => $request->is_standalone_payment,
            'is_voided' => $request->is_voided,
            'order' => $request->order,
            'owner' => $request->owner,
            'pending' => $request->pending,
            'source_data.pan' => $request->{'source_data.pan'},
            'source_data.sub_type' => $request->{'source_data.sub_type'},
            'source_data.type' => $request->{'source_data.type'},
            'success' => $request->success,
        ];

        $concatenated = '';
        $concatenated = implode('', $data);

        $calculatedHmac = hash_hmac(
            'sha512',
            $concatenated,
            config('services.paymob.hmac_secret')
        );

        return hash_equals($calculatedHmac, $receivedHmac);
    }
}
