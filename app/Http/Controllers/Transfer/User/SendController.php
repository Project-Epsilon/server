<?php

namespace App\Http\Controllers\Transfer\User;

use App\Currency;
use App\Jobs\SendTransfer;
use App\User;
use Money\Money;
use App\Transfer;
use Illuminate\Http\Request;
use App\Classes\WalletManager;
use App\Http\Controllers\Controller;
use App\Providers\NexmoServiceProvider;
use App\Transformers\TransferTransformer;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class SendController extends Controller
{

    /**
     * Creates a sending transfer
     *
     * @param Request $request
     * @param NexmoServiceProvider $nexmo
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function send(Request $request, NexmoServiceProvider $nexmo)
    {
        $this->validateRequest($request);

        $user = $request->user();
        $manager = new WalletManager($user);

        $withdrawal = $manager->validateWithdrawalFromWallet($request->wallet_id, $request->amount);

        if(is_string($withdrawal)){
            return $this->buildFailedValidationResponse($request, $withdrawal);
        }

        $transfer = $this->createTransfer($request, $user, $withdrawal);

        $manager->withdraw($withdrawal);

        $this->sendToken($transfer);

        return fractal()
            ->item($transfer, new TransferTransformer())
            ->toArray();
    }

    /**
     * Validates the request parameters.
     *
     * @param Request $request
     */
    protected function validateRequest(Request $request)
    {
        $this->validate($request, [
            'receiver' => 'required',
            'receiver.name' => 'required',
            'receiver.phone_number' => 'required_without:receiver.email',
            'receiver.email' => 'required_without:receiver.phone_number|email',
            'amount' => 'required|numeric',
            'wallet_id' => 'required|numeric',
            'message' => 'max:255'
        ]);
    }

    /**
     * Creates the transfer object
     *
     * @param Request $request
     * @param User $user
     * @param Money $withdrawal
     * @return mixed
     */
    protected function createTransfer(Request $request, User $user, Money $withdrawal)
    {
        $currency = Currency::find($withdrawal->getCurrency());

        $transfer = Transfer::create([
            'receiver' => $request->input('receiver.name'),
            'sender' => $user->name,
            'receiver_phone_number' => $request->input('receiver.phone_number'),
            'receiver_email' => $request->input('receiver.email'),
            'message' => trim($request->message),
            'status' => 'pending',
            'amount_display' => $currency->format($withdrawal->getAmount()),
            'amount' => $withdrawal->getAmount(),
            'sender_wallet_id' => $request->wallet_id,
            'token' => str_random(32)
        ]);

        return $transfer;
    }

    /**
     * Send token via mail or sms
     *
     * @param Transfer $transfer
     */
    protected function sendToken(Transfer $transfer)
    {
        $this->dispatch(new SendTransfer($transfer));
    }

}
