<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 13/04/18
 * Time: 11:15
 */

namespace App\Librerias\Settings;


use App\Models\gafafit\Settings;
use App\Http\Requests\AdminRequest as Request;

class LibSettings
{
    private $settings;

    function __construct()
    {
        $this->settings = Settings::all();
    }

    /**
     * @param string $key
     *
     * @return string
     * @internal param $settings
     */
    public function get($key = '')
    {
        $busqueda = $this->getSettings()->first(function ($setting) use ($key) {
            return $setting->meta_key === $key;
        });

        return $busqueda ? $busqueda->meta_value : '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|static[] $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public static function saveSettings(Request $request)
    {
        $cloudflare = $request->get('cloudflare', []);

        if (!isset($cloudflare['enabled'])) {
            Settings::updateOrCreate([
                'meta_key' => 'cloudflare.enabled',
            ], [
                'meta_value' => '',
            ]);
        }

        foreach ($cloudflare as $k => $v) {
            Settings::updateOrCreate([
                'meta_key' => 'cloudflare.' . $k,
            ], [
                'meta_value' => $v ?? '',
            ]);
        }

        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $setting = \App\Models\gafafit\Settings::updateOrCreate([
                'meta_key' => 'pic',
            ], [
                'meta_key' => 'pic',
            ]);
            $url = $setting->UploadImage($file);
            $setting->meta_value = $url;
            $setting->save();
        }

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $setting = \App\Models\gafafit\Settings::updateOrCreate([
                'meta_key' => 'icon',
            ], [
                'meta_key' => 'icon',
            ]);
            $url = $setting->UploadImage($file);
            $setting->meta_value = $url;
            $setting->save();
        }
    }
}
