<?php

use App\Models\Combos\Combos;
use Illuminate\Database\Seeder;

class ZudaCombosTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Combos::updateOrCreate([
            'name'            => '1 Sesión',
            'companies_id'    => 3,
            'brands_id'       => 5,
            'price'           => 300,
            'expiration_days' => 30,
            'order'           => 1,
            'credits_id'   => 4,
            'credits'      => 1,
            'hide_in_home' => false,
        ], [

        ]);

        Combos::updateOrCreate([
            'name'            => '5 Sesiones',
            'companies_id'    => 3,
            'brands_id'       => 5,
            'price'           => 1450,
            'expiration_days' => 30,
            'order'           => 1,
            'credits_id'   => 4,
            'credits'      => 5,
            'hide_in_home' => false,
        ], [

        ]);

        Combos::updateOrCreate([
            'name'            => '10 Sesiones',
            'companies_id'    => 3,
            'brands_id'       => 5,
            'price'           => 2800,
            'expiration_days' => 30,
            'order'           => 1,
            'credits_id'   => 4,
            'credits'      => 10,
            'hide_in_home' => false,
        ], [

        ]);

        Combos::updateOrCreate([
            'name'            => '20 Sesiones',
            'companies_id'    => 3,
            'brands_id'       => 5,
            'price'           => 5200,
            'expiration_days' => 60,
            'order'           => 1,
            'credits_id'   => 4,
            'credits'      => 20,
            'hide_in_home' => false,
        ], [

        ]);
    }
}
