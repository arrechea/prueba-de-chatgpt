<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ZudaBrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            [
                'id'            => 5,
                'name'          => "ZUDA",
                'slug'          => 'zuda',
                'currencies_id' => 1,
                'language_id'   => 2,
                'companies_id'  => 3,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
