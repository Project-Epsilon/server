<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\BankTransfer;
use App\Classes\WalletManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Providers\PayPalServiceProvider;

use Money\Money;
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
        $user = $request->user();

        // Batch header
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())	// This is done to prevent duplicate batches // from being processed.
            ->setEmailSubject("mBater Withdrawal");

        $this->validate($request, [
            'email' => 'required',
            'amount' => 'required',
            'wallet_id' => 'required'
        ]);

        $email = $request->email;
        $amount = $request->amount;

        $wallet = $user->wallets()
            ->where('id', $request->wallet_id)
            ->first();

        if ($wallet){

        }

        $transfer = BankTransfer::create([
            'method' => 'paypal',
            'amount' => -$amount,
            'wallet_id' => $wallet->id
        ]);

        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for you for useing mBarter!')
            ->setReceiver($email)
            ->setSenderItemId($transfer->id)
            ->setAmount(new Currency('{ 
                        "value": "' . $amount . '", 
                        "currency": "' . $wallet->currency_code .'"
                    }'));

        $payouts = new Payout();

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);

        //Execute Payout
        $errors = null;

        try {
            $output = $payouts->createSynchronous($paypal->getContext());
        } catch (PayPalConnectionException $ex) {

            $errors = [
                'code' => $ex->getCode(),
                'data' => $ex->getData()
            ];
        }

        if ($errors){
            response()->json([
                'errors' => $errors
            ]);
        }

        $manager = new WalletManager($user);
        //$manager->withdraw(new Money( ,new \Money\Currency()));

        return response()->json();
    }

}