<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 13/04/2018
 * Time: 10:42 AM
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class CurrenciesTableSeeder Extends Seeder
{
    public function run()
    {
//        DB::table('currencies')->insert([
//            [
//
//                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
//            ],
//            [
//                'name'       => "currency.dollars",
//                'prefijo'    => "$",
//                'sufijo'     => "USD",
//                'code3'      => "USD",
//                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
//
//            ],
//        ]);

        \App\Models\Currency\Currencies::updateOrCreate([
            'name'       => 'currency.mexican',
            'code3'      => 'MXN',
        ],[
            'prefijo'    => "$",
            'sufijo'     => "MXN",
        ]);

        \App\Models\Currency\Currencies::updateOrCreate([
            'name'       => 'currency.dollars',
            'code3'      => 'USD',
        ],[
            'prefijo'    => "$",
            'sufijo'     => "USD",
        ]);

        \App\Models\Currency\Currencies::updateOrCreate([
            'name'       => 'currency.euro',
            'code3'      => 'EUR',
        ],[
            'prefijo'    => "€",
            'sufijo'     => "EUR",
        ]);

        \App\Models\Currency\Currencies::updateOrCreate([
            'name'       => 'currency.quetzal',
            'code3'      => 'GTQ',
        ],[
            'prefijo'    => "Q",
            'sufijo'     => "GTQ",
        ]);
    }
}
