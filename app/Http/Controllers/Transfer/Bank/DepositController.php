<?php

namespace App\Http\Controllers\Transfer\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Providers\PayPalServiceProvider;

use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use PayPal\Api\PaymentExecution;

class DepositController extends Controller
{

    /**
     * Creates a bank deposit
     *
     * @param PayPalServiceProvider $paypal
     * @param  \Illuminate\Http\Request  $request
     * @return Payment
     */
    public function deposit(PayPalServiceProvider $paypal, Request $request)
    {
        /**
         * Get currency type and amount
         */
        $currency = $request->currency;
        $amount = $request->amount;

        /**
         * Payer that represents person adding money to wallet
         */
        $payer = new Payer();
        $payer->setPaymentMethod("paypal"); // We are using paypal

        /**
         * Item information.
         * In this case we just have 1 item. Money transfer.
         */
        $item1 = new Item();
        $item1->setName('mBarter money transfer.')
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($amount);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        /**
         * Amount
         * Specify payment amount.
         */
        $transactionAmount = new Amount();
        $transactionAmount->setCurrency($currency)
            ->setTotal($amount);

        /**
         * Transaction
         * Define contract of payment.
         * This includes payment and payee
         */
        $transaction = new Transaction();
        $transaction->setAmount($transactionAmount)
            ->setItemList($itemList)
            ->setDescription('mBarter funds added on: '. (new \DateTime())->format('Y-m-d H:i:s'))
            ->setInvoiceNumber(uniqid());

        /**
         * Redirect Urls
         * Urls to be used by paypal AFTER the
         * accept or cancellation of payment.
         */
        $baseUrl = config('app.url') . '/api/transfer/bank/deposit';
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl . "?success=true")
            ->setCancelUrl($baseUrl . "?success=false");

        /**
         * Payment
         * Payment object to be returned
         */
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        /**
         * Create a payment
         * This is done by passing the api context.
         */
        try {
            $payment->create($paypal->getContext());
        } catch (PayPalConnectionException $ex) {
            return response()
                ->json(['errors' => ['code' => $ex->getCode(),
                    'data' => $ex->getData()
                ]
                ]);
        }

        return $payment;
    }

    public function paypalCallback(PayPalServiceProvider $paypal, Request $request)
    {
        // Check if the payment was accepted.
        if ($request->success == 'true') {

            // Paypal will send paymentId in the request.
            $paymentId = $request->paymentId;
            $payment = Payment::get($paymentId, $paypal->getContext());

            /**
             * Payment Execute
             */
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);

            try {

                /**
                 * Execute the payment
                 */
                $result = $payment->execute($execution, $paypal->getContext());

                try {
                    $payment = Payment::get($paymentId, $paypal->getContext());
                } catch (PayPalConnectionException $ex) {
                    return response()
                        ->json(['errors' => ['code' => $ex->getCode(),
                            'data' => $ex->getData()
                        ]
                        ]);
                }
            } catch (PayPalConnectionException $ex) {
                return response()
                    ->json(['errors' => ['code' => $ex->getCode(),
                        'data' => $ex->getData()
                    ]
                    ]);

            }

            return $payment;
        } else {
            return response()
                ->json(['errors' => ['data'=>'User Cancelled the Approval.']]);
        }

    }
}