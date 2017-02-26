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
}
