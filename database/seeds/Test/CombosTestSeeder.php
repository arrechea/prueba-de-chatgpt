<?php

use App\Models\Combos\Combos;
use Illuminate\Database\Seeder;

class CombosTestSeeder extends Seeder
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
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 350,
            'expiration_days' => 7,
            'order'           => 1,
        ], [
            'credits_id'   => 3,
            'credits'      => 1,
            'hide_in_home' => false,
        ]);

        Combos::updateOrCreate([
            'name'            => '3 Sesiones',
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 900,
            'expiration_days' => 14,
            'order'           => 2,
        ], [
            'credits_id' => 3,
            'credits'    => 3,
        ]);

        Combos::updateOrCreate([
            'name'            => '9 Sesiones',
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 2520,
            'expiration_days' => 30,
            'order'           => 3,
        ], [
            'credits_id' => 3,
            'credits'    => 9,
        ]);

        Combos::updateOrCreate([
            'name'            => '12 Sesiones',
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 3120,
            'expiration_days' => 60,
            'order'           => 4,
        ], [
            'credits_id'   => 3,
            'credits'      => 12,
            'hide_in_home' => false,
        ]);

        Combos::updateOrCreate([
            'name'            => '24 Sesiones',
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 5760,
            'expiration_days' => 120,
            'order'           => 5,
        ], [
            'credits_id' => 3,
            'credits'    => 24,
        ]);

        Combos::updateOrCreate([
            'name'            => '36 Sesiones',
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 7920,
            'expiration_days' => 180,
            'order'           => 6,
        ], [
            'credits_id'   => 3,
            'credits'      => 36,
            'hide_in_home' => false,
        ]);

        Combos::updateOrCreate([
            'name'            => '50 Sesiones',
            'companies_id'    => 1,
            'brands_id'       => 1,
            'price'           => 10000,
            'expiration_days' => 240,
            'order'           => 7,
        ], [
            'credits_id' => 3,
            'credits'    => 50,
        ]);
    }
}
