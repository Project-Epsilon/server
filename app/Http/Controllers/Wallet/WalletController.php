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
     * @return array
     */
    /**
     * @api {get} wallet Get wallets.
     * @apiVersion 0.2.0
     * @apiName GetAllWallets
     * @apiGroup Wallets
     *
     * @apiDescription Gets all wallets of the authenticated user.
     *
     * @apiSuccess {Object[]} data          The wallet information.
     * @apiSuccess {Number} data.id       Wallet id.
     * @apiSuccess {Number} data.user_id  Owner of the user.
     * @apiSuccess {String} data.balance  Wallet balance.
     * @apiSuccess {Number} data.visible  Visibility of the wallet.
     * @apiSuccess {String} data.currency_code Currency code.
     * @apiSuccess {Number} data.order    Order shown of the wallet.
     * @apiSuccess {String} data.created_at Created at.
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
    /**
     * @api {get} wallet/:id Get a wallet.
     * @apiVersion 0.2.0
     * @apiName GetWallets
     * @apiGroup Wallets
     *
     * @apiDescription Gets a wallet of the authenticated user.
     *
     * @apiSuccess {Object} data          The wallet information.
     * @apiSuccess {Number} data.id       User id.
     * @apiSuccess {Number} data.user_id  Owner of the user.
     * @apiSuccess {String} data.balance  Wallet balance.
     * @apiSuccess {Number} data.visible  Visibility of the wallet.
     * @apiSuccess {String} data.currency_code Currency code.
     * @apiSuccess {Number} data.order    Order shown of the wallet.
     * @apiSuccess {String} data.created_at Created at.
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
