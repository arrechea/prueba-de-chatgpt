<?php

use Illuminate\Database\Seeder;
use App\Models\Room\Room;

class RoomsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::updateOrCreate([
            'id'           => 1,
            'name'         => 'Salón 1',
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
        ], [
            'details'  => 'quantity',
            'capacity' => 35,
            'status'   => 'active',
        ]);

        Room::updateOrCreate([
            'id'           => 2,
            'name'         => 'Salón 2',
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
        ], [
            'details'  => 'quantity',
            'capacity' => 35,
            'status'   => 'active',
        ]);

        Room::updateOrCreate([
            'id'           => 3,
            'name'         => 'Salón 3',
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 2,
        ], [
            'details'  => 'quantity',
            'capacity' => 35,
            'status'   => 'active',
        ]);

        Room::updateOrCreate([
            'id'           => 4,
            'name'         => 'Salón 4',
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
        ], [
            'details'  => 'quantity',
            'capacity' => 30,
            'status'   => 'active',
        ]);

        Room::updateOrCreate([
            'id'           => 5,
            'name'         => 'Salón 5',
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
        ], [
            'details'  => 'quantity',
            'capacity' => 30,
            'status'   => 'active',
        ]);

    }
}
