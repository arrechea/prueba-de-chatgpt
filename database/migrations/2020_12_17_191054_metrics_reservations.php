<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MetricsReservations extends Migration
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

        if (!Schema::hasColumn('reservations', 'user_profiles_categories')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('user_profiles_categories')->nullable()->default('')->after('user_profiles_id');
                $table->string('user_profiles_email')->nullable()->default('')->after('user_profiles_id');
            });
        }
        \App\Models\Reservation\Reservation::with([
            'user.categories' => function ($query) {
                $query->select('id');
            },
        ])
            ->chunk(50, function ($listadoReservas) use ($output) {
                $listadoReservas->each(function ($reserva) use ($output) {
                    $user = $reserva->user;
                    if ($user) {
                        $reserva->user_profiles_categories = $user->generateUserCategoriesString();
                        $reserva->user_profiles_email = $reserva->user->email??'';
                        $reserva->save();
                        $output->writeln("<info>Reserva actualizada: {$reserva->id}</info>");
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
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('user_profiles_categories');
            $table->dropColumn('user_profiles_email');
        });
    }
}
