<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 19/04/2018
 * Time: 04:45 PM
 */

namespace App\Observers;


use App\Librerias\Catalog\Tables\GafaFit\CatalogCompany;
use App\Librerias\SDKs\LibCloudflareSDK;
use App\Models\Company\Company;
use App\Models\gafafit\Settings;

class CompanyObserver
{
    private $prev_slug;

    /**
     * @param CatalogCompany $company
     */
    public function created(CatalogCompany $company)
    {
        $cloudflare = new LibCloudflareSDK();
        $cloudflare->newSite($company->slug);
    }

    public function deleting(CatalogCompany $company)
    {
        $cloudflare = new LibCloudflareSDK();
        $cloudflare->deleteSite($company->slug);
    }
}
