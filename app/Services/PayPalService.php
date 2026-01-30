<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalService
{
    private PayPalHttpClient $client;

    public function __construct()
    {
        $environment = app()->environment('production')
            ? new ProductionEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
            : new SandboxEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            );

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder(float $amount, string $successUrl, string $cancelUrl)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW', 
            ],
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($amount, 2, '.', ''),
                ],
            ]],
        ];

        return $this->client->execute($request);
    }

    public function capturePayment(string $orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');
        $response = $this->client->execute($request);
        return $response;
    }


    
}