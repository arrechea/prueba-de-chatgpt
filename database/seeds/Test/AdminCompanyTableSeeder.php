<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use  \Illuminate\Support\Facades\DB;
use \App\Librerias\Permissions\Role;
use Carbon\Carbon;
use App\Admin;
use App\Models\Company\Company;

class AdminCompanyTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('admins')->insert([
            [
                'name'       => "AdminCompany",
                'email'      => "admin-company@test.com",
                'password'   => Hash::make('gafa2018'),
                'status'     => 'active',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);

        DB::table('admin_profiles')->insert([
           [
               'admins_id'    => 3,
               'companies_id' => 1,
               'email'        => "admin-company@test.com",
               'status'       => 'active',
               'password'     => Hash::make('gafa2018'),
               'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
               'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
           ]
        ]);

        DB::table('roles')->insert([
           [
               'id'=> 3,
               'name' => 'admincompany',
               'title' => 'AdminCompany',
               'type' => \App\Librerias\Permissions\LibPermissions::LEVEL_COMPANY,
           ]
        ]);

        DB::table('permissions')->insert([
            [
                'ability_id' => 2,
                'entity_id' => 3,
                'entity_type' => Role::class

            ],
            [
                'ability_id' => 3,
                'entity_id' => 3,
                'entity_type' => Role::class

            ],
            [
                'ability_id' => 4,
                'entity_id' => 3,
                'entity_type' => Role::class

            ],
        ]);

        DB::table('assigned_roles')->insert([
            [
                'role_id' => 3,
                'entity_id' => 3,
                'entity_type' => Admin::class,
                'assigned_id' => 1,
                'assigned_type' => Company::class,
            ],
        ]);
    }
}
