<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/05/2018
 * Time: 12:12 PM
 */

namespace App\Http\Controllers\Admin\Brand\Marketing;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogMembership;
use App\Librerias\Credits\LibCredits;
use App\Librerias\Errores\LibErrores;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\CreditsBrand;
use App\Models\Membership\Membership;
use App\Models\User\UserCategory;
use App\Models\User\UsersMemberships;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class MembershipController extends BrandLevelController
{

    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();


        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEMBERSHIP_VIEW, $Brands)) {
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
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEMBERSHIP_CREATE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $membership = \request()->route('membership');
            if (!$membership || $membership->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEMBERSHIP_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $membership = \request()->route('membership');
            if (!$membership || $membership->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEMBERSHIP_DELETE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
    }

    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogMembership::class));
        }
        $catalogo = new CatalogMembership();

        return VistasGafaFit::view('admin.brand.marketing.membership.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function indexSaas(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogMembership::class));
        }
        $catalogo = new CatalogMembership();

        return VistasGafaFit::view('admin.brand.marketing.membership.index-saas', [
            'catalogo' => $catalogo,
        ]);
    }

    public function edit(Request $request, Company $company, Brand $brand, Membership $membership)
    {
        if ($membership === null)
            return abort(404);

        $credits_c = CreditsBrand::select('*')->where('brands_id', $brand->id)->get();

        $credits = LibCredits::getCreditsCompany($brand, $company, $credits_c);

        $creditsgf = LibCredits::getCreditsGafa($brand, $company, $credits_c);

        $creditMembership = LibCredits::getCredits($brand);

        return VistasGafaFit::view('admin.brand.marketing.membership.edit.index', [
            'membership'       => $membership,
            'urlForm'          => route('admin.company.brand.marketing.membership.save.edit', [
                'company'    => $this->getCompany(),
                'brand'      => $this->getBrand(),
                'membership' => $membership->id,
            ]),
            'credits'          => $credits,
            'creditMembership' => $creditMembership,
            'creditsgf'        => $creditsgf,
            'categories'       => UserCategory::where('companies_id', $this->getCompany()->id)->get(),
        ]);
    }

    public function create(Request $request, Company $company, Brand $brand)
    {
        $credits_c = CreditsBrand::select('*')->where('brands_id', $brand->id)->get();

        $credits = LibCredits::getCreditsCompany($brand, $company, $credits_c);

        $creditsgf = LibCredits::getCreditsGafa($brand, $company, $credits_c);

        $creditMembership = LibCredits::getCredits($brand);

        return VistasGafaFit::view('admin.brand.marketing.membership.create.index', [
            'urlForm'          => route('admin.company.brand.marketing.membership.save.new', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
            ]),
            'credits'          => $credits,
            'creditMembership' => $creditMembership,
            'creditsgf'        => $creditsgf,
            'categories'       => UserCategory::where('companies_id', $this->getCompany()->id)->get(),
        ]);

    }

    public function delete(Request $request, Company $company, Brand $brand, Membership $membership)
    {
        return VistasGafaFit::view('admin.brand.marketing.membership.delete', [
            'company'    => $this->getCompany(),
            'brand'      => $this->getBrand(),
            'membership' => $membership->id,
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

        $nuevo = CatalogFacade::save($request, CatalogMembership::class);

        return redirect()->route('admin.company.brand.marketing.membership.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }

    /**
     * @param Request    $request
     * @param Company    $company
     * @param Brand      $brand
     * @param Membership $membership
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Membership $membership)
    {
        CatalogFacade::save($request, CatalogMembership::class);

        return redirect()->route('admin.company.brand.marketing.membership.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }

    /**
     * @param Request    $request
     * @param Company    $company
     * @param Brand      $brand
     * @param Membership $membership
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, Membership $membership)
    {
        CatalogFacade::delete($request, CatalogMembership::class);

        return redirect()->route('admin.company.brand.marketing.membership.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }

    /**
     * @param Request    $request
     * @param Company    $company
     * @param Brand      $brand
     * @param Membership $membership
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sync(Request $request, Company $company, Brand $brand, Membership $membership)
    {
        $count = 0;
        if ($membership->level === 'location') {
            UsersMemberships::where('memberships_id', $membership->id)
                ->with('purchase')
                ->get()
                ->each(function ($membership) use (&$count) {
                    if (isset($membership->purchase->locations_id)) {
                        if ($membership->locations_id != $membership->purchase->locations_id) {
                            $count++;
                            $membership->locations_id = $membership->purchase->locations_id;
                            $membership->save();
                        }
                    }
                });
        } else {
            $count = UsersMemberships::where('memberships_id', $membership->id)
                ->whereNotNull('locations_id')->count();
            UsersMemberships::where('memberships_id', $membership->id)
                ->update([
                    'locations_id' => null,
                ]);
        }

        LibErrores::lanzarMensajes(__('memberships.updated.count', [
            'count' => $count,
        ]));

        return redirect()->back();
    }

}
