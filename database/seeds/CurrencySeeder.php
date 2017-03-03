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
            'AFN' => [
                'currency' => 'Afghani',
                'minorUnit' => 2,
            ],
            'EUR' => [
                'currency' => 'Euro',
                'minorUnit' => 2,
            ],
            'ALL' => [
                'currency' => 'Lek',
                'minorUnit' => 2,
            ],
            'DZD' => [
                'currency' => 'Algerian Dinar',
                'minorUnit' => 2,
            ],
            'USD' => [
                'currency' => 'US Dollar',
                'minorUnit' => 2,
            ],
            'AOA' => [
                'currency' => 'Kwanza',
                'minorUnit' => 2,
            ],
            'XCD' => [
                'currency' => 'East Caribbean Dollar',
                'minorUnit' => 2,
            ],
            'ARS' => [
                'currency' => 'Argentine Peso',
                'minorUnit' => 2,
            ],
            'AMD' => [
                'currency' => 'Armenian Dram',
                'minorUnit' => 2,
            ],
            'AWG' => [
                'currency' => 'Aruban Florin',
                'minorUnit' => 2,
            ],
            'AUD' => [
                'currency' => 'Australian Dollar',
                'minorUnit' => 2,
            ],
            'AZN' => [
                'currency' => 'Azerbaijanian Manat',
                'minorUnit' => 2,
            ],
            'BSD' => [
                'currency' => 'Bahamian Dollar',
                'minorUnit' => 2,
            ],
            'BHD' => [
                'currency' => 'Bahraini Dinar',
                'minorUnit' => 3,
            ],
            'BDT' => [
                'currency' => 'Taka',
                'minorUnit' => 2,
            ],
            'BBD' => [
                'currency' => 'Barbados Dollar',
                'minorUnit' => 2,
            ],
            'BYN' => [
                'currency' => 'Belarusian Ruble',
                'minorUnit' => 2,
            ],
            'BYR' => [
                'currency' => 'Belarusian Ruble',
                'minorUnit' => 0,
            ],
            'BZD' => [
                'currency' => 'Belize Dollar',
                'minorUnit' => 2,
            ],
            'XOF' => [
                'currency' => 'CFA Franc BCEAO',
                'minorUnit' => 0,
            ],
            'BMD' => [
                'currency' => 'Bermudian Dollar',
                'minorUnit' => 2,
            ],
            'INR' => [
                'currency' => 'Indian Rupee',
                'minorUnit' => 2,
            ],
            'BTN' => [
                'currency' => 'Ngultrum',
                'minorUnit' => 2,
            ],
            'BOB' => [
                'currency' => 'Boliviano',
                'minorUnit' => 2,
            ],
            'BOV' => [
                'currency' => 'Mvdol',
                'minorUnit' => 2,
            ],
            'BAM' => [
                'currency' => 'Convertible Mark',
                'minorUnit' => 2,
            ],
            'BWP' => [
                'currency' => 'Pula',
                'minorUnit' => 2,
            ],
            'NOK' => [
                'currency' => 'Norwegian Krone',
                'minorUnit' => 2,
            ],
            'BRL' => [
                'currency' => 'Brazilian Real',
                'minorUnit' => 2,
            ],
            'BND' => [
                'currency' => 'Brunei Dollar',
                'minorUnit' => 2,
            ],
            'BGN' => [
                'currency' => 'Bulgarian Lev',
                'minorUnit' => 2,
            ],
            'BIF' => [
                'currency' => 'Burundi Franc',
                'minorUnit' => 0,
            ],
            'CVE' => [
                'currency' => 'Cabo Verde Escudo',
                'minorUnit' => 2,
            ],
            'KHR' => [
                'currency' => 'Riel',
                'minorUnit' => 2,
            ],
            'XAF' => [
                'currency' => 'CFA Franc BEAC',
                'minorUnit' => 0,
            ],
            'CAD' => [
                'currency' => 'Canadian Dollar',
                'minorUnit' => 2,
            ],
            'KYD' => [
                'currency' => 'Cayman Islands Dollar',
                'minorUnit' => 2,
            ],
            'CLP' => [
                'currency' => 'Chilean Peso',
                'minorUnit' => 0,
            ],
            'CLF' => [
                'currency' => 'Unidad de Fomento',
                'minorUnit' => 4,
            ],
            'CNY' => [
                'currency' => 'Yuan Renminbi',
                'minorUnit' => 2,
            ],
            'COP' => [
                'currency' => 'Colombian Peso',
                'minorUnit' => 2,
            ],
            'COU' => [
                'currency' => 'Unidad de Valor Real',
                'minorUnit' => 2,
            ],
            'KMF' => [
                'currency' => 'Comoro Franc',
                'minorUnit' => 0,
            ],
            'CDF' => [
                'currency' => 'Congolese Franc',
                'minorUnit' => 2,
            ],
            'NZD' => [
                'currency' => 'New Zealand Dollar',
                'minorUnit' => 2,
            ],
            'CRC' => [
                'currency' => 'Costa Rican Colon',
                'minorUnit' => 2,
            ],
            'HRK' => [
                'currency' => 'Kuna',
                'minorUnit' => 2,
            ],
            'CUP' => [
                'currency' => 'Cuban Peso',
                'minorUnit' => 2,
            ],
            'CUC' => [
                'currency' => 'Peso Convertible',
                'minorUnit' => 2,
            ],
            'ANG' => [
                'currency' => 'Netherlands Antillean Guilder',
                'minorUnit' => 2,
            ],
            'CZK' => [
                'currency' => 'Czech Koruna',
                'minorUnit' => 2,
            ],
            'DKK' => [
                'currency' => 'Danish Krone',
                'minorUnit' => 2,
            ],
            'DJF' => [
                'currency' => 'Djibouti Franc',
                'minorUnit' => 0,
            ],
            'DOP' => [
                'currency' => 'Dominican Peso',
                'minorUnit' => 2,
            ],
            'EGP' => [
                'currency' => 'Egyptian Pound',
                'minorUnit' => 2,
            ],
            'SVC' => [
                'currency' => 'El Salvador Colon',
                'minorUnit' => 2,
            ],
            'ERN' => [
                'currency' => 'Nakfa',
                'minorUnit' => 2,
            ],
            'ETB' => [
                'currency' => 'Ethiopian Birr',
                'minorUnit' => 2,
            ],
            'FKP' => [
                'currency' => 'Falkland Islands Pound',
                'minorUnit' => 2,
            ],
            'FJD' => [
                'currency' => 'Fiji Dollar',
                'minorUnit' => 2,
            ],
            'XPF' => [
                'currency' => 'CFP Franc',
                'minorUnit' => 0,
            ],
            'GMD' => [
                'currency' => 'Dalasi',
                'minorUnit' => 2,
            ],
            'GEL' => [
                'currency' => 'Lari',
                'minorUnit' => 2,
            ],
            'GHS' => [
                'currency' => 'Ghana Cedi',
                'minorUnit' => 2,
            ],
            'GIP' => [
                'currency' => 'Gibraltar Pound',
                'minorUnit' => 2,
            ],
            'GTQ' => [
                'currency' => 'Quetzal',
                'minorUnit' => 2,
            ],
            'GBP' => [
                'currency' => 'Pound Sterling',
                'minorUnit' => 2,
            ],
            'GNF' => [
                'currency' => 'Guinea Franc',
                'minorUnit' => 0,
            ],
            'GYD' => [
                'currency' => 'Guyana Dollar',
                'minorUnit' => 2,
            ],
            'HTG' => [
                'currency' => 'Gourde',
                'minorUnit' => 2,
            ],
            'HNL' => [
                'currency' => 'Lempira',
                'minorUnit' => 2,
            ],
            'HKD' => [
                'currency' => 'Hong Kong Dollar',
                'minorUnit' => 2,
            ],
            'HUF' => [
                'currency' => 'Forint',
                'minorUnit' => 2,
            ],
            'ISK' => [
                'currency' => 'Iceland Krona',
                'minorUnit' => 0,
            ],
            'IDR' => [
                'currency' => 'Rupiah',
                'minorUnit' => 2,
            ],
            'XDR' => [
                'currency' => 'SDR (Special Drawing Right)',
                'minorUnit' => 0,
            ],
            'IRR' => [
                'currency' => 'Iranian Rial',
                'minorUnit' => 2,
            ],
            'IQD' => [
                'currency' => 'Iraqi Dinar',
                'minorUnit' => 3,
            ],
            'ILS' => [
                'currency' => 'New Israeli Sheqel',
                'minorUnit' => 2,
            ],
            'JMD' => [
                'currency' => 'Jamaican Dollar',
                'minorUnit' => 2,
            ],
            'JPY' => [
                'currency' => 'Yen',
                'minorUnit' => 0,
            ],
            'JOD' => [
                'currency' => 'Jordanian Dinar',
                'minorUnit' => 3,
            ],
            'KZT' => [
                'currency' => 'Tenge',
                'minorUnit' => 2,
            ],
            'KES' => [
                'currency' => 'Kenyan Shilling',
                'minorUnit' => 2,
            ],
            'KPW' => [
                'currency' => 'North Korean Won',
                'minorUnit' => 2,
            ],
            'KRW' => [
                'currency' => 'Won',
                'minorUnit' => 0,
            ],
            'KWD' => [
                'currency' => 'Kuwaiti Dinar',
                'minorUnit' => 3,
            ],
            'KGS' => [
                'currency' => 'Som',
                'minorUnit' => 2,
            ],
            'LAK' => [
                'currency' => 'Kip',
                'minorUnit' => 2,
            ],
            'LBP' => [
                'currency' => 'Lebanese Pound',
                'minorUnit' => 2,
            ],
            'LSL' => [
                'currency' => 'Loti',
                'minorUnit' => 2,
            ],
            'ZAR' => [
                'currency' => 'Rand',
                'minorUnit' => 2,
            ],
            'LRD' => [
                'currency' => 'Liberian Dollar',
                'minorUnit' => 2,
            ],
            'LYD' => [
                'currency' => 'Libyan Dinar',
                'minorUnit' => 3,
            ],
            'CHF' => [
                'currency' => 'Swiss Franc',
                'minorUnit' => 2,
            ],
            'MOP' => [
                'currency' => 'Pataca',
                'minorUnit' => 2,
            ],
            'MKD' => [
                'currency' => 'Denar',
                'minorUnit' => 2,
            ],
            'MGA' => [
                'currency' => 'Malagasy Ariary',
                'minorUnit' => 2,
            ],
            'MWK' => [
                'currency' => 'Malawi Kwacha',
                'minorUnit' => 2,
            ],
            'MYR' => [
                'currency' => 'Malaysian Ringgit',
                'minorUnit' => 2,
            ],
            'MVR' => [
                'currency' => 'Rufiyaa',
                'minorUnit' => 2,
            ],
            'MRO' => [
                'currency' => 'Ouguiya',
                'minorUnit' => 2,
            ],
            'MUR' => [
                'currency' => 'Mauritius Rupee',
                'minorUnit' => 2,
            ],
            'XUA' => [
                'currency' => 'ADB Unit of Account',
                'minorUnit' => 0,
            ],
            'MXN' => [
                'currency' => 'Mexican Peso',
                'minorUnit' => 2,
            ],
            'MXV' => [
                'currency' => 'Mexican Unidad de Inversion (UDI)',
                'minorUnit' => 2,
            ],
            'MDL' => [
                'currency' => 'Moldovan Leu',
                'minorUnit' => 2,
            ],
            'MNT' => [
                'currency' => 'Tugrik',
                'minorUnit' => 2,
            ],
            'MAD' => [
                'currency' => 'Moroccan Dirham',
                'minorUnit' => 2,
            ],
            'MZN' => [
                'currency' => 'Mozambique Metical',
                'minorUnit' => 2,
            ],
            'MMK' => [
                'currency' => 'Kyat',
                'minorUnit' => 2,
            ],
            'NAD' => [
                'currency' => 'Namibia Dollar',
                'minorUnit' => 2,
            ],
            'NPR' => [
                'currency' => 'Nepalese Rupee',
                'minorUnit' => 2,
            ],
            'NIO' => [
                'currency' => 'Cordoba Oro',
                'minorUnit' => 2,
            ],
            'NGN' => [
                'currency' => 'Naira',
                'minorUnit' => 2,
            ],
            'OMR' => [
                'currency' => 'Rial Omani',
                'minorUnit' => 3,
            ],
            'PKR' => [
                'currency' => 'Pakistan Rupee',
                'minorUnit' => 2,
            ],
            'PAB' => [
                'currency' => 'Balboa',
                'minorUnit' => 2,
            ],
            'PGK' => [
                'currency' => 'Kina',
                'minorUnit' => 2,
            ],
            'PYG' => [
                'currency' => 'Guarani',
                'minorUnit' => 0,
            ],
            'PEN' => [
                'currency' => 'Sol',
                'minorUnit' => 2,
            ],
            'PHP' => [
                'currency' => 'Philippine Peso',
                'minorUnit' => 2,
            ],
            'PLN' => [
                'currency' => 'Zloty',
                'minorUnit' => 2,
            ],
            'QAR' => [
                'currency' => 'Qatari Rial',
                'minorUnit' => 2,
            ],
            'RON' => [
                'currency' => 'Romanian Leu',
                'minorUnit' => 2,
            ],
            'RUB' => [
                'currency' => 'Russian Ruble',
                'minorUnit' => 2,
            ],
            'RWF' => [
                'currency' => 'Rwanda Franc',
                'minorUnit' => 0,
            ],
            'SHP' => [
                'currency' => 'Saint Helena Pound',
                'minorUnit' => 2,
            ],
            'WST' => [
                'currency' => 'Tala',
                'minorUnit' => 2,
            ],
            'STD' => [
                'currency' => 'Dobra',
                'minorUnit' => 2,
            ],
            'SAR' => [
                'currency' => 'Saudi Riyal',
                'minorUnit' => 2,
            ],
            'RSD' => [
                'currency' => 'Serbian Dinar',
                'minorUnit' => 2,
            ],
            'SCR' => [
                'currency' => 'Seychelles Rupee',
                'minorUnit' => 2,
            ],
            'SLL' => [
                'currency' => 'Leone',
                'minorUnit' => 2,
            ],
            'SGD' => [
                'currency' => 'Singapore Dollar',
                'minorUnit' => 2,
            ],
            'XSU' => [
                'currency' => 'Sucre',
                'minorUnit' => 0,
            ],
            'SBD' => [
                'currency' => 'Solomon Islands Dollar',
                'minorUnit' => 2,
            ],
            'SOS' => [
                'currency' => 'Somali Shilling',
                'minorUnit' => 2,
            ],
            'SSP' => [
                'currency' => 'South Sudanese Pound',
                'minorUnit' => 2,
            ],
            'LKR' => [
                'currency' => 'Sri Lanka Rupee',
                'minorUnit' => 2,
            ],
            'SDG' => [
                'currency' => 'Sudanese Pound',
                'minorUnit' => 2,
            ],
            'SRD' => [
                'currency' => 'Surinam Dollar',
                'minorUnit' => 2,
            ],
            'SZL' => [
                'currency' => 'Lilangeni',
                'minorUnit' => 2,
            ],
            'SEK' => [
                'currency' => 'Swedish Krona',
                'minorUnit' => 2,
            ],
            'CHE' => [
                'currency' => 'WIR Euro',
                'minorUnit' => 2,
            ],
            'CHW' => [
                'currency' => 'WIR Franc',
                'minorUnit' => 2,
            ],
            'SYP' => [
                'currency' => 'Syrian Pound',
                'minorUnit' => 2,
            ],
            'TWD' => [
                'currency' => 'New Taiwan Dollar',
                'minorUnit' => 2,
            ],
            'TJS' => [
                'currency' => 'Somoni',
                'minorUnit' => 2,
            ],
            'TZS' => [
                'currency' => 'Tanzanian Shilling',
                'minorUnit' => 2,
            ],
            'THB' => [
                'currency' => 'Baht',
                'minorUnit' => 2,
            ],
            'TOP' => [
                'currency' => 'Pa’anga',
                'minorUnit' => 2,
            ],
            'TTD' => [
                'currency' => 'Trinidad and Tobago Dollar',
                'minorUnit' => 2,
            ],
            'TND' => [
                'currency' => 'Tunisian Dinar',
                'minorUnit' => 3,
            ],
            'TRY' => [
                'currency' => 'Turkish Lira',
                'minorUnit' => 2,
            ],
            'TMT' => [
                'currency' => 'Turkmenistan New Manat',
                'minorUnit' => 2,
            ],
            'UGX' => [
                'currency' => 'Uganda Shilling',
                'minorUnit' => 0,
            ],
            'UAH' => [
                'currency' => 'Hryvnia',
                'minorUnit' => 2,
            ],
            'AED' => [
                'currency' => 'UAE Dirham',
                'minorUnit' => 2,
            ],
            'USN' => [
                'currency' => 'US Dollar (Next day)',
                'minorUnit' => 2,
            ],
            'UYU' => [
                'currency' => 'Peso Uruguayo',
                'minorUnit' => 2,
            ],
            'UYI' => [
                'currency' => 'Uruguay Peso en Unidades Indexadas (URUIURUI)',
                'minorUnit' => 0,
            ],
            'UZS' => [
                'currency' => 'Uzbekistan Sum',
                'minorUnit' => 2,
            ],
            'VUV' => [
                'currency' => 'Vatu',
                'minorUnit' => 0,
            ],
            'VEF' => [
                'currency' => 'Bolívar',
                'minorUnit' => 2,
            ],
            'VND' => [
                'currency' => 'Dong',
                'minorUnit' => 0,
            ],
            'YER' => [
                'currency' => 'Yemeni Rial',
                'minorUnit' => 2,
            ],
            'ZMW' => [
                'currency' => 'Zambian Kwacha',
                'minorUnit' => 2,
            ],
            'ZWL' => [
                'currency' => 'Zimbabwe Dollar',
                'minorUnit' => 2,
            ]
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
