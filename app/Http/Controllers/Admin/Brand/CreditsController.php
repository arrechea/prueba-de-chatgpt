<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/05/2018
 * Time: 09:59 AM
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Catalogcredit;
use App\Librerias\Catalog\Tables\Brand\CatalogCredits;
use App\Librerias\Catalog\Tables\Brand\CatalogLocation;
use App\Librerias\Credits\LibCredits;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Servicies\LibServices;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class CreditsController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CREDITS_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($Brands) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $brands_id = (int)LibFilters::getFilterValue('brands_id', $request);

                if ($brands_id !== \request()->route('brand')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CREDITS_CREATE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {

            $credit = $this->getCredits($request);
            if (!$credit || $credit->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CREDITS_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveCredit',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {

            $credit = $this->getCredits($request);
            if (!$credit || $credit->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CREDITS_DELETE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $credit = \request()->route('credit');
            if (!$credit || $credit->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMBOS_DELETE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'services',
            'saveServices',
        ]);
    }

    /**
     * @param $request
     *
     * @return Credit
     */
    private function getCredits($request)
    {
        $creditId = $request->route('credit');

        if ($creditId instanceof Credit) {
            $credit = $creditId;
        } else {
            $credit = Credit::find($creditId);
        }

        return $credit;
    }

    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCredits::class));

        }
        $catalogo = new CatalogCredits();

        return VistasGafaFit::view('admin.brand.credits.index', [
            'catalogo' => $catalogo,
        ]);

    }

    public function edit(Request $request, Company $company, Brand $brand, Credit $credit)
    {
        if ($credit === null) {
            return abort(404);
        }

        $get_brand = $this->getBrand();
        $locations = $get_brand->locations;

        return VistasGafaFit::view('admin.brand.credits.edit.index', [
            'credit'  => $credit,
            'urlForm' => route('admin.company.brand.credits.save.credit', [
                'company'   => $this->getCompany(),
                'brand'     => $get_brand,
                'credit'    => $credit->id,
                'locations' => $locations,
            ]),

        ]);
    }

    public function create(Request $request, Company $company, Brand $brand)
    {
        $get_brand = $this->getBrand();
        $locations = $get_brand->locations;

        return VistasGafaFit::view('admin.brand.credits.create.index', [
            'urlForm' => route('admin.company.brand.credits.save.new', [
                'company'   => $this->getCompany(),
                'brand'     => $get_brand,
                'locations' => $locations,
            ]),
        ]);
    }

    public function delete(Request $request, Company $company, Brand $brand, Credit $credit)
    {
        return VistasGafaFit::view('admin.brand.credits.edit.delete', [
            'company' => $company,
            'brand'   => $brand,
            'credit'  => $credit,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        if ($request->get('level') !== 'location') {
            $request->merge(['locations_id', null]);
        }
        $nuevo = CatalogFacade::save($request, CatalogCredits::class);

        return redirect()->route('admin.company.brand.credits.edit', [
            'company' => $company,
            'brand'   => $brand,
            'credit'  => $nuevo->id,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Credit  $credit
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveCredit(Request $request, Company $company, Brand $brand, Credit $credit)
    {
        CatalogFacade::save($request, CatalogCredits::class);

        return redirect()->back();

    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand)
    {
        CatalogFacade::delete($request, CatalogCredits::class);

        return redirect()->route('admin.company.brand.credits.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);

    }

    public function services(Request $request, Company $company, Brand $brand, Credit $credit)
    {
        $services = LibServices::getParentServiceswithChilds($this->getBrand());
        $credits_services = $credit->services;

        return VistasGafaFit::view('admin.brand.credits.services', [
            'services'         => $services ?? [],
            'combos'           => $credit,
            'credits_services' => $credits_services,
        ]);
    }

    public function saveServices(Request $request, Company $company, Brand $brand, Credit $credit)
    {
        LibCredits::saveServices($request, $this->getCompany(), $this->getBrand(), $credit);
    }
}
