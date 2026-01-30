<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Session;

class DonationController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }


    public function showDonationForm()
    {
        return view('donations.form');
    }


    public function processDonation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ]);

        // Generate order with PayPal
        $amount = $validated['amount'];
        $successUrl = route('donation.success');
        $cancelUrl = route('donation.cancel');

        // Create PayPal order
        $response = $this->paypalService->createOrder($amount, $successUrl, $cancelUrl);

        // Redirect to PayPal if the order creation is successful
        foreach ($response->result->links as $link) {
            if ($link->rel == 'approve') {
                // Store the PayPal order ID in the session for later use
                Session::put('paypal_order_id', $response->result->id);
                return redirect()->away($link->href);
            }
        }

        return redirect()->route('donation.form')->with('error', 'There was an error processing your donation.');
    }

    // Handle PayPal success callback
    public function success(Request $request)
    {
        $paypalOrderId = Session::get('paypal_order_id');
        
        // Capture the payment via PayPal API
        $response = $this->paypalService->capturePayment($paypalOrderId);

        if ($response->status === 'COMPLETED') {
            // Save donation to the database
            $donation = new Donation();
            $donation->name = $response->payer->name->given_name;
            $donation->email = $response->payer->email_address;
            $donation->amount = $response->purchase_units[0]->amount->value;
            $donation->payment_status = 'completed';
            $donation->payment_method = 'paypal';
            $donation->paypal_transaction_id = $response->purchase_units[0]->payments->captures[0]->id;
            $donation->save();

            // Set session data to show success message on the frontend
            Session::flash('donation_success', true);
            Session::flash('donation_transaction_id', $response->purchase_units[0]->payments->captures[0]->id);
            Session::flash('donation_amount', $response->purchase_units[0]->amount->value);

            return redirect()->route('donation.form');  
        }

        return redirect()->route('donation.form')->with('error', 'Payment was not successful.');
    }


    // Handle PayPal cancel callback
    public function cancel()
    {
        return redirect()->route('donation.form')->with('error', 'You have canceled the donation.');
    }
}
