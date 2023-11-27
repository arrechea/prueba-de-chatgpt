<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(AbilityGroupsTableSeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(AbilitiesSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(OffersPossibilitiesSeeder::class);
        $this->call(PaymentMethodsSeeder::class);
        $this->call(AuthSeeder::class);
    }
}
