<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\Wallet;
use Money\Money;
use Money\Currency;
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
            return $this->sendErrorResponse($withdrawal);
        }

        if(! $paypal->createPayout($request->email, 1, $request->amount, $withdrawal->getCurrency()->getCode())){
            return $this->sendErrorResponse('There was an error with PayPal.');
        }

        $wallet = $manager->withdraw($withdrawal);

        return fractal()->item($wallet)->transformWith(new WalletTransformer())->toArray();
    }

    /**
     * Sends the error response
     *
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendErrorResponse($message = null)
    {
        return response()->json([
            'errors' => [
                'message' => ($message) ? $message: 'There was an error processing the withdrawal.'
            ]
        ]);
    }

}