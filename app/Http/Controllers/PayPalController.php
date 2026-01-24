<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PayPalService;
use App\Services\MembershipStatusService;

class PayPalController extends Controller
{

   public function success(Payment $payment, PayPalService $paypal)
    {
        abort_if($payment->status === 'paid', 403);

        $request = new OrdersCaptureRequest(request('token'));
        $request->prefer('return=representation');

        $response = $paypal->client->execute($request);

        if ($response->result->status !== 'COMPLETED') {
            return redirect()
                ->route('member.payments')
                ->with('error', 'Payment not completed.');
        }

        $captureId =
            $response->result->purchase_units[0]
            ->payments->captures[0]->id;

        DB::transaction(function () use ($payment, $captureId) {
            $payment->update([
                'status' => 'paid',
                'paypal_capture_id' => $captureId,
                'paid_at' => now(),
                'late_fee' => 0,
            ]);

            MembershipStatusService::updateForPayment($payment);
        });

        return redirect()
            ->route('member.payments')
            ->with('success', 'Payment successful.');
    }

    
    public function cancel($paymentId)
    {
        return redirect()->route('member.payments')->with('error', 'Payment was cancelled.');
    }
}