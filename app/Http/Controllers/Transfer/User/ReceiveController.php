<?php

namespace App\Http\Controllers\Transfer\User;

use App\Classes\WalletManager;
use App\Http\Responses\JsonErrorResponse;
use App\Transfer;
use App\Transformers\TransferTransformer;
use App\User;
use App\Wallet;
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
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function receive(Request $request)
    {
        $transfer = $this->validateToken($request);

        if (! $transfer || $transfer->status == 'complete' || $transfer->status == 'cancelled' ) {
            return $this->buildFailedValidationResponse($request, 'Transfer does not exists.');
        }

        $user = $request->user();
        $wallet = $this->makeDeposit($user, $transfer);

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
     * Processes a cancel request.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function cancel(Request $request)
    {
        $transfer = $this->validateToken($request);

        if (! $transfer || $transfer->status == 'complete' || $transfer->status == 'cancelled' ) {
            return $this->buildFailedValidationResponse($request, 'Transfer does not exists.');
        }

        $sender = $transfer->senderWallet->user;
        $this->makeDeposit($sender, $transfer);

        $transfer->update(['status' => 'cancelled']);

        return fractal()
            ->item($transfer, new TransferTransformer())
            ->toArray();
    }

    /**
     * Validates transfer and returns it.
     * @param Request $request
     * @return Transfer
     */
    protected function validateToken(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        return Transfer::where('token', $request->token)->first();
    }

    /**
     * Process deposit to the desired user.
     *
     * @param User $user
     * @param Transfer $transfer
     * @return Wallet
     */
    protected function makeDeposit(User $user, Transfer $transfer)
    {
        $manager = new WalletManager($user);

        $code = $transfer->senderWallet->currency_code;
        $wallet =  $manager->deposit(Money::$code($transfer->amount));

        $manager->record($transfer, $wallet, true);

        return $wallet;
    }

}
