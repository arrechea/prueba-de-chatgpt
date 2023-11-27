<?php

use App\Librerias\Payments\PaymentTypes\Cash\Cash;
use App\Librerias\Payments\PaymentTypes\Conekta\Conekta;
use App\Librerias\Payments\PaymentTypes\Courtesy\Courtesy;
use App\Librerias\Payments\PaymentTypes\Paypal\Paypal;
use App\Models\Payment\PaymentType;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::updateOrCreate([
            'slug' => 'cash',
        ], [
            'name'  => 'company.Cash',
            'slug'  => 'cash',
            'model' => Cash::class,
            'order' => 0,
        ]);

        PaymentType::updateOrCreate([
            'slug' => 'conekta',
        ], [
            'name'  => 'company.Conekta',
            'slug'  => 'conekta',
            'model' => Conekta::class,
            'order' => 1,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'paypal',
        ], [
            'name'  => 'company.Paypal',
            'slug'  => 'paypal',
            'model' => Paypal::class,
            'order' => 2,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'courtesy',
        ], [
            'name'  => 'company.courtesy',
            'slug'  => 'courtesy',
            'model' => Courtesy::class,
            'order' => 3,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'srpago',
        ], [
            'name'  => 'company.srpago',
            'slug'  => 'srpago',
            'model' => \App\Librerias\Payments\PaymentTypes\Srpago\Srpago::class,
            'order' => 3,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'stripe',
        ], [
            'name'  => 'company.stripe',
            'slug'  => 'stripe',
            'model' => \App\Librerias\Payments\PaymentTypes\Stripe\Stripe::class,
            'order' => 3,
        ]);
        PaymentType::updateOrCreate([
            'slug' => 'terminal',
        ], [
            'name'  => 'company.terminal',
            'slug'  => 'terminal',
            'model' => \App\Librerias\Payments\PaymentTypes\Terminal\Terminal::class,
            'order' => 3,
        ]);
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
}
