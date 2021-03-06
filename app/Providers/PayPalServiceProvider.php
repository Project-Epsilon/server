<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\Payout;
use PayPal\Api\PayoutItem;
use PayPal\Api\Currency;


class PayPalServiceProvider extends ServiceProvider
{
    private $context;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Add paypal library as singleton
        $this->app->singleton(PayPalServiceProvider::class, function ($app) {
            $this->context =  new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    config('services.paypal.client_id'),     // ClientID
                    config('services.paypal.client_secret')      // ClientSecret
                )
            );

            $this->context->setConfig([
                'mode' => config('app.debug')? 'sandbox': 'live',
            ]);

            return $this;
        });
    }

    public function getContext()
    {
        return $this->context;
    }

    /**
     * Builds the PayPal checkout form.
     *
     * @param $currency
     * @param $amount
     * @param $user
     * @return Payment|null
     */
    public function createPayPalCheckout($currency, $amount, $user)
    {
        $payer = (new Payer())->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName(config('app.name') . 'money transfer.')
            ->setCurrency($currency)->setQuantity(1)->setPrice($amount);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $transactionAmount = new Amount();
        $transactionAmount->setCurrency($currency)->setTotal($amount);

        $transaction = new Transaction();
        $transaction->setAmount($transactionAmount)
            ->setItemList($itemList)
            ->setDescription(config('app.name') . ' funds added on: '. Carbon::now())
            ->setInvoiceNumber(uniqid());

        $userID = encrypt($user->id);
        $baseUrl = config('app.url') . '/api/transfer/bank/deposit';
        $redirects = new RedirectUrls();
        $redirects->setReturnUrl($baseUrl . '?success=true&user=' . $userID)
            ->setCancelUrl($baseUrl . '?success=false&user=' . $userID);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)->setRedirectUrls($redirects)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->getContext());
        } catch (PayPalConnectionException $ex) {
            logger()->error($ex->getCode() . ' ' .  $ex->getData());
            return null;
        }

        return $payment;
    }

    /**
     * Executes payment on the Paypal Servers.
     *
     * @param $payerID
     * @param $paymentId
     * @return null|Payment
     */
    public function executePayPalPayment($payerID, $paymentId)
    {
        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);

        try {
            $payment = Payment::get($paymentId, $this->getContext());
            $payment->execute($execution, $this->getContext());
        } catch (PayPalConnectionException $ex) {
            logger()->error($ex->getCode() . ' ' .  $ex->getData());
            return null;
        }

        //Retrieve new information about payment.
        $payment = null;

        try {
            $payment = Payment::get($paymentId, $this->getContext());
        } catch (PayPalConnectionException $ex) {
            logger()->error($ex->getCode() . ' ' .  $ex->getData());
            return null;
        }

        return $payment;
    }

    /**
     * @param $email
     * @param $transferId
     * @param $amount
     * @param $currencyCode
     * @return null|\PayPal\Api\PayoutBatch
     */
    public function createPayout($email, $transferId, $amount, $currencyCode)
    {
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject('mBater withdrawal service.');

        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thank you for using mBarter!')
            ->setReceiver($email)
            ->setSenderItemId($transferId)
            ->setAmount(new Currency([
                'value' => $amount,
                'currency' => $currencyCode
            ]));

        $payouts = new Payout();
        $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);

        try {
            $payment = $payouts->createSynchronous($this->getContext());
        } catch (PayPalConnectionException $ex) {
            logger()->error($ex->getCode() . ' ' .  $ex->getData());
            return null;
        }

        return $payment;
    }

}
