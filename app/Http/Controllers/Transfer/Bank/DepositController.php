<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\BankTransfer;
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
    /**
     * @api {post} transfer/bank/deposit Deposit
     * @apiVersion 0.2.0
     * @apiName Deposit with PayPal.
     * @apiGroup BankTransfer
     *
     * @apiDescription Requests for a PayPal deposit page.
     *
     * @apiParam {String} currency          Currency code for deposit.
     * @apiParam {String} amount            Amount for deposit.
     *
     * @apiSuccess {Object} data            Data object.
     * @apiSuccess {Number} data.url        The url needed to redirect the user to paypal.
     *
     * @apiError {Object} errors            Object containing errors to the parameters inputted.
     */
    public function deposit(PayPalServiceProvider $paypal, Request $request)
    {
        $this->validate($request, [
            'currency' => 'required',
            'amount' => 'required|greater_than:0'
        ]);

        $currency = Currency::find($request->currency);

        if (! $currency || ! $currency->supported){
            return $this->buildFailedValidationResponse($request, 'Currency is not available for deposit.');
        }

        $integer = $currency->toInteger($request->amount);
        if (((int) $integer) - $integer < 0){
            return $this->buildFailedValidationResponse($request, 'Amount has too many decimals.');
        }

        $user = $request->user();

        $payment = $paypal->createPayPalCheckout($request->currency, $request->amount, $user);
        if (! $payment){
            return $this->buildFailedValidationResponse($request, 'There was an error with PayPal.');
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

        $deposit = new Money($currency->toInteger($amount), new \Money\Currency($currency->code));
        $manager = new WalletManager($user);

        $wallet = $manager->deposit($deposit);
        $wallet = fractal()
            ->item($wallet, new WalletTransformer())
            ->toArray();

        $transfer = BankTransfer::create([
            'method' => 'paypal',
            'invoice_id' => $payment->getId(),
            'amount' => $deposit->getAmount(),
            'amount_display' => $currency->format($deposit->getAmount()),
            'status' => 'complete',
            'incoming' => true
        ]);

        $manager->record($transfer, $wallet, true);

        return redirect('api/app/callback?wallet=' . urlencode(json_encode($wallet)));
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
            urlencode(($message) ? $message: 'There was an error processing the payment.'));
    }

}