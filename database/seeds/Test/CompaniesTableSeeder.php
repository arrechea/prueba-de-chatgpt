<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
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
                'id'          => 1,
                'name'        => "Test Company 1",
                'slug'        => 'test-company-1',
                'email'       => 'testcompany@gafa.mx',
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'status'      => 'active',
                'language_id' => 2,
            ],
            [
                'id'          => 2,
                'name'        => "El t3mplo",
                'slug'        => 'el-t3mplo',
                'email'       => 'elt3mplo@gafa.mx',
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'status'      => 'active',
                'language_id' => 2,
            ]
        ]);
    }
}
