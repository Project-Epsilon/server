<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            'AUD' => ['currency' => 'Australian Dollar', 'minorUnit' => '2', 'symbol' => '$'],
            'BRL' => ['currency' => 'Brazilian Real', 'minorUnit' => '2', 'symbol' => 'R$'],
            'CAD' => ['currency' => 'Canadian Dollar', 'minorUnit' => '2', 'symbol' => '$'],
            'CZK' => ['currency' => 'Czech Koruna', 'minorUnit' => '2', 'symbol' => 'Kč'],
            'DKK' => ['currency' => 'Danish Krone', 'minorUnit' => '2', 'symbol' => ''],
            'EUR' => ['currency' => 'Euro', 'minorUnit' => '2', 'symbol' => '€'],
            'HKD' => ['currency' => 'Hong Kong Dollar', 'minorUnit' => '2', 'symbol' => '$'],
            'HUF' => ['currency' => 'Forint', 'minorUnit' => '2', 'symbol' => 'Ft'],
            'ILS' => ['currency' => 'New Israeli Sheqel', 'minorUnit' => '2', 'symbol' => '₪'],
            'JPY' => ['currency' => 'Yen', 'minorUnit' => '0', 'symbol' => '¥'],
            'MYR' => ['currency' => 'Malaysian Ringgit', 'minorUnit' => '2', 'symbol' => 'RM'],
            'MXN' => ['currency' => 'Mexican Peso', 'minorUnit' => '2', 'symbol' => '$'],
            'NOK' => ['currency' => 'Norwegian Krone', 'minorUnit' => '2', 'symbol' => 'kr'],
            'NZD' => ['currency' => 'New Zealand Dollar', 'minorUnit' => '2', 'symbol' => '$'],
            'PHP' => ['currency' => 'Philippine Peso', 'minorUnit' => '2', 'symbol' => '₱'],
            'PLN' => ['currency' => 'Zloty', 'minorUnit' => '2', 'symbol' => 'zł'],
            'GBP' => ['currency' => 'Pound Sterling', 'minorUnit' => '2', 'symbol' => '£'],
            'RUB' => ['currency' => 'Russian Ruble', 'minorUnit' => '2', 'symbol' => 'ƒ'],
            'SGD' => ['currency' => 'Singapore Dollar', 'minorUnit' => '2', 'symbol' => '$'],
            'SEK' => ['currency' => 'Swedish Krona', 'minorUnit' => '2', 'symbol' => 'kr'],
            'CHF' => ['currency' => 'Swiss Franc', 'minorUnit' => '2', 'symbol' => 'CHF'],
            'TWD' => ['currency' => 'New Taiwan Dollar', 'minorUnit' => '2', 'symbol' => 'NT$'],
            'THB' => ['currency' => 'Baht', 'minorUnit' => '2', 'symbol' => '	฿'],
            'USD' => ['currency' => 'US Dollar', 'minorUnit' => '2', 'symbol' => '$']
        ];
        
        $data = [];
        foreach ($currencies as $key => $value){
            array_push($data, [
                'code' => $key,
                'name' => $value['currency'],
                'symbol' => $value['symbol'],
                'minor_unit' => $value['minorUnit'],
                'supported' => true
            ]);
        }

        \App\Currency::unguard();

        \App\Currency::insert($data);

        \App\Currency::reguard();

    }
}
