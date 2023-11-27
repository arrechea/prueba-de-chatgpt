<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogCombo;
use App\Librerias\Credits\LibCredits;
use App\Librerias\LibCombos;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Servicies\LibServices;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditsBrand;
use App\Models\User\UserCategory;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CombosController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMBOS_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($Brands) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $brands_id = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'brands_id';
                    })->first()['value'] ?? 0;

                if ($brands_id !== \request()->route('brand')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id
                )
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMBOS_CREATE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $combo = \request()->route('combos');
            if (!$combo || $combo->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id
                )
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMBOS_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $combo = \request()->route('combos');
            if (!$combo || $combo->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMBOS_DELETE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $combo = \request()->route('combos');
            if (!$combo || $combo->brands_id != \request()->route('brand')->id) {
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
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCombo::class));

        }
        $catalogo = new CatalogCombo();

        return VistasGafaFit::view('admin.brand.marketing.combos.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function indexSaas(Request $request, Company $company, Brand $brand)
    {

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCombo::class));

        }
        $catalogo = new CatalogCombo();

        return VistasGafaFit::view('admin.brand.marketing.combos.index-saas', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Combos  $combos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand, Combos $combos)
    {
        $credits_c = CreditsBrand::select('*')->where('brands_id', $brand->id)->get();

        $credits = LibCredits::getCreditsCompany($brand, $company, $credits_c);

        $creditsgf = LibCredits::getCreditsGafa($brand, $company, $credits_c);


        $credit_brands = LibCredits::getCredits($brand);

        return VistasGafaFit::view('admin.brand.marketing.combos.edit.index', [
            'combos'        => $combos,
            'urlForm'       => route('admin.company.brand.marketing.combos.save', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
                'combos'  => $combos->id,
            ]),
            'credits'       => $credits,
            'credit_brands' => $credit_brands,
            'creditsgf'     => $creditsgf,
            'categories'    => UserCategory::where('companies_id', $this->getCompany()->id)->get(),
        ]);
    }

    /**
     * @param $combos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand)
    {
        $credits_c = CreditsBrand::select('*')->where('brands_id', $brand->id)->get();

        $credits = LibCredits::getCreditsCompany($brand, $company, $credits_c);

        $creditsgf = LibCredits::getCreditsGafa($brand, $company, $credits_c);

        $credit_brands = LibCredits::getCredits($brand);

        return VistasGafaFit::view('admin.brand.marketing.combos.create.index', [
            'urlForm'       => route('admin.company.brand.marketing.combos.save.new', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
            ]),
            'credits'       => $credits,
            'credit_brands' => $credit_brands,
            'creditsgf'     => $creditsgf,
            'categories'    => UserCategory::where('companies_id', $this->getCompany()->id)->get(),
        ]);
    }

    /**
     * @param Company $company
     * @param Brand   $brand
     * @param int     $combos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Brand $brand, Combos $combos)
    {

        return VistasGafaFit::view('admin.brand.marketing.combos.delete', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
            'combos'  => $combos->id,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogCombo::class);


        return redirect()->route('admin.company.brand.marketing.combos.index',
            [
                'company' => $company,
                'brand'   => $brand,
            ]);

    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Combos $combos)
    {
        CatalogFacade::save($request, CatalogCombo::class);

        return redirect()->route('admin.company.brand.marketing.combos.index',
            [
                'company' => $company,
                'brand'   => $brand,
            ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, Combos $combos)
    {

        CatalogFacade::delete($request, CatalogCombo::class);

        return redirect()->route('admin.company.brand.marketing.combos.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }

//    public function services(Request $request, Company $company, Brand $brand, Combos $combos)
//    {
//        $services = LibServices::getParentServices($this->getBrand());
//
//        $combos_services = $combos->services()->pluck('services_id')->toArray();
//
//        return VistasGafaFit::view('admin.brand.marketing.combos.services', [
//            'services'        => $services ?? [],
//            'combos'          => $combos,
//            'combos_services' => $combos_services,
//        ]);
//    }
//
//    public function saveServices(Request $request, Company $company, Brand $brand, Combos $combos)
//    {
//        LibCombos::saveServices($request, $this->getCompany(), $this->getBrand(), $combos);
//    }
}
