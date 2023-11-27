<?php

use Illuminate\Database\Seeder;
use App\Models\gafafit\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::updateOrCreate([
            'meta_key' => 'cloudflare.base-uri',
        ], [
            'meta_value' => 'https://api.cloudflare.com/client/v4/',
        ]);
    }
}
