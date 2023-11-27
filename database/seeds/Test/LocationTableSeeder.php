<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
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
                'id'           => 1,
                'name'         => "El t3mplo Pedregal",
                'slug'         => 't3mplo-pedregal',
                'companies_id' => 1,
                'brands_id'    => 1,
                'latitude'     => 19.4191427,
                'longitude'    => -99.164345,
                'phone'        => "55 55555555",
                'email'        => "pedregal@elt3mplo.mx",
                'street'       => "Colima",
                'number'       => "220",
                'postcode'     => "06700",
                'suburb'       => "Roma Norte",
                'district'     => "Cuauhtemoc",
                'city'         => 'Ciudad de México',
                'countries_id' => '136',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'           => 2,
                'name'         => "El t3mplo Santa Fé",
                'slug'         => 't3mplo-santa-fe',
                'companies_id' => 1,
                'brands_id'    => 1,
                'latitude'     => 19.3563873,
                'longitude'    => -99.2232571,
                'phone'        => "55 55555555",
                'email'        => "santafe@elt3mplo.mx",
                'street'       => "Colima",
                'number'       => "220",
                'postcode'     => "06700",
                'suburb'       => "Lomas",
                'district'     => "Santa Fé",
                'city'         => 'Ciudad de México',                'countries_id' => '136',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'           => 3,
                'name'         => "El t3mplo Cancún",
                'slug'         => 't3mplo-cancun',
                'companies_id' => 1,
                'brands_id'    => 1,
                'latitude'     => 19.3938433,
                'longitude'    => -99.1693835,
                'phone'        => "55 55555555",
                'email'        => "cancun@elt3mplo.mx",
                'street'       => "Colima",
                'number'       => "220",
                'postcode'     => "06700",
                'suburb'       => "Roma Norte",
                'district'     => "Cuauhtemoc",
                'city'         => 'Ciudad de México',                'countries_id' => '136',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
