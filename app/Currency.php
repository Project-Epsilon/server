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


    public function toInteger($float)
    {
        return pow(10, $this->minor_unit) * $float;
    }

    public function toDecimal($integer)
    {
        $money = new Money($integer, new \Money\Currency($this->code));
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $formatter->format($money);
    }

}
