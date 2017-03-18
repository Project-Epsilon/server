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
            'AUD' => ['currency' => 'Australian Dollar', 'minorUnit' => '2'],
            'BRL' => ['currency' => 'Brazilian Real', 'minorUnit' => '2'],
            'CAD' => ['currency' => 'Canadian Dollar', 'minorUnit' => '2'],
            'CZK' => ['currency' => 'Czech Koruna', 'minorUnit' => '2'],
            'DKK' => ['currency' => 'Danish Krone', 'minorUnit' => '2'],
            'EUR' => ['currency' => 'Euro', 'minorUnit' => '2'],
            'HKD' => ['currency' => 'Hong Kong Dollar', 'minorUnit' => '2'],
            'HUF' => ['currency' => 'Forint', 'minorUnit' => '2'],
            'ILS' => ['currency' => 'New Israeli Sheqel', 'minorUnit' => '2'],
            'JPY' => ['currency' => 'Yen', 'minorUnit' => '0'],
            'MYR' => ['currency' => 'Malaysian Ringgit', 'minorUnit' => '2'],
            'MXN' => ['currency' => 'Mexican Peso', 'minorUnit' => '2'],
            'NOK' => ['currency' => 'Norwegian Krone', 'minorUnit' => '2'],
            'NZD' => ['currency' => 'New Zealand Dollar', 'minorUnit' => '2'],
            'PHP' => ['currency' => 'Philippine Peso', 'minorUnit' => '2'],
            'PLN' => ['currency' => 'Zloty', 'minorUnit' => '2'],
            'GBP' => ['currency' => 'Pound Sterling', 'minorUnit' => '2'],
            'RUB' => ['currency' => 'Russian Ruble', 'minorUnit' => '2'],
            'SGD' => ['currency' => 'Singapore Dollar', 'minorUnit' => '2'],
            'SEK' => ['currency' => 'Swedish Krona', 'minorUnit' => '2'],
            'CHF' => ['currency' => 'Swiss Franc', 'minorUnit' => '2'],
            'TWD' => ['currency' => 'New Taiwan Dollar', 'minorUnit' => '2'],
            'THB' => ['currency' => 'Baht', 'minorUnit' => '2'],
            'USD' => ['currency' => 'US Dollar', 'minorUnit' => '2']
        ];
        
        $data = [];
        foreach ($currencies as $key => $value){
            array_push($data, [
                'code' => $key,
                'name' => $value['currency'],
                'minor_unit' => $value['minorUnit'],
                'supported' => true
            ]);
        }

        \App\Currency::unguard();

        \App\Currency::insert($data);

        \App\Currency::reguard();

    }
}
