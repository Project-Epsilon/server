<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\User;
use App\Currency;
use Illuminate\Http\Request;
use App\Classes\WalletManager;
use App\Http\Controllers\Controller;
use App\Transformers\WalletTransformer;
use App\Providers\PayPalServiceProvider;

class DepositController extends Controller
{

    /**
     * Creates a bank deposit
     *
     * @param PayPalServiceProvider $paypal
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(PayPalServiceProvider $paypal, Request $request)
    {
        $this->validate($request, [
            'currency' => 'required',
            'amount' => 'required|greater_than:0'
        ]);

        $currency = Currency::find($request->currency);

        if (! $currency || ! $currency->available){
            return $this->sendErrorResponse('Currency is not available for deposit.');
        }

        $user = $request->user();

        if (! $url = $paypal->createPayPalCheckout($request->currency, $request->amount, $user)->links[1]->href){
            $this->sendErrorResponse('There was an error with PayPal.');
        }

        return response()->json(['data' => ['url' => $url]]);
    }

    /**
     * Handles the callback request from the server.
     *
     * @param PayPalServiceProvider $paypal
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function paypalCallback(PayPalServiceProvider $paypal, Request $request)
    {
        if ($request->success != 'true') {
            return $this->sendErrorResponse('Payment was cancelled.');
        }

        $user = User::find(decrypt($request->user));

        if (! $user){
            return $this->sendErrorResponse();
        }

        if(! $payment = $paypal->executePayPalPayment($request->payerID, $request->paymentId)){
            $this->sendErrorResponse('There was an error with PayPal.');
        }

        return $this->processDeposit($payment, $user);
    }

    /**
     * Process deposit
     *
     * @param $payment
     * @param $user
     * @return array
     */
    protected function processDeposit($payment, $user)
    {
        $amount = $payment->transactions[0]->amount->total;
        $currency = Currency::find($payment->transactions[0]->amount->currency);

        $money = $currency->toInteger($amount);
        $manager = new WalletManager($user);

        return fractal()
            ->item($manager->deposit($money))
            ->transformWith(new WalletTransformer())
            ->toArray();
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
                'message' => ($message) ? $message: 'There was an error processing the payment.'
            ]
        ]);
    }
}