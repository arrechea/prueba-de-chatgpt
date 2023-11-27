<?php

use App\Models\gafafit\Settings;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::updateOrCreate([
            'meta_key' => 'cloudflare.zone-id',
        ], [
            'meta_value' => '18b6f57495c424d2cfce578e946352ec',
        ]);

        Settings::updateOrCreate([
            'meta_key' => 'cloudflare.x-auth-key',
        ], [
            'meta_value' => '00e381774a27becb0c149c380c25d55fd6428',
        ]);

        Settings::updateOrCreate([
            'meta_key' => 'cloudflare.x-auth-email',
        ], [
            'meta_value' => 'i@gafa.mx',
        ]);

        Settings::updateOrCreate([
            'meta_key' => 'cloudflare.ip',
        ], [
            'meta_value' => '52.225.228.54',
        ]);
    }
}
