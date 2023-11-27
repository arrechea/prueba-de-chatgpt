<?php

use Database\traits\DisableForeignKeys;
use Database\traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate('countries');

        $countries = Config::get('countries');
        if (!$countries) {
            throw new \Exception("Countries config file doesn't exists or empty, did you run: php artisan vendor:publish?");
        }
        DB::table('countries')->insert($countries);
        $this->enableForeignKeys();
    }
}
