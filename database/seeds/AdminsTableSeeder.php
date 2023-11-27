<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'name'       => "Wisquimas",
                'email'      => "mario@gafa.mx",
                'password'   => Hash::make('Wisquimas86'),
                'status'     => 'active',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'       => "Pablo Ruiz",
                'email'      => "pablor@gafa.mx",
                'password'   => Hash::make('Gafa@2018'),
                'status'     => 'active',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('admin_profiles')->insert([
            [
                'admins_id'    => 1,
                'companies_id' => 1,
                'first_name'   => "Mario",
                'last_name'    => "Sanzol",
                'email'        => "mario@gafa.mx",
                'status'       => 'active',
                'password'     => Hash::make('Wisquimas86'),
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'admins_id'    => 2,
                'companies_id' => 1,
                'first_name'   => "Pablo",
                'last_name'    => "R.",
                'email'        => "pablor@gafa.mx",
                'status'       => 'active',
                'password'     => Hash::make('Gafa@2018'),
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        \App\User::create([
            'id'       => 1,
            'name'       => "Wisquimas",
            'email'      => "mario@gafa.mx",
            'password'   => 'Wisquimas86',
            'status'     => 'active',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('user_profiles')->insert([
            [
                'users_id'     => 1,
                'companies_id' => 1,
                'first_name'   => "Mario",
                'last_name'    => "Sanzol",
                'email'        => "mario@gafa.mx",
                'password'     => Hash::make('Wisquimas86'),
                'status'       => true,
                'verified'     => true,
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

    }
}
