<?php

use App\Models\Payment\BrandPaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ZudaPaymentTypesTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // -- ZUDA payment types seeder
        BrandPaymentType::updateOrCreate([
            'brands_id'        => 5,
            'payment_types_id' => 1,
        ], [
            'front' => 0,
            'back'  => 1,
        ]);
        BrandPaymentType::updateOrCreate([
            'brands_id'        => 5,
            'payment_types_id' => 2,
        ], [
            'config' => json_encode([
                'type'                        => 'development',
                'production_public_api_key'   => null,
                'production_private_api_key'  => null,
                'development_public_api_key'  => 'key_CureqTtJ67LJYzqtw7u6isg',
                'development_private_api_key' => 'key_spqMGtzKs6SwskzzhxBRvg',
            ]),//'{"type": "development", "production_public_api_key": null, "development_public_api_key": null, "production_private_api_key": null, "development_private_api_key": null}',
            'front'  => 1,
            'back'   => 1,
        ]);

        // -- ZUDA payment types seeder
    }
}
