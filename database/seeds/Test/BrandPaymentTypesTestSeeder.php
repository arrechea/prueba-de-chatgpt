<?php

use App\Models\Payment\BrandPaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class BrandPaymentTypesTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BrandPaymentType::updateOrCreate([
            'brands_id'        => 1,
            'payment_types_id' => 1,
        ], [
            'front' => 0,
            'back'  => 1,
        ]);
        BrandPaymentType::updateOrCreate([
            'brands_id'        => 1,
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
        BrandPaymentType::updateOrCreate([
            'brands_id'        => 1,
            'payment_types_id' => 3,
        ], [
            'config' => json_encode([
                'type'                        => 'development',
                'production_public_api_key'   => null,
                'production_private_api_key'  => null,
                'development_public_api_key'  => 'ARg7Pcp8rzYCpjlU4_ninRdMXI0NkgQ1vLa8mbDlH7f6HP3APEnw9aSQChNGhHbPSt5U4AHmiPF3YXC-',
                'development_private_api_key' => 'EDvmzUBu09NUX0HHTFKnMJdDyvgk5IR7zBSPunA9fbH09PoTyRLhlj4UV1C8gHuZRCEvTQhkL6CppBOz',
            ]),//'{"type": "development", "production_public_api_key": null, "development_public_api_key": "ARg7Pcp8rzYCpjlU4_ninRdMXI0NkgQ1vLa8mbDlH7f6HP3APEnw9aSQChNGhHbPSt5U4AHmiPF3YXC-", "production_private_api_key": null, "development_private_api_key": "EDvmzUBu09NUX0HHTFKnMJdDyvgk5IR7zBSPunA9fbH09PoTyRLhlj4UV1C8gHuZRCEvTQhkL6CppBOz"}',
            'front'  => 1,
            'back'   => 1,
        ]);
    }
}
