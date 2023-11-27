<?php

namespace App\Observers;

//use App\Models\Brand;

use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Librerias\GafaPay\LibGafaPay;

class BrandObserver
{
    /**
     * Handle the brand "created" event.
     *
     * @param  CatalogBrand  $brand
     * @return void
     */
    public function creating(CatalogBrand $brand)
    {
        LibGafaPay::processCreateBrand($brand);
    }

    /**
     * Handle the brand "updated" event.
     *
     * @param  CatalogBrand  $brand
     * @return void
     */
    public function updating(CatalogBrand $brand)
    {
        if(\request()->get('regeneratesecret') === 'on'){
            if($brand->gafapay_brand_id != null && $brand->gafapay_brand_id != ""){
                $secret = LibGafaPay::regenerateSecretBrand($brand->gafapay_brand_id);
                if($secret)
                    $brand->gafapay_client_secret = $secret;
            }
        }

        if ($brand->gafapay_client_id == null || $brand->gafapay_client_id == "") {
            LibGafaPay::processCreateBrand($brand);
        }
    }

//    /**
//     * Handle the brand "deleted" event.
//     *
//     * @param  CatalogBrand  $brand
//     * @return void
//     */
//    public function deleted(CatalogBrand $brand)
//    {
//        //
//    }
//
//    /**
//     * Handle the brand "restored" event.
//     *
//     * @param  CatalogBrand  $brand
//     * @return void
//     */
//    public function restored(CatalogBrand $brand)
//    {
//        //
//    }
//
//    /**
//     * Handle the brand "force deleted" event.
//     *
//     * @param  CatalogBrand  $brand
//     * @return void
//     */
//    public function forceDeleted(CatalogBrand $brand)
//    {
//        //
//    }
}
