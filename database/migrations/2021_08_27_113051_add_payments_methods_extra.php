<?php

use App\Models\Payment\PaymentType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsMethodsExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PaymentType::updateOrCreate([
            'slug' => 'gympass',
        ], [
            'name'  => 'company.gympass',
            'slug'  => 'gympass',
            'model' => \App\Librerias\Payments\PaymentTypes\Courtesy\Gympass::class,
            'order' => 4,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'fitpass',
        ], [
            'name'  => 'company.fitpass',
            'slug'  => 'fitpass',
            'model' => \App\Librerias\Payments\PaymentTypes\Courtesy\Fitpass::class,
            'order' => 5,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'ubereats',
        ], [
            'name'  => 'company.ubereats',
            'slug'  => 'ubereats',
            'model' => \App\Librerias\Payments\PaymentTypes\Courtesy\Ubereats::class,
            'order' => 6,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'rappy',
        ], [
            'name'  => 'company.rappy',
            'slug'  => 'rappy',
            'model' => \App\Librerias\Payments\PaymentTypes\Courtesy\Rappy::class,
            'order' => 7,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
