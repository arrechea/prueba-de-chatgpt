<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogBrand;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Currency\Currencies;
use App\Models\Language\Language;
use App\Http\Requests\AdminRequest as Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingsBController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($brand) {
            if (!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                return abort(404);

            $company =$this->getCompany();
            $brandId = $request->route('brand');

            if ($brandId instanceof Brand) {
                $brand = $brandId;
            } else {
                $brand = Brand::where('id', $brandId)->first();
            }


            if ($brand->company->id !== $company->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next ($request);
        })->only([
            'saveBrand',
        ]);

        $this->middleware(function ($request, $next) use ($brand) {

            $company =$this->getCompany();
            $brandId = $request->route('brand');

            if ($brandId instanceof Brand) {
                $brand = $brandId;
            } else {
                $brand = Brand::where('id', $brandId)->first();
            }


            if ($brand->company->id !== $company->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_DELETE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next ($request);
        })->only([
            'delete',
            'deletePost',
        ]);

    }

    public function index()
    {
        $company =$this->getCompany();
        $brand = $this->getBrand();
        $urlForm = route('admin.company.brand.settings.save.brand', ['company'=>$company, 'brand' => $brand]);

        return VistasGafaFit::view('admin.brand.settings.index', [
            'brandToEdit' => $brand,
            'urlForm'     => $urlForm,
//            'countries'      => Countries::all(),
            'currencies'     => Currencies::all(),
            'languages'      => Language::all(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveBrand(Request $request,Company $company, Brand $brand)
    {
//        $request->merge([
//            'status' => 'on',
//        ]);
        CatalogFacade::save($request, CatalogBrand::class);

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param Brand   $brand
     * @param Brand   $brandToEdit
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request,Company $company, Brand $brand, int $id)
    {
        $brandToEdit = Brand::find($id);

        return VistasGafaFit::view('admin.brand.settings.delete', [
            'brandToEdit' => $brandToEdit,
        ]);
    }


    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Brand   $brandToEdit
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand)
    {
        $brands = auth()->user()->companies;
        $id = (int)$request->get('id');

        if ($id === $brand->id) {
            CatalogFacade::delete($request, CatalogBrand::class);


            $first_comp = $brands->where('id', '<>', $id)->first();
            if ($first_comp !== null)
                return redirect()->route('admin.company.dashboard', ['company' => $first_comp]);
            else {

                return redirect()->route('admin.company.dashboard',['company' => $company]);
            }
        } else {
            CatalogFacade::delete($request, CatalogBrand::class);

            return redirect()->back();
        }
    }


}
