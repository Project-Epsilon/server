<?php

namespace App\Http\Controllers\Transfer\User;

use App\Transfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responses\JsonErrorResponse;
use App\Classes\WalletManager;
use App\Providers\NexmoServiceProvider;
use App\Transformers\TransferTransformer;

class SendController extends Controller
{

    /**
     * Creates a sending transfer
     *
     * @param Request $request
     * @param NexmoServiceProvider $nexmo
     * @return JsonErrorResponse|array
     */
    public function send(Request $request, NexmoServiceProvider $nexmo)
    {
        $this->validateRequest($request);

        $manager = new WalletManager($request->user());

        $withdrawal = $manager->validateWithdrawalFromWallet($request->wallet_id, $request->amount);

        if(is_string($withdrawal)){
            return $this->sendErrorResponse($withdrawal);
        }

        $transfer = $this->createTransfer($request->all());

        $manager->withdraw($withdrawal);

        $this->sendToken($transfer, $nexmo);

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
     * Send token via mail or sms
     *
     * @param Transfer $transfer
     * @param NexmoServiceProvider $nexmo
     */
    protected function sendToken(Transfer $transfer, NexmoServiceProvider $nexmo)
    {
        //TODO
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
