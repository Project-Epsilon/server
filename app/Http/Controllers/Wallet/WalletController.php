<?php

namespace App\Http\Controllers\Wallet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{

    /**
     * Return a listing of all wallets.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // return wallets with transaction log.
    }

    /**
     * Display the specified wallet with transaction log.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // return wallet with transaction log.
    }

    /**
     * Update the specified wallet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
