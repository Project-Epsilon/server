<?php

namespace App\Http\Controllers\Transfer\Bank;

use App\User;
use App\BankTransfer;
use App\Transformers\BankTransferTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    /**
     * Display a listing of transfers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transfers = $request->user()->bankTransfers()->get();

        return fractal()
            ->collection($transfers)
            ->transformWith(new BankTransferTransformer())
            ->toArray();
    }

    /**
     * Displays the specified transfer.
     *
     * @param $id
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function show($id, Request $request)
    {
        $transfer = BankTransfer::find($id);

        if (! $transfer || ! $this->canView($transfer, $request->user())) {
            return $this->buildFailedValidationResponse($request, 'Cannot find transfer.');
        }

        return fractal()
            ->item($transfer, new BankTransferTransformer())
            ->toArray();
    }

    /**
     * Determines if the user can view the bank transfer.
     *
     * @param BankTransfer $transfer
     * @param User $user
     * @return bool
     */
    protected function canView(BankTransfer $transfer, User $user)
    {
        return !!($user->bankTransfers()->find($transfer->id));
    }

}
