<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/05/2018
 * Time: 04:00 PM
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ZudaCreditsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('credits')->insert([

            // -- ZUDA
            [
                'id'           => 4,
                'name'         => "ZUDA",
                'companies_id' => 3,
                'brands_id'    => 5,
                'status'       => 'active',
                'picture'      => 'http://op-treasure-cruise.fr/images/icones/sac-berry.png',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 4,
            'services_id' => 6,
            'credits'     => 1,
        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 4,
            'services_id' => 7,
            'credits'     => 1,
        ]);

        DB::table('credits_services')->insert([
            'credits_id'  => 4,
            'services_id' => 8,
            'credits'     => 1,
        ]);
    }
}
