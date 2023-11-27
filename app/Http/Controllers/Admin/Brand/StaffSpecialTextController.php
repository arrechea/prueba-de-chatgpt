<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/06/2018
 * Time: 01:25 PM
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffSpecialText;
use App\Librerias\Catalog\Tables\Brand\CatalogStaffSpecialText;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaffSpecialTextController extends BrandLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_SPECIAL_TEXTS_CREATE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_SPECIAL_TEXTS_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);
        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::STAFF_SPECIAL_TEXTS_DELETE, $brand)) {
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
            $staff = \request()->route('staff');
            $special_texts = $staff->special_texts ? $staff->special_texts->pluck('id')->toArray() : [];
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
     * Editar un texto especial para este instructor. Regresa todos los textos asociados para el instructor.
     *
     * @param Request          $request
     * @param Company          $company
     * @param Brand            $brand
     * @param Staff            $staff
     * @param StaffSpecialText $text
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand, Staff $staff, StaffSpecialText $text)
    {
        CatalogFacade::save($request, CatalogStaffSpecialText::class);

        return (Staff::find($staff->id))->special_texts;
    }

    /**
     * Guardar un nuevo texto especial para este instructor. Regresa todos los textos asociados para el instructor.
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Staff   $staff
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Staff $staff)
    {
        CatalogFacade::save($request, CatalogStaffSpecialText::class);

        return $staff->special_texts;
    }

    /**
     * Borrar un texto especial para este instructor. Regresa todos los textos asociados para el instructor.
     *
     * @param Request          $request
     * @param Company          $company
     * @param Brand            $brand
     * @param Staff            $staff
     * @param StaffSpecialText $text
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, Company $company, Brand $brand, Staff $staff, StaffSpecialText $text)
    {
        CatalogFacade::delete($request, CatalogStaffSpecialText::class);

        return (Staff::find($staff->id))->special_texts;
    }
}
