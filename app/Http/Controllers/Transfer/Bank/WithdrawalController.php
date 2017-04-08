<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\BankTransfer;
use Illuminate\Http\Request;
use App\Classes\WalletManager;
use App\Http\Controllers\Controller;
use App\Transformers\WalletTransformer;
use App\Providers\PayPalServiceProvider;

class WithdrawalController extends Controller
{

    /**
     * Creates a bank withdrawal.
     *
     * @param PayPalServiceProvider $paypal
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    /**
     * @api {post} transfer/bank/withdraw  Withdrawal
     * @apiVersion 0.2.0
     * @apiName Withdraw with payout.
     * @apiGroup BankTransfer
     *
     * @apiDescription Processes a withdrawal PayPal payout to the email address.
     *
     * @apiParam {String} email             Email to send payout to.
     * @apiParam {String} amount            Amount for deposit.
     * @apiParam {Number} wallet_id         Wallet to withdraw from
     *
     * @apiSuccess {Object} data            The wallet information.
     * @apiSuccess {Number} data.id         User id.
     * @apiSuccess {Number} data.user_id    Owner of the user.
     * @apiSuccess {String} data.balance    Wallet balance.
     * @apiSuccess {Number} data.visible    Visibility of the wallet.
     * @apiSuccess {String} data.currency_code Currency code.
     * @apiSuccess {Number} data.order      Order shown of the wallet.
     * @apiSuccess {String} data.created_at Created at.
     *
     * @apiError {Object} errors            Object containing errors to the parameters inputted.
     */
    public function withdraw(PayPalServiceProvider $paypal, Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'amount' => 'required|greater_than:0',
            'wallet_id' => 'required'
        ]);

        $manager = new WalletManager($request->user());

        $withdrawal = $manager->validateWithdrawalFromWallet($request->wallet_id, $request->amount);

        if(is_string($withdrawal)){
            return $this->buildFailedValidationResponse($request, $withdrawal);
        }

        if(! $payment =  $paypal->createPayout($request->email, 1, $request->amount, $withdrawal->getCurrency()->getCode())){
            return $this->buildFailedValidationResponse(request, 'There was an error with PayPal.');
        }

        $wallet = $manager->withdraw($withdrawal);
        $currency = $wallet->currency;

        $bank_transfer = BankTransfer::create([
            'method' => 'paypal',
            'invoice_id' => $payment->getId(),
            'amount' => $withdrawal->getAmount(),
            'amount_display' => $currency->format($withdrawal->getAmount()),
            'status' => 'complete',
            'incoming' => false
        ]);

        $manager->record($bank_transfer, $wallet, false);

        return fractal()
            ->item($wallet, new WalletTransformer())
            ->toArray();
    }

}