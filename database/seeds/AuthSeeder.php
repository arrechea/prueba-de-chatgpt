<?php

use Illuminate\Database\Seeder;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'companies_id'           => 1,
            'name'                   => 'test-company-1',
            'secret'                 => '6iiSBO8CLz3Y1PDHaKNtLX74HboIktnynvxuMysA',
            'redirect'               => '',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'revoked'                => 0,
        ]);
        DB::table('oauth_clients')->insert([
            'companies_id'           => 2,
            'name'                   => 'el-t3mplo',
            'secret'                 => 'rik}UJA<a9.CS/97d!TZXQ7,Hf&==h9[j27&sCc&',
            'redirect'               => '',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'revoked'                => 0,
        ]);
        DB::table('oauth_clients')->insert([
            'companies_id'           => 3,
            'name'                   => 'zuda',
            'secret'                 => 'rh9}UJA<7,H7d!T27&a9.9ZXQsCf&CS/[jik==c&',
            'redirect'               => '',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'revoked'                => 0,
        ]);
    }
}
