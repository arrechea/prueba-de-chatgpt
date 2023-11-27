<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ZudaLocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            [
                'id'           => 4,
                'name'         => "ZUDA Plaza Lilas",
                'slug'         => 'zuda-plaza-lilas',
                'companies_id' => 3,
                'brands_id'    => 5,
                'latitude'     => 19.388055,
                'longitude'    => -99.247881,
                'phone'        => "59256308",
                'email'        => "lilas@zuda.mx",
                'street'       => "Paseo de Las Lilas",
                'number'       => "92",
                'postcode'     => "11910",
                'suburb'       => "Bosques de las Lomas",
                'district'     => "Miguel Hidalgo",
                'city'         => 'Ciudad de México',
                'countries_id' => '136',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}