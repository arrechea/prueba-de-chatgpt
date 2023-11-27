<?php

use App\Models\Offer\OfferPossibility;
use Illuminate\Database\Seeder;

class OffersPossibilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfferPossibility::updateOrCreate([
            'name' => 'general_classes',
        ]);
    }
}
