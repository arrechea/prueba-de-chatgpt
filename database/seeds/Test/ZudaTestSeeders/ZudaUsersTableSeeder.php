<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ZudaUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'id'       => 2,
            'name'       => "Pablo Ruiz",
            'email'      => "pablor@gafa.mx",
            'password'   => Hash::make('Gafa@2018'),
            'status'     => 'active',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('user_profiles')->insert([
            [
                'users_id'     => 2,
                'companies_id' => 3,
                'first_name'   => "Pablo",
                'last_name'    => "Ruiz",
                'email'        => "pablor@gafa.mx",
                'password'     => Hash::make('Gafa@2018'),
                'status'       => true,
                'verified'     => true,
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

    }
}
