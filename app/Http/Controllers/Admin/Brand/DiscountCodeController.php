<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/09/2018
 * Time: 05:03 PM
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogDiscountCode;
use App\Librerias\Credits\LibCredits;
use App\Librerias\DiscountCode\LibDiscountCode;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditsBrand;
use App\Models\DiscountCode\DiscountCode;
use App\Models\User\UserCategory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DiscountCodeController extends BrandLevelController
{
    /**
     * DiscountCodeController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::DISCOUNT_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($Brands) {
            if ($request->ajax()) {
                $brands_id = LibFilters::getFilterValue('brands_id');
                if (!$brands_id || (int)$brands_id !== (int)$Brands->id) {
                    throw new NotFoundHttpException();
                }
            }

            return $next($request);
        })->only([
            'index',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::DISCOUNT_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveEdit',
            'edit',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::DISCOUNT_CREATE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'save',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            if ($request->method() === 'POST') {
                if (!\request()->has('brands_id') || (int)\request()->get('brands_id') !== $this->getBrand()->id ||
                    !\request()->has('companies_id') || (int)\request()->get('companies_id') !== $this->getCompany()->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'saveEdit',
            'save',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::DISCOUNT_DELETE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogDiscountCode::class));
        }

        $catalog = new CatalogDiscountCode();

        return VistasGafaFit::view('admin.brand.DiscountCode.index', [
            'catalogo' => $catalog,
        ]);
    }

    public function indexSaas(Request $request, Company $company, Brand $brand)
    {

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogDiscountCode::class));
        }

        $catalog = new CatalogDiscountCode();

        return VistasGafaFit::view('admin.brand.DiscountCode.index-saas', [
            'catalogo' => $catalog,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand)
    {
        $urlForm = route('admin.company.brand.discount-code.save', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        return VistasGafaFit::view('admin.brand.DiscountCode.create.index', [
            'urlForm' => $urlForm,
            'categories'    => UserCategory::where('companies_id', $this->getCompany()->id)->get()
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param DiscountCode $discountCode
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        $urlForm = route('admin.company.brand.discount-code.save.edit', [
            'company'      => $company,
            'brand'        => $brand,
            'discountCode' => $discountCode,
        ]);

        return VistasGafaFit::view('admin.brand.DiscountCode.edit.index', [
            'discountCode' => $discountCode,
            'urlForm'      => $urlForm,
            'categories'    => UserCategory::where('companies_id', $this->getCompany()->id)->get()
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param DiscountCode $discountCode
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        return VistasGafaFit::view('admin.brand.DiscountCode.edit.delete', [
            'company'      => $company,
            'brand'        => $brand,
            'discountCode' => $discountCode,
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
    public function save(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogDiscountCode::class);

        return redirect()->route('admin.company.brand.discount-code.edit', [
            'company'      => $company,
            'brand'        => $brand,
            'discountCode' => $nuevo->id,
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param DiscountCode $discountCode
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        //dd($request->toArray());

        CatalogFacade::save($request, CatalogDiscountCode::class);

        return redirect()->back();
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param DiscountCode $discountCode
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        //dd('aaca');
        CatalogFacade::delete($request, CatalogDiscountCode::class);

        return redirect()->route('admin.company.brand.discount-code.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param DiscountCode $discountCode
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function credits(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        $credits_c = CreditsBrand::select('*')->where('brands_id', $brand->id)->get();
        $credits = LibCredits::getCreditsCompany($brand,$company,$credits_c);
        $creditsgf = LibCredits::getCreditsGafa($brand, $company, $credits_c);
        $creditsBrand = LibCredits::getCredits($brand);

        $discount_credits = $discountCode->credits;

        return VistasGafaFit::view('admin.brand.DiscountCode.edit.credits', [
            'credits'          => $credits ?? [],
            'creditsBrand'     => $creditsBrand,
            'credits_c'        => $credits_c,
            'creditsgf' => $creditsgf,
            'discountCode'     => $discountCode,
            'discount_credits' => $discount_credits,
            'company'          => $company
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param DiscountCode $discountCode
     */
    public function creditsSave(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        LibDiscountCode::saveCredits($request, $company, $brand, $discountCode);
    }
}
