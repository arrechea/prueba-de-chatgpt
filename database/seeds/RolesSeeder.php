<?php

use App\Librerias\Permissions\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate([
            'name' => 'generic',
        ], [
            'title' => 'Generic',
            'type'  => 'gafafit',
        ]);
    }
}
