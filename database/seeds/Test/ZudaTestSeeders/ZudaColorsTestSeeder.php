<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 02/08/2018
 * Time: 04:02 PM
 */

use \Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class ZudaColorsTestSeeder extends Seeder
{
    public function run()
    {
        DB::table('companies_colors')->insert([
            [
                'companies_id'             => 3,
                'color_black'              => 'black',
                'color_main'               => '#323232',
                'color_secondary'          => '#626262',
                'color_secondary2'         => '#929292',
                'color_secondary3'         => '#C2C2C2',
                'color_light'              => '#C2C2C2',
                'color_menutop'            => '#323232',
                'color_menuleft'           => '#323232',
                'color_menuleft_secondary' => '#323232',
                'color_menuleft_selected'  => '#626262',
                'color_alert'              => 'red',
            ],
        ]);
    }

}
