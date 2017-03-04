<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Transformers\CurrencyTransformer;
use Illuminate\Http\Request;

class AppController extends Controller
{

    /**
     * Returns all of the system's currencies
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
