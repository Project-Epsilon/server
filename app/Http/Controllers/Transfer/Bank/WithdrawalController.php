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
    public function withdraw(PayPalServiceProvider $paypal, Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'email' => 'required|email',
            'amount' => 'required|greater_than:0',
            'wallet_id' => 'required'
        ]);

        $wallet = Wallet::find($request->wallet_id);
        if (! $wallet || $wallet->user_id != $user->id){
            return $this->sendErrorResponse('Wallet does not exists.');
        }

        $integer = $wallet->currency->toInteger($request->amount);
        if (((int) $integer) - $integer < 0){
            return $this->sendErrorResponse('Amount has too many decimals.');
        }

        $withdrawal = new Money($wallet->currency->toInteger($request->amount), new Currency($wallet->currency_code));

        if(! $wallet->toMoney()->greaterThanOrEqual($withdrawal)){
            return $this->sendErrorResponse('Not enough funds.');
        }

        if(! $paypal->createPayout($request->email, 1, $request->amount, $wallet->currency_code)){
            return $this->sendErrorResponse('There was an error with PayPal.');
        }

        $manager = new WalletManager($user);
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