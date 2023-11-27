<?php

use Illuminate\Database\Seeder;
use App\Models\Meeting\Meeting;
use Carbon\Carbon;

class MeetingsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 1,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(),
            'end_date'   => Carbon::now()->addDay()->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 2,
            'staff_id'     => 2,
            'services_id'  => 2,
            'start_date' => Carbon::now()->addDay(2),
            'end_date'   => Carbon::now()->addDay(2)->addHours(3),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 2,
            'rooms_id'     => 3,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(3),
            'end_date'   => Carbon::now()->addDay(3)->addHours(1),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 4,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(1),
            'end_date'   => Carbon::now()->addDay(1)->addHours(1),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 5,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(9),
            'end_date'   => Carbon::now()->addDay(9)->addHours(1),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 1,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(3),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 2,
            'staff_id'     => 2,
            'services_id'  => 2,
            'start_date' => Carbon::now()->addDay(8),
            'end_date'   => Carbon::now()->addDay(8)->addHours(3),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 2,
            'rooms_id'     => 3,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(1),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 4,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(6),
            'end_date'   => Carbon::now()->addDay(6)->addHours(1),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 5,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(5),
            'end_date'   => Carbon::now()->addDay(5)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 1,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(),
            'end_date'   => Carbon::now()->addDay()->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 2,
            'staff_id'     => 2,
            'services_id'  => 2,
            'start_date' => Carbon::now()->addDay(2),
            'end_date'   => Carbon::now()->addDay(2)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 2,
            'rooms_id'     => 3,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(3),
            'end_date'   => Carbon::now()->addDay(3)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 4,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(1),
            'end_date'   => Carbon::now()->addDay(1)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 5,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(9),
            'end_date'   => Carbon::now()->addDay(9)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 1,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 1,
            'rooms_id'     => 2,
            'staff_id'     => 2,
            'services_id'  => 2,
            'start_date' => Carbon::now()->addDay(8),
            'end_date'   => Carbon::now()->addDay(8)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 2,
            'rooms_id'     => 3,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(7),
            'end_date'   => Carbon::now()->addDay(7)->addHours(2),
            'capacity'   => 35,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 4,
            'staff_id'     => 3,
            'services_id'  => 3,
            'start_date' => Carbon::now()->addDay(6),
            'end_date'   => Carbon::now()->addDay(6)->addHours(2),
            'capacity'   => 30,
        ]);

        Meeting::updateOrCreate([
            'companies_id' => 1,
            'brands_id'    => 1,
            'locations_id' => 3,
            'rooms_id'     => 5,
            'staff_id'     => 1,
            'services_id'  => 1,
            'start_date' => Carbon::now()->addDay(5),
            'end_date'   => Carbon::now()->addDay(5)->addHours(5),
            'capacity'   => 30,
        ]);
    }
}
