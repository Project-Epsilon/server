<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Transformers\CurrencyTransformer;
use Illuminate\Http\Request;

class AppController extends Controller
{

    /**
     * Returns all of the system's currencies
     *
     * @return array
     */
    /**
     * @api {get} app/currencies Get application currencies.
     * @apiVersion 0.2.0
     * @apiName AppCurrencies
     * @apiGroup Application
     *
     * @apiDescription Retrieves a list of app supported currencies.
     *
     * @apiSuccess {Object[]} data              Array of currencies in ascending order.
     * @apiSuccess {String} data.code         Currency code.
     * @apiSuccess {String} data.name         Currency name.
     * @apiSuccess {Number} data.minor_unit   The minor unit of the currency.
     * @apiSuccess {Number} data.supported    Indicates if currency is supported.
     *
     */
    public function currencies()
    {
        $currencies = Currency::orderBy('code', 'asc')
            ->get();

        return fractal()
            ->collection($currencies)
            ->transformWith(new CurrencyTransformer())
            ->toArray();
    }

    /**
     * Redirects callbacks to a pretty page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function callback(Request $request)
    {
        $success = (isset($request->success))? filter_var($request->success, FILTER_VALIDATE_BOOLEAN) : true;

        return view('callback', compact('success'));
    }

}
