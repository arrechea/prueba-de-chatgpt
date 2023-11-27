<?php

use Database\traits\DisableForeignKeys;
use Database\traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate('cities');
        $this->truncate('country_states');

        $cities = new \Illuminate\Support\Collection(Config::get('cities'));
        if (!$cities) {
            throw new \Exception("Cities config file doesn't exists or empty, did you run: php artisan vendor:publish?");
        }
        /*
         * States
         */
        $country_states = $cities->map(function ($item) {
            return [
                'name'     => $item['district'],
                'country_code' => $item['country_code'],
            ];
        })->unique();

        DB::table('country_states')->insert($country_states->toArray());

        /*
         * Cities
         */
        $cities = $cities->map(function ($item) {
            $name = $item['district'];
            $code = $item['country_code'];
            $state = \App\Models\CountryState::where('name', $name)->where('country_code', $code)->first();
            $id = $state->id ?? 0;
            $item['country_states_id'] = $id;

            return $item;
        });

        DB::table('cities')->insert($cities->toArray());

        /*
         * End
         */
        $this->enableForeignKeys();
    }
}
