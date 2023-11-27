<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogStaff;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Staff\Staff;
use App\Http\Requests\AdminRequest as Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaffController extends BrandLevelController
{
    /**
     * StaffController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_VIEW, $Brands)) {
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
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);

                if (\request()->has('order') && \request()->get('order') === null) {
                    $req = \request()->all();
                    unset($req['order']);
                    \request()->replace($req);
                }
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_CREATE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $staff = \request()->route('staff');
            if (!$staff || !$staff->brands->contains('id', \request()->route('brand')->id)) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);

                if (\request()->has('order') && \request()->get('order') === null) {
                    $req = \request()->all();
                    unset($req['order']);
                    \request()->replace($req);
                }
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {
            $staff = \request()->route('staff');
            if (!$staff || !$staff->brands->contains('id', \request()->route('brand')->id)) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_DELETE, $Brands)) {
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
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogStaff::class));

        }
        $catalogo = new CatalogStaff();

        return VistasGafaFit::view('admin.brand.staff.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function edit(Request $request, Company $company, Brand $brand, Staff $staff)
    {

        if ($staff === null)
            return abort(404);

        $special_texts = $staff->special_texts;

        return VistasGafaFit::view('admin.brand.staff.edit.index', [
            'staff'         => $staff,
            'urlForm'       => route('admin.company.brand.staff.save', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
                'staff'   => $staff,
            ]),
//            'countries'     => Countries::all(),
            'special_texts' => $special_texts,
        ]);
    }

    public function create(Request $request, Company $company, Brand $brand)
    {

        return VistasGafaFit::view('admin.brand.staff.create.index', [
            'urlForm'   => route('admin.company.brand.staff.save.new', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
            ]),
//            'countries' => Countries::all(),
        ]);
    }

    public function delete(Request $request, Company $company, Brand $brand, Staff $staff)
    {

        return VistasGafaFit::view('admin.brand.staff.edit.delete', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
            'staff'   => $staff,
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

        $nuevo = CatalogFacade::save($request, CatalogStaff::class);

        return redirect()->route('admin.company.brand.staff.edit', [
            'company' => $company,
            'brand'   => $brand,
            'staff'   => $nuevo,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Staff   $staff
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Staff $staff)
    {

        CatalogFacade::save($request, CatalogStaff::class);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Staff   $staff
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, Staff $staff)
    {

        CatalogFacade::delete($request, CatalogStaff::class);

        return redirect()->route('admin.company.brand.staff.index', [
            'company' => $company,
            'brand'   => $brand,
            'staff'   => $staff,
        ]);

    }

    public function createSpecialText(Request $request,Company $company,Brand $brand, Staff $staff){

    }

}



