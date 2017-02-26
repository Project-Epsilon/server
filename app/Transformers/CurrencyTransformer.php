<?php

namespace App\Transformers;

use App\Currency;
use League\Fractal\TransformerAbstract;

class CurrencyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Currency $currency)
    {
        return [
            'code' => $currency->code,
            'name' => $currency->name,
            'minor_unit' => (int) $currency->minor_unit,
            'supported' => (boolean) $currency->supported
        ];
    }
}
