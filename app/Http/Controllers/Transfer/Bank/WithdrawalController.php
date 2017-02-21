<?php

namespace App\Http\Controllers\Transfer\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Providers\PayPalServiceProvider;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


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
