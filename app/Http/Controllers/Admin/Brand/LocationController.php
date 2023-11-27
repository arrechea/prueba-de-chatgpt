<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Librerias\Catalog\Tables\Brand\CatalogLocation;
use App\Librerias\Servicies\LibServices;
use App\Librerias\Vistas\VistasGafaFit;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Service;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Countries;
use App\Models\Currency\Currencies;
use App\Models\Language\Language;

class LocationController extends BrandLevelController
{
    private $location;

    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();
        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::LOCATION_VIEW, $Brands)) {
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

            $locationid = $request->route('LocationToEdit');

            if ($locationid instanceof Location) {
                $Locations = $locationid;
            } else {
                $Locations = Location::where('id', $locationid)->first();
            }


            if ($Locations->brand->id !== $Brands->id) {
                return abort(404);
            }

            $this->location = $Locations;

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::LOCATION_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
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
            if (LibPermissions::userCannot($user, LibListPermissions::LOCATION_CREATE, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($Brands) {

            $locationid = $request->route('LocationToEdit');

            if ($locationid instanceof Location) {
                $Locations = $locationid;
            } else {
                $Locations = Location::where('id', $locationid)->first();
            }

            if ($Locations->brand->id !== $Brands->id) {
                return abort(404);
            }

            $this->location = $Locations;

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::LOCATION_DELETE, $Brands)) {
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
     *
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogLocation::class));
        }
        $catalogo = new CatalogLocation();

        return VistasGafaFit::view('admin.brand.locations.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function edit(Request $request, Company $company, Brand $brand, int $id)
    {
        $LocationToEdit = $this->location;

        if ($LocationToEdit === null)
            return abort(404);

        $services = LibServices::getParentServices($brand);

        return VistasGafaFit::view('admin.brand.locations.edit.index', [
            'LocationToEdit' => $LocationToEdit,
            'urlForm'        => route('admin.company.brand.locations.save', [
                'company'        => $this->getCompany(),
                'brand'          => $this->getBrand(),
                'LocationToEdit' => $LocationToEdit->id,
            ]),
            'currencies'     => Currencies::all(),
            'languages'      => Language::all(),
            'services'       => $services,
        ]);

    }

    public function create(Request $request, Company $company, Brand $brand)
    {
        $services = LibServices::getParentServices($brand);

        return VistasGafaFit::view('admin.brand.locations.create.index', [
            'urlForm'    => route('admin.company.brand.locations.save.new', [
                'company' => $this->getCompany(),
//                'LocationToEdit' => $LocationToEdit,
                'brand'   => $this->getBrand(),
            ]),
            'currencies' => Currencies::all(),
            'languages'  => Language::all(),
            'services'   => $services,
        ]);
    }


    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Brand $brand, int $id)
    {
        return VistasGafaFit::view('admin.brand.locations.edit.delete', [
            'LocationToEdit' => $id,
            'brand'          => $this->getBrand(),
        ]);


    }

    /**
     * @param Request $request
     *
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogLocation::class);

        if ($nuevo->isActive())
            return redirect()->to($nuevo->link());
        else
            return redirect()->route('admin.company.brand.locations.index', [
                'company' => $company,
                'brand'   => $brand,
            ]);

    }

    /**
     * @param Request $request
     *
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, int $id)
    {
        CatalogFacade::save($request, CatalogLocation::class);

        return redirect()->back();
    }

    /**
     * @param Request $request
     *
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, int $id)
    {
        CatalogFacade::delete($request, CatalogLocation::class);

        return redirect()->back();
    }
}
