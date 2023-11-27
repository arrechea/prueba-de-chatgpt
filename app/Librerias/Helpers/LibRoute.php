<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 16/04/18
 * Time: 17:13
 */

namespace App\Librerias\Helpers;


use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Http\Request;

abstract class LibRoute
{
    /**
     * @param $request
     *
     * @return Company
     */
    static public function getCompany($request)
    {
        $companySlug = $request->route('company');

        if ($companySlug instanceof Company) {
            $company = $companySlug;
        } else {
            if (!$companySlug) {
                //No slug, check header
                $gafaFitHeader = self::getCompanyHeader($request);
                $company = Company::where('id', $gafaFitHeader)->first();
            } else {
                //Hay slug
                $company = Company::where('slug', $companySlug)->first();
            }
        }

        return $company;
    }

    /**
     * @param $request
     *
     * @return array|string
     */
    static public function getCompanyHeader(Request $request)
    {
        return $request->header("gafafit-company");
    }

    /**
     * @param $request
     *
     * @return Company
     */
    static public function getCompanyLogin($request)
    {
        $companySlug = $request->route('companyLogin');

        if ($companySlug instanceof Company) {
            $company = $companySlug;
        } else {
            $company = Company::where('slug', $companySlug)->first();
        }

        return $company;
    }

    /**
     * @param $request
     *
     * @return Brand
     */
    static public function getBrand($request)
    {
        $brandSlug = $request->route('brand');

        if ($brandSlug instanceof Brand) {
            $brand = $brandSlug;
        } else {
            $brand = Brand::where('slug', $brandSlug)->first();
        }

        return $brand;
    }

    /**
     * @param        $request
     *
     * @param string $locationKey
     *
     * @return Location
     */
    static public function getLocation($request, $locationKey = 'location')
    {
        $locationSlug = $request->route($locationKey);

        if ($locationSlug instanceof Location) {
            $location = $locationSlug;
        } else {
            $location = Location::where('slug', $locationSlug)->first();
        }

        return $location;
    }
}
