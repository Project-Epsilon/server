<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\BankTransfer;
use App\Classes\WalletManager;
use App\Transformers\WalletTransformer;
use App\Wallet;
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
     * @return Wallet
     */
    public function withdraw(PayPalServiceProvider $paypal, Request $request)
    {
        //Validate inputs.
        $this->validate($request, [
            'email' => 'required|email',
            'amount' => 'required|min:0',
            'wallet_id' => 'required|exists:wallets,id'
        ]);

        $user = $request->user();
        $email = $request->email;
        $amount = $request->amount;

        $wallet = Wallet::find($request->wallet_id);
        $currency = $wallet->currency;
        $currency_code = $currency->code;

        $errors = [];

        //Check if user is the owner if this wallet.
        if ($wallet->user_id != $user->id){
            $errors['user_id'] = 'Wallet user id does not match.';
        }

        //Check enough balance.
        $balance = $wallet->toMoney();
        $withdrawal = Money::$currency_code($currency->toInteger($amount));;

        if(! $balance->greaterThanOrEqual($withdrawal)){
            $errors['balance'] = 'Not enough credits.';
        }

        if ($errors) {
            return response()->json([
                'errors' => $errors
            ]);
        }

        //Create BankTransfer;
        $transfer = new BankTransfer([
            'method' => 'paypal',
            'amount' => -$amount,
            'wallet_id' => $wallet->id
        ]);

        // Execute paypal.
        // Batch header
        // uniqid() is done to prevent duplicate batches from being processed.
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject("mBater Withdrawal");

        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for you for useing mBarter!')
            ->setReceiver($email)
            ->setSenderItemId($transfer->id)
            ->setAmount(new Currency([
                'value' => $amount,
                'currency' => $wallet->currency_code
            ]));

        $payouts = new Payout();
        $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);

        try {
            $output = $payouts->createSynchronous($paypal->getContext());
        } catch (PayPalConnectionException $ex) {
//            $transfer->status = 'failed';  //TODO
//            $transfer->save();
            return response()->json([
                'errors' => [
                    'code' => $ex->getCode(),
                    'data' => $ex->getData()
                ]
            ]);
        }

        $manager = new WalletManager($user);
        $wallet = $manager->withdraw($withdrawal);

//        $transfer->save(); //TODO

        return fractal()
            ->item($wallet)
            ->transformWith(new WalletTransformer())
            ->toArray();
    }

}