<?php

namespace App\Http\Controllers\Wallet;

use App\Transformers\WalletTransformer;
use App\Wallet;
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
        $wallets = $request->user()->wallets()->get();

        return fractal()
            ->collection($wallets)
            ->transformWith(new WalletTransformer())
            ->toArray();
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
        $wallet = Wallet::findOrFail($id);

        return fractal()
            ->item($wallet)
            ->transformWith(new WalletTransformer())
            ->toArray();
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

    }

}
