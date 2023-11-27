<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 23/02/18
 * Time: 09:23
 */

namespace App\Http\Controllers;


use App\Librerias\Helpers\LibRoute;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Language\Language;

class ApiController extends Controller
{

    /**
     * @var Company
     */
    private $company;
    private $language;

    function __construct()
    {
        $companyID = LibRoute::getCompanyHeader(request());
        if ($companyID) {

            $this->company = Company::where('id', $companyID)
                ->where('status', 'active')->first();
            $brand_slug = \request()->route('brand');
            $brand = $brand_slug ? Brand::where('slug', $brand_slug)->first() : null;
            $language = $brand->language ?? ($this->company->language??null);
            $this->language = $language->slug ?? 'en';
        }
    }

    /**
     * @return Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function getLanguage(): string
    {
        return $this->language ? $this->language : Language::find(1);
    }
}
