<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 13/04/2018
 * Time: 10:51 AM
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class LanguageTableSeeder Extends Seeder
{
    public function run()
    {
        DB::table('language')->insert([
            [
                'name'       => 'lang.es',
                'slug'       => "es",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'lang.en',
                'slug'       => "en",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
