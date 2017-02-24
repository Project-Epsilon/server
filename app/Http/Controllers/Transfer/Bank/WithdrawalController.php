<?php

namespace App\Http\Controllers\Transfer\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Providers\PayPalServiceProvider;

use \PayPal\Api\PayoutSenderBatchHeader;
use \PayPal\Api\Payout;
use \PayPal\Api\PayoutItem;
use \PayPal\Api\Currency;

use PayPal\Exception\PayPalConnectionException;

class WithdrawalController extends Controller
{

    /**
     * Creates a bank withdrawal.
     *
     * @param PayPalServiceProvider $paypal
     * @param Request $request
     * @return \PayPal\Api\PayoutBatch
     */

    public function withdraw(PayPalServiceProvider $paypal, Request $request)
    {
        /**
         * This is the output
         * {
                    "sender_batch_header":{
                        "sender_batch_id":"2014021801",
                        "email_subject":"You have a Payout!"
                    },
                    "items":[
                        {
                            "recipient_type":"EMAIL",
                            "amount":{
                                "value":"1.0",
                                "currency":"USD"
                            },
                            "note":"Thanks for your patronage!",
                            "sender_item_id":"2014031400023",
                            "receiver":"shirt-supplier-one@mail.com"
                        }
                    ]
                }
         */

        $payouts = new Payout();

        // Batch header
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())	// This is done to prevent duplicate batches
                                                        // from being processed.
        ->setEmailSubject("You have a Payout from mBarter!");


        /**
         * Create item to payout. This include the persons email, value and currency
         */
        // First get necessary info from request
        $email = $request->email;
        $senderItemId = $request->senderItemId;
        $amount = $request->amount;
        $currency = $request->currency;

        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for you for useing mBarter!')
            ->setReceiver($email)
            ->setSenderItemId("$senderItemId")
            ->setAmount(new Currency('{ 
                        "value": "' . $amount . '", 
                        "currency": "' . $currency .'"
                    }'));

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);


        /**
         * Create payout
         */
        try {
            $output = $payouts->createSynchronous($paypal->getContext());
        } catch (PayPalConnectionException $ex) {
            return response()
                ->json(['errors' => ['code' => $ex->getCode(),
                    'data' => $ex->getData()
                ]
                ]);
        }

        return $output;
    }

}