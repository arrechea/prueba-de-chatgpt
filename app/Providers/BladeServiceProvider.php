<?php

namespace App\Providers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;


class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        /**
         * Directiva para imprimir cargando
         */
        Blade::directive('cargando', function ($argumentos = null) {
            return $this->GetView('admin.common.cargando');
        });
        /**
         * Laravel dd() function.
         *
         * Usage: @dd($variableToDump)
         */
        Blade::directive('dd', function ($expression) {
            return "<?php dd({$expression}); ?>";
        });
        /**
         * Var Dump
         * Usage: @dump($variableToDump)
         */
        Blade::directive('dump', function ($expression) {
            return "<?php var_dump({$expression}); ?>";
        });

        Blade::directive('adminProfile', function () {

            $var = '<?php
                $default = "/images/square/chess_pawn.png";
                if (isset($company)) {
                    $profile = auth()->user()->profile()->where("companies_id", $company->id)->first();
                } else {
                    $profile = auth()->user();
                }
                $path = isset($profile) ? $profile->pic : null;
                $admin_profile_pic = $path !== null && $path !== "" ? url($path) : url($default);
                echo $admin_profile_pic;
                ?>';

            return $var;
        });
    }

    /**
     * Devuelve una vista
     *
     * @param $view
     *
     * @return string
     */
    private function GetView($view)
    {
        return '<?php echo $__env->make(\'' . $view . '\', array_except(get_defined_vars(), array(\'__data\', \'__path\')))->render(); ?>';
    }
}
