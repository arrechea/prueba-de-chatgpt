<?php

use Illuminate\Database\Seeder;
use App\Models\Room\Room;

class ZudaRoomsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // -- ZUDA Rooms BEGIN
        Room::updateOrCreate([
            'id'           => 6,
            'name'         => 'CUBO',
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
        ], [
            'details'  => 'quantity',
            'capacity' => 30,
            'status'   => 'active',
        ]);

        Room::updateOrCreate([
            'id'           => 7,
            'name'         => 'FITBOX',
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
        ], [
            'details'  => 'quantity',
            'capacity' => 30,
            'status'   => 'active',
        ]);

        Room::updateOrCreate([
            'id'           => 8,
            'name'         => 'LA VIEJA GUARDIA',
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
        ], [
            'details'  => 'quantity',
            'capacity' => 30,
            'status'   => 'active',
        ]);
        // -- ZUDA Rooms END

    }
}
