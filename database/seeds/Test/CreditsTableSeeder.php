<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/05/2018
 * Time: 04:00 PM
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreditsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('credits')->insert([
            [
                'id'           => 1,
                'name'         => "Test credit 1",
                'companies_id' => 1,
                'brands_id'    => 1,
                'status'       => 'active',
                'picture'      => 'https://i.pinimg.com/originals/79/90/a8/7990a8949cdb9464b75cdec253bbf13b.jpg',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'           => 2,
                'name'         => "Test credit 2",
                'companies_id' => 1,
                'brands_id'    => 1,
                'status'       => 'active',
                'picture'      => 'http://op-treasure-cruise.fr/images/icones/sac-berry.png',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id'           => 3,
                'name'         => "T3mplo",
                'companies_id' => 1,
                'brands_id'    => 1,
                'status'       => 'active',
                'picture'      => 'http://op-treasure-cruise.fr/images/icones/sac-berry.png',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 3,
            'services_id' => 1,
            'credits'     => 1,
        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 3,
            'services_id' => 2,
            'credits'     => 1,
        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 3,
            'services_id' => 3,
            'credits'     => 1,
        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 3,
            'services_id' => 4,
            'credits'     => 1,
        ]);
    }
}
