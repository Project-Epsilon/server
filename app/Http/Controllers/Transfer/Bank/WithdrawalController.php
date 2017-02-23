<?php

namespace App\Http\Controllers\Transfer\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Providers\PayPalServiceProvider;


class WithdrawalController extends Controller
{

    /**
     * Creates a bank withdrawal.
     *
     * @param PayPalServiceProvider $paypal
     * @param Request $request
     * @return Payment
     */

    public function withdraw(PayPalServiceProvider $paypal, Request $request)
    {

    }

}
