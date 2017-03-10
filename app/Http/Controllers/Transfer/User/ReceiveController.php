<?php

namespace App\Http\Controllers\Transfer\User;

use App\Classes\WalletManager;
use App\Http\Responses\JsonErrorResponse;
use App\Transfer;
use App\Transformers\TransferTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Money\Money;

class ReceiveController extends Controller
{
    /**
     * Handles a receiving user transfer.
     * 
     * @param Request $request
     * @return JsonErrorResponse|array
     */
    public function receive(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $transfer = Transfer::where('token', $request->token)->first();

        if (! $transfer || $transfer->status == 'complete') {
            return $this->sendErrorResponse('Transfer does not exists.');
        }

        $user = $request->user();

        $manager = new WalletManager($user);

        $code = $transfer->senderWallet->currency_code;
        $wallet = $manager->deposit(Money::$code($transfer->amount));

        $transfer->update([
            'receiver_wallet_id' => $wallet->id,
            'status' => 'complete',
            'received_at' => Carbon::now()
        ]);

        return fractal()
            ->item($transfer, new TransferTransformer())
            ->toArray();
    }

    /**
     * Returns the error response.
     *
     * @param $message
     * @return JsonErrorResponse
     */
    protected function sendErrorResponse($message)
    {
        return new JsonErrorResponse($message? : 'There was any error processing retrieval of transfer.');
    }

}
