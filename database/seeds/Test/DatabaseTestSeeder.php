<?php

use Illuminate\Database\Seeder;

class DatabaseTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DatabaseSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RoomsTestSeeder::class);
        $this->call(ServicesTestSeeder::class);
        $this->call(StaffTestSeeder::class);
        $this->call(MeetingsTestSeeder::class);
        $this->call(CombosTestSeeder::class);
        $this->call(CreditsTableSeeder::class);
        $this->call(MembershipTestSeeder::class);
        $this->call(AdminCompanyTableSeeder::class);
        $this->call(BrandPaymentTypesTestSeeder::class);

        $this->call(ZudaRunTestSeeder::class);

    }
}
