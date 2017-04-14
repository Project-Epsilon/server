<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class Currency extends Model
{
    /**
     * Disable timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Change primary key to code
     * @var string
     *
     */
    protected $primaryKey = 'code';

    /**
     * Disable incrementing
     * @var bool
     */
    public $incrementing = false;

    /**
     * Returns the currency converted to integer.
     *
     * @param $float
     * @return number
     */
    public function toInteger($float)
    {
        return pow(10, $this->minor_unit) * $float;
    }

    /**
     * Returns the formatted currency in the correct decimal place.
     *
     * @param $integer
     * @return string
     */
    public function toDecimal($integer)
    {
        $money = new Money($integer, new \Money\Currency($this->code));
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $formatter->format($money);
    }

    /**
     * Returns the formatted currency with symbol.
     *
     * @param $integer
     * @return string
     */
    public function format($integer)
    {
        return $this->symbol . $this->toDecimal($integer) . ' ' . $this->code;
    }

}
