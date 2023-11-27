<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 23/05/2018
 * Time: 01:36 PM
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogServiceSpecialText;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Service;
use App\Models\Service\ServiceSpecialText;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceSpecialTextController extends BrandLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::SERVICE_SPECIAL_TEXTS_CREATE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::SERVICE_SPECIAL_TEXTS_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::SERVICE_SPECIAL_TEXTS_DELETE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                return abort(404);

            return $next($request);
        })->only([
            'save',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            $id = \request()->get('id');
            $service = \request()->route('service');
            $special_texts = $service->specialTexts ? $service->specialTexts->pluck('id')->toArray() : [];
            if (!in_array($id, $special_texts)) {
                return abort(404);
            }

            return $next($request);
        })->only([
            'save',
            'delete',
        ]);
    }

    /**
     * @param Request            $request
     * @param Company            $company
     * @param Brand              $brand
     * @param Service            $service
     * @param ServiceSpecialText $text
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand, Service $service, ServiceSpecialText $text)
    {
        CatalogFacade::save($request, CatalogServiceSpecialText::class);

        return (Service::find($service->id))->specialTexts;
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Service $service
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Service $service)
    {
        CatalogFacade::save($request, CatalogServiceSpecialText::class);

        return $service->specialTexts;
    }

    /**
     * @param Request            $request
     * @param Company            $company
     * @param Brand              $brand
     * @param Service            $service
     * @param ServiceSpecialText $text
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, Company $company, Brand $brand, Service $service, ServiceSpecialText $text)
    {
        CatalogFacade::delete($request, CatalogServiceSpecialText::class);

        return (Service::find($service->id))->specialTexts;
    }
}
