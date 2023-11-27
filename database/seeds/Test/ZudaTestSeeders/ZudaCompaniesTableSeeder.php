<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ZudaCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'id'          => 3,
                'name'        => "ZUDA",
                'slug'        => 'zuda',
                'email'       => 'contact@zuda.mx',
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'status'      => 'active',
                'language_id' => 2,
            ],
        ]);
    }
}
