<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\User;
use App\Currency;
use Illuminate\Http\Request;
use App\Classes\WalletManager;
use App\Http\Controllers\Controller;
use App\Transformers\WalletTransformer;
use App\Providers\PayPalServiceProvider;
use Money\Money;

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

        if (! $currency || ! $currency->supported){
            return $this->sendErrorResponse('Currency is not available for deposit.');
        }

        $integer = $currency->toInteger($request->amount);
        if (((int) $integer) - $integer < 0){
            return $this->sendErrorResponse('Amount has too many decimals.');
        }

        $user = $request->user();

        $payment = $paypal->createPayPalCheckout($request->currency, $request->amount, $user);
        if (! $payment){
            return $this->sendErrorResponse('There was an error with PayPal.');
        }

        return response()->json(['data' => ['url' => $payment->links[1]->href]]);
    }

    /**
     * Handles the callback request from the server.
     *
     * @param PayPalServiceProvider $paypal
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paypalCallback(PayPalServiceProvider $paypal, Request $request)
    {
        if ($request->success != 'true') {
            return $this->sendCallbackError('Payment was cancelled.');
        }

        $user = User::find(decrypt($request->user));

        if (! $user){
            return $this->sendCallbackError();
        }

        if(! $payment = $paypal->executePayPalPayment($request->PayerID, $request->paymentId)){
            return $this->sendCallbackError('There was an error with PayPal.');
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

        $money = new Money($currency->toInteger($amount), new \Money\Currency($currency->code));
        $manager = new WalletManager($user);

        $wallet = fractal()->item($manager->deposit($money))->transformWith(new WalletTransformer())->toArray();

        return redirect('api/app/callback?wallet=' . urlencode(json_encode($wallet)));
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
                'message' => ($message) ? $message: $this->errorMessage()
            ]
        ]);
    }

    /**
     * Returns a redirect page.
     *
     * @param null $message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function sendCallbackError($message = null)
    {
        return redirect('api/app/callback?success=false&message=' .
            urlencode(($message) ? $message: $this->errorMessage()));
    }

    /**
     * Generic error message.
     *
     * @return string
     */
    protected function errorMessage()
    {
        return 'There was an error processing the payment.';
    }
}