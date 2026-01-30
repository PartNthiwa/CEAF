<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPal\Api\Payout;
use PayPal\Api\PayoutItem;
use PayPalCheckoutSdk\Payouts\SenderBatchHeader;
use PayPal\Api\Amount;
use PayPal\Api\PayoutBatchGetRequest;

class PayPalPayoutService
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


     /**
     * Send a payout to a single member
     * @param string $receiverEmail - PayPal email of the recipient
     * @param float $amount - Amount to payout
     * @param string $currency - Currency code (e.g. USD)
     * @return mixed
     */
  public function sendPayout(string $receiverEmail, float $amount, string $currency = 'USD')
    {
        $payout = new Payout();
        $senderBatchHeader = new SenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
                          ->setEmailSubject("You have a event payout from Carolina East Africa Foundation!")
                          ->setSenderBatchStatus('SUCCESS');

        $item = new PayoutItem();
        $item->setRecipientType('EMAIL')
             ->setReceiver($receiverEmail)
             ->setAmount(new Amount($currency, number_format($amount, 2, '.', '')))
             ->setNote('Payout from your approved event')
             ->setSenderItemId(uniqid());

        $payout->setSenderBatchHeader($senderBatchHeader);
        $payout->addItem($item);

        try {
            $response = $this->client->execute($payout);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception("Error processing payout: " . $e->getMessage());
        }
    }

    /**
     * Check payout status
     * @param string $batchId - Batch ID of the payout batch
     * @return mixed
     */
    public function getPayoutStatus(string $batchId)
    {
        $request = new \PayPal\Api\PayoutBatchGetRequest($batchId);
        return $this->client->execute($request);
    }
}
