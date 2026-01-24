<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceived;
use App\Services\PayPalService;
use App\Services\MembershipStatusService;

use App\Models\PaymentCycle;

class Payments extends Component
{
    public $payments;

    public function mount()
    {
        $member = Auth::user()->member;

        $this->payments = Payment::with('paymentCycle')
            ->where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function totalDue($payment)
    {
        return $payment->amount_due + $payment->late_fee;
    }

 public function payNow($paymentId)
    {
        $payment = Payment::with('paymentCycle')->findOrFail($paymentId);

        if ($payment->status === 'paid') {
            session()->flash('error', 'Payment already completed.');
            return;
        }

        $amount = $payment->amount_due + $payment->late_fee;

        $paypal = app(PayPalService::class);

        $response = $paypal->createOrder(
            $amount,
            route('member.paypal.success', $payment->id),
            route('member.paypal.cancel', $payment->id)
        );

        // SAVE ORDER ID
        $payment->update([
            'paypal_order_id' => $response->result->id,
            'paypal_order_status' => $response->result->status,
            'paypal_order_created_at' => Carbon::parse($response->result->create_time),
            'payment_initiated_at' => now(),       
        ]);

        foreach ($response->result->links as $link) {
            if ($link->rel === 'approve') {
                return redirect()->away($link->href);
            }
        }

        session()->flash('error', 'Unable to initiate PayPal payment.');
    }


   public function render()
    {
        return view('livewire.member.payments', [
            'payments' => $this->payments,
            'memberStatus' => Auth::user()->member->membership_status
        ])->layout('layouts.app');
    }
}