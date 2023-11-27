<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 23/04/2018
 * Time: 11:36 AM
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogService;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Service;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ServicesController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SERVICES_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($brand) {
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
        $this->middleware(function ($request, $next) use ($brand) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $service = \request()->route('service');
            if (!$service || $service->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SERVICES_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SERVICES_CREATE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            $service = \request()->route('service');
            if (!$service || $service->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SERVICES_DELETE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->middleware(function ($request, $next) use ($brand) {
            if (!$request->has('order') || !$request->input('order')) {
                $request->merge([
                    'order' => 0,
                ]);
            }

            return $next($request);
        })->only([
            'saveNew',
            'saveEdit',
            'saveChild',
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
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogService::class));
        }

        $catalogo = new CatalogService();

        return VistasGafaFit::view('admin.brand.services.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Service $service
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand, Service $service)
    {
        $child_services = $service->childServices()->whereNull('deleted_at')->orderBy('order')->get();

        $special_texts = $service->specialTexts()->orderBy('order')->get();

        return VistasGafaFit::view('admin.brand.services.edit.index', [
            'service'        => $service,
            'urlForm'        => route('admin.company.brand.services.save', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
                'service' => $service,
            ]),
            'child_services' => $child_services,
            'parent_id'      => $service->parent_id,
            'special_texts'  => $special_texts,
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
        $urlForm = route('admin.company.brand.services.save.new', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
        ]);

        return VistasGafaFit::view('admin.brand.services.create.index', [
            'urlForm' => $urlForm,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Service $parent
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createChild(Request $request, Company $company, Brand $brand, Service $parent)
    {
        $urlForm = route('admin.company.brand.services.save.new', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
        ]);

        return VistasGafaFit::view('admin.brand.services.create.child', [
            'urlForm'   => $urlForm,
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Service $service)
    {
        CatalogFacade::save($request, CatalogService::class);

        return redirect()->back();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogService::class);

        return redirect()->route('admin.company.brand.services.edit', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
            'service' => $nuevo,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveChild(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogService::class);
        $nuevo->edit_url = route('admin.company.brand.services.edit', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
            'service' => $nuevo,
        ]);
        $nuevo->delete_url = route('admin.company.brand.services.delete.post', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
            'service' => $nuevo,
        ]);

        return $nuevo;
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Service $service
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Brand $brand, Service $service)
    {
        return VistasGafaFit::view('admin.brand.services.delete', [
            'id' => $service->id,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Service $service
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, Service $service)
    {
        CatalogFacade::delete($request, CatalogService::class);

        if ($request->ajax()) {
            return null;
        } else {
            return redirect()->route('admin.company.brand.services.index', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
            ]);
        }
    }
}
