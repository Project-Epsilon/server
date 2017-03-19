<?php

namespace Tests\Unit;

use App\Currency;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurrencyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test to decimal format;
     *
     * @return void
     */
    public function testToDecimalFormat()
    {
        $this->seed();

        $currency = Currency::find('CAD');

        $this->assertEquals(12.13, $currency->toDecimal(1213));
    }

    /**
     * Test to integer format.
     *
     * @return void
     */
    public function testToInteger()
    {
        $this->seed();

        $currency = Currency::find('CAD');

        $this->assertEquals(1213, $currency->toInteger(12.13));
    }
}
