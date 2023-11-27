<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 24/05/2018
 * Time: 05:27 PM
 */

namespace App\Librerias\Credits;

use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogBrandsCredits;
use App\Librerias\Catalog\Tables\Company\CatalogCompanyCredits;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditsBrand;
use App\Models\Credit\CreditsServices;
use App\Models\Service;

class LibCredits
{
    /**
     * Guardar los servicios asignados a un crédito.
     *
     * @param Request $request
     * @param Company $company
     * @param Brand $brand
     * @param Credit $credit
     */
    public static function saveServices(Request $request, Company $company, Brand $brand, Credit $credit)
    {
        $services_array = array_where($request->get('services'), function ($v) {
            return isset($v['active']) && $v['active'] === 'on';
        });

        $saved = [];

        foreach ($services_array as $service) {
            $db_service = Service::find($service['id']);

            if ($db_service->companies_id !== $company->id)
                continue;
            if ($db_service->brands_id !== $brand->id)
                continue;

            $creditsForService = isset($service['credits']) ? (int)$service['credits'] : 1;
            if ($creditsForService < 1) {
                $creditsForService = 1;
            }

            CreditsServices::updateOrCreate([
                'credits_id' => $credit->id,
                'services_id' => $service['id'],
            ], [
                'credits' => $creditsForService,
            ]);

            $saved[] = $service['id'];
        }

        CreditsServices::whereNotIn('services_id', $saved)->where('credits_id', $credit->id)->delete();
    }

    public static function saveServicesInCompany(Request $request, Company $company, Credit $credit)
    {
        $services_array = array_where($request->get('services'), function ($v) {
            return isset($v['active']) && $v['active'] === 'on';
        });

        $saved = [];

        foreach ($services_array as $service) {
            $db_service = Service::find($service['id']);

            if ($db_service->companies_id !== $company->id)
                continue;

            $creditsForService = isset($service['credits']) ? (int)$service['credits'] : 1;
            if ($creditsForService < 1) {
                $creditsForService = 1;
            }

            CreditsServices::updateOrCreate([
                'credits_id' => $credit->id,
                'services_id' => $service['id'],
            ], [
                'credits' => $creditsForService,
            ]);

            $saved[] = $service['id'];
        }

        CreditsServices::whereNotIn('services_id', $saved)->where('credits_id', $credit->id)->delete();
    }


    public static function saveServicesInGafa(Request $request, Credit $credit)
    {
        $services_array = array_where($request->get('services'), function ($v) {
            return isset($v['active']) && $v['active'] === 'on';
        });

        $saved = [];

        foreach ($services_array as $service) {
            $db_service = Service::find($service['id']);


            $creditsForService = isset($service['credits']) ? (int)$service['credits'] : 1;
            if ($creditsForService < 1) {
                $creditsForService = 1;
            }

            CreditsServices::updateOrCreate([
                'credits_id' => $credit->id,
                'services_id' => $service['id'],
            ], [
                'credits' => $creditsForService,
            ]);

            $saved[] = $service['id'];
        }

        CreditsServices::whereNotIn('services_id', $saved)->where('credits_id', $credit->id)->delete();
    }

    /**
     * @param Brand $brand
     * @return mixed
     */
    public static function getCredits(Brand $brand)
    {

        return Credit::where([
            ['brands_id', $brand->id],
            ['status', 'active'],
        ])->get();
    }

    public static function getCreditsCompany(Brand $brand, Company $company, $credits_array)
    {

        if ($brand->companies_id === $company->id) {
            $credits_list = [];
            foreach ($credits_array as $credito) {
                $credits = Credit::where(function ($q) use ($brand, $credito) {
                    $q->where('id', $credito->credits_id);
                    $q->whereNull('brands_id');
                    $q->where('companies_id', $brand->companies_id);
                    $q->where('status', 'active');
                })
                    ->get();
                foreach ($credits as $credit) {
                    array_push($credits_list, $credit);
                }
            }

        }
        return $credits_list;

    }

    public static function getCreditsGafa(Brand $brand, Company $company, $credits_array)
    {

        if ($brand->companies_id === $company->id) {
            $creditsgf_list = [];
            foreach ($credits_array as $credito) {
                $credits = Credit::where(function ($q) use ($brand, $credito) {
                    $q->where('id', $credito->credits_id);
                    $q->whereNull('brands_id');
                    $q->whereNull('companies_id');
                    $q->where('status', 'active');
                })
                    ->get();

                foreach ($credits as $credit) {
                    array_push($creditsgf_list, $credit);
                }
            }

        }
        return $creditsgf_list;

    }

    /**
     * @param $company
     * @param $credits_array
     * @return array
     */
    public static function getCreditsBrands($company, $credits_array)
    {
        $brands_list = [];
        foreach ($credits_array as $credit) {
            if ($credit->companies_id === $company) {
                $brands = Brand::where([
                    ['id', $credit->brands_id],
                    ['status', 'active'],
                ])->orWhere(function ($q) use ($credit) {
                    $q->where('id', $credit->brands_id);
                    $q->where('companies_id', $credit->companies_id);
                    $q->where('status', 'active');
                })
                    ->get();

                foreach ($brands as $brand_c) {
                    array_push($brands_list, $brand_c);
                }
            }

        }

        return $brands_list;
    }

    public static function getCreditsBrandsGF($company, $credits_array)
    {
        $brands_list = [];
        foreach ($credits_array as $credit) {

            $brands = Brand::where([
                ['id', $credit->brands_id],
                ['status', 'active'],
            ])->orWhere(function ($q) use ($credit) {
                $q->where('id', $credit->brands_id);
                $q->where('status', 'active');
            })
                ->get();

            foreach ($brands as $brand_c) {
                array_push($brands_list, $brand_c);
            }
        }

        return $brands_list;
    }

    public static function getCreditBrands($company, $credit)
    {
        $brands_list = [];

        if ($credit->companies_id === $company) {
            $brands = Brand::where([
                ['id', $credit->brands_id],
                ['status', 'active'],
            ])->orWhere(function ($q) use ($credit) {
                $q->where('id', $credit->brands_id);
                $q->where('companies_id', $credit->companies_id);
                $q->where('status', 'active');
            })
                ->get();

            foreach ($brands as $brand_c) {
                dd($brand_c);
                array_push($brands_list, (string)$brand_c->name);
            }
        }

        return $brands_list;
    }

    public static function deleteBrands(Credit $credit)
    {
        //->where('companies_id', $company->id)
        CreditsBrand::select('*')->where('credits_id', $credit->id)->delete();
    }



    /**
     * Para tabla Credits_brands
     * Busca en la tabla los que concuerden con  credits_id y companies_id
     * y los elimina, luego trae de request los que se deben crear
     *
     * @param Request $request
     * @param Company $company
     * @param Credit $credit
     */
    public static function saveBrands(Request $request, Company $company, Credit $credit)
    {
        $brands_array = array_where($request->get('brandCompanies'), function ($v) {
            $brands = json_decode($v, true);
            return isset($brands['status']) && $brands['status'] === 'active';
        });

        $saved = [];

        LibCredits::deleteBrands($credit, $company);

        if ($request->input('brandCompanies')) {

            foreach ($brands_array as $brand) {
                $brand_c = json_decode($brand, true);
                $db_brand = Brand::find($brand_c['id']);

                if ($db_brand->companies_id !== $company->id)
                    continue;

                CreditsBrand::updateOrCreate([
                    'brands_id' => $brand_c['id'],
                    'companies_id' => $company->id,
                    'credits_id' => $credit->id,
                ]);

                $saved[] = $brand_c;

            }
        }
    }


    /**
     * Para tabla Credits_brands
     * Busca en la tabla los que concuerden con  credits_id y companies_id
     * y los elimina, luego trae de request los que se deben crear
     *
     * @param Request $request
     * @param Company $company
     * @param Credit $credit
     */
    public static function saveBrandsInGafa(Request $request,  Credit $credit)
    {
        $brands_array = array_where($request->get('brandCompanies'), function ($v) {
            $brands = json_decode($v, true);
            return isset($brands['status']) && $brands['status'] === 'active';
        });

        $saved = [];

        LibCredits::deleteBrands($credit);

        if ($request->input('brandCompanies')) {

            foreach ($brands_array as $brand) {
                $brand_c = json_decode($brand, true);
                $db_brand = Brand::find($brand_c['id']);

//                if ($db_brand->companies_id !== $company->id)
//                    continue;

                CreditsBrand::updateOrCreate([
                    'brands_id' => $brand_c['id'],
                    'companies_id' => $brand_c['companies_id'],
                    'credits_id' => $credit->id,
                    'gafa_fit' => true
                ]);


                $saved[] = $brand_c;

            }
        }
    }

}
