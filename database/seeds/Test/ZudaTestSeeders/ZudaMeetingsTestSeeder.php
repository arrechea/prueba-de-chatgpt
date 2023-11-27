<?php

use Illuminate\Database\Seeder;
use App\Models\Meeting\Meeting;
use Carbon\Carbon;

class ZudaMeetingsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 6,
            'staff_id'     => 4,
            'services_id'  => 6,
            'start_date' => Carbon::now()->addDay(),
            'end_date'   => Carbon::now()->addDay()->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 5,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(2),
            'end_date'   => Carbon::now()->addDay(2)->addHours(3),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 6,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(3),
            'end_date'   => Carbon::now()->addDay(3)->addHours(1),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 4,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(1),
            'end_date'   => Carbon::now()->addDay(1)->addHours(1),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 5,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(9),
            'end_date'   => Carbon::now()->addDay(9)->addHours(1),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 6,
            'staff_id'     => 6,
            'services_id'  => 6,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(3),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 7,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(8),
            'end_date'   => Carbon::now()->addDay(8)->addHours(3),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 7,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(1),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 6,
            'staff_id'     => 7,
            'services_id'  => 6,
            'start_date' => Carbon::now()->addDay(6),
            'end_date'   => Carbon::now()->addDay(6)->addHours(1),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 8,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(5),
            'end_date'   => Carbon::now()->addDay(5)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 8,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(),
            'end_date'   => Carbon::now()->addDay()->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 9,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(2),
            'end_date'   => Carbon::now()->addDay(2)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 10,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(3),
            'end_date'   => Carbon::now()->addDay(3)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 6,
            'staff_id'     => 10,
            'services_id'  => 6,
            'start_date' => Carbon::now()->addDay(1),
            'end_date'   => Carbon::now()->addDay(1)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 11,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(9),
            'end_date'   => Carbon::now()->addDay(9)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 11,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 8,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(8),
            'end_date'   => Carbon::now()->addDay(8)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 9,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 7,
            'staff_id'     => 12,
            'services_id'  => 7,
            'start_date' => Carbon::now()->addDay(6),
            'end_date'   => Carbon::now()->addDay(6)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 3,
            'brands_id'    => 5,
            'locations_id' => 4,
            'rooms_id'     => 8,
            'staff_id'     => 11,
            'services_id'  => 8,
            'start_date' => Carbon::now()->addDay(5),
            'end_date'   => Carbon::now()->addDay(5)->addHours(5),
            'capacity'   => 30,
        ]);
    }
}
