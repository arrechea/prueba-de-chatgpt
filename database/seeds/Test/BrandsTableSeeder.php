<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
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
                'id'            => 1,
                'name'          => "Test Brand 1",
                'slug'          => 'test-brand-1',
                'currencies_id' => 1,
                'language_id'   => 2,
                'companies_id'  => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'            => 2,
                'name'          => "Test Brand 2",
                'slug'          => 'test-brand-2',
                'currencies_id' => 1,
                'language_id'   => 2,
                'companies_id'  => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'            => 3,
                'name'          => "El T3mplo Pedregal",
                'slug'          => 'el-t3mplo-pedregal',
                'currencies_id' => 1,
                'language_id'   => 1,
                'companies_id'  => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'            => 4,
                'name'          => "El T3mplo Santa Fé",
                'slug'          => 'el-t3mplo-santa-fe',
                'currencies_id' => 1,
                'language_id'   => 1,
                'companies_id'  => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
