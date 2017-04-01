<?php

namespace App\Http\Controllers\Transfer\User;

use App\User;
use App\Transfer;
use App\Transformers\TransferTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    /**
     * Returns a listing of user transfers sent and received of the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $transfers = [];

        array_push($transfers,
            $user->transfersOut()->get());
        array_push($transfers,
            $user->transfersIn()->get());

        $transfers = array_flatten($transfers);

        $transfers = array_sort($transfers, function($transfer) {
            return $transfer->id;
        });

        return fractal($transfers)
            ->transformWith(new TransferTransformer())
            ->toArray();
    }

    /**
     * Returns a specific transfer of the sender or receiver
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id, Request $request)
    {
        $transfer = Transfer::find($id);

        if (! $transfer || ! $this->canViewTransfer($transfer, $request->user())) {
            return $this->buildFailedValidationResponse($request, 'Transfer does not exist');
        }

        return fractal($transfer)
            ->transformWith(new TransferTransformer())
            ->toArray();
    }

    /**
     * Gets transfer by token.
     *
     * @param $token
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function getByToken(Request $request)
    {
        $transfer = Transfer::where('token', $request->token)->first();

        if (! $transfer) {
            return $this->buildFailedValidationResponse($request, 'Transfer does not exist'); //TODO: May have a security issue.
        }

        return fractal($transfer)
            ->transformWith(new TransferTransformer())
            ->toArray();
    }


    /**
     * Permits the user to edit the transfer.
     *
     * @param Transfer $transfer
     * @param User $user
     * @return bool
     */
    public function canEditTransfer(Transfer $transfer, User $user)
    {
        return $user->transfersOut()->find($transfer->id) ? true : false;
    }

    /**
     * Permits a user to view a transfer.
     *
     * @param Transfer $transfer
     * @param User $user
     * @return bool
     */
    public function canViewTransfer(Transfer $transfer, User $user)
    {
        if (
            $user->transfersOut()->find($transfer->id) ||
            $user->transfersIn()->find($transfer->id)
        ) {
            return true;
        }

        return false;
    }

}
