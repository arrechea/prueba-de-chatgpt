<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MetricsPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $memoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', -1);

        $output = new Symfony\Component\Console\Output\ConsoleOutput();

        if (!Schema::hasColumn('purchases', 'user_profiles_categories')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->string('user_profiles_categories')->nullable()->default('')->after('user_profiles_id');
                $table->string('user_profiles_email')->nullable()->default('')->after('user_profiles_id');
            });
        }
        \App\Models\Purchase\Purchase::with([
            'user_profile.categories' => function ($query) {
                $query->select('id');
            },
        ])
            ->chunk(50, function ($listadoCompras) use ($output) {
                $listadoCompras->each(function ($compra) use ($output) {
                    $user = $compra->user;
                    if ($user) {
                        $compra->user_profiles_categories = $user->generateUserCategoriesString();
                        $compra->user_profiles_email = $compra->user_profile->email??'';
                        $compra->save();
                        $output->writeln("<info>Compra actualizada: {$compra->id}</info>");
                    }
                });
            });
        // Restore memory limit consumption
        ini_set('memory_limit', $memoryLimit);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('user_profiles_categories');
            $table->dropColumn('user_profiles_email');
        });
    }
}
