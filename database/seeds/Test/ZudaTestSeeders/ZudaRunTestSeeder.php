<?php

use Illuminate\Database\Seeder;

class ZudaRunTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ZudaCompaniesTableSeeder::class);
        $this->call(ZudaBrandsTableSeeder::class);
        $this->call(ZudaLocationTableSeeder::class);
        $this->call(ZudaRoomsTestSeeder::class);
        $this->call(ZudaServicesTestSeeder::class);
        $this->call(ZudaStaffTestSeeder::class);
        $this->call(ZudaMeetingsTestSeeder::class);
        $this->call(ZudaCombosTestSeeder::class);
        $this->call(ZudaPaymentTypesTestSeeder::class);
        $this->call(ZudaCreditsTableSeeder::class);
        $this->call(ZudaUsersTableSeeder::class);
        $this->call(ZudaColorsTestSeeder::class);

    }
}
