<?php

namespace App\Http\Controllers\Transfer\User;

use App\Classes\WalletManager;
use App\Transformers\TransferTransformer;
use App\Wallet;
use App\Transfer;
use Money\Money;
use Money\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\WalletTransformer;
use App\Http\Responses\JsonErrorResponse;

class SendController extends Controller
{

    /**
     * Creates a sending transfer
     *
     * @param Request $request
     * @return JsonErrorResponse|array
     */
    public function send(Request $request)
    {
        $this->validateRequest($request);

        $user = $request->user();

        $wallet = Wallet::find($request->wallet_id);
        if (! $wallet || $wallet->user_id != $user->id) {
            return $this->sendErrorResponse('Wallet does not exists.');
        }

        $integer = $wallet->currency->toInteger($request->amount);
        if (((int) $integer) - $integer < 0){
            return $this->sendErrorResponse('Amount has too many decimals.');
        }

        $code = $wallet->currency_code;
        $withdrawal = Money::$code($wallet->currency->toInteger($request->amount));

        if(! $wallet->toMoney()->greaterThanOrEqual($withdrawal)){
            return $this->sendErrorResponse('Not enough funds.');
        }

        $transfer = $this->createTransfer($request->all());

        $manager = new WalletManager($user);
        $wallet = $manager->withdraw($withdrawal);

        return fractal()
            ->item($wallet, new WalletTransformer())
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
            'receiver.phone_number' => 'required_without:receiver.email',
            'receiver.email' => 'required_without:receiver.phone_number|email',
            'amount' => 'numeric',
            'wallet_id' => 'required|numeric',
            'message' => 'max:255'
        ]);
    }

    /**
     * Creates the transfer object
     *
     * @param array $data
     * @return mixed
     */
    protected function createTransfer(array $data)
    {
        $transfer = Transfer::create([
            'receiver_phone_number' => isset($data['receiver']['phone_number'])? $data['receiver']['phone_number'] : null,
            'receiver_email' => isset($data['receiver']['email'])? $data['receiver']['email'] : null,
            'message' => trim($data['message']),
            'status' => 'pending',
            'amount' => $data['amount'],
            'sender_wallet_id' => $data['wallet_id'],
            'token' => str_random(128)
        ]);

        return $transfer;
    }

    /**
     * Returns error response.
     *
     * @param $message
     * @return JsonErrorResponse
     */
    public function sendErrorResponse($message)
    {
        return new JsonErrorResponse($message? : 'A transfer could not have been created.');
    }

}
