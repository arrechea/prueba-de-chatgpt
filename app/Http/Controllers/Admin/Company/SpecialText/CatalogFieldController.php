<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/11/2018
 * Time: 09:55
 */

namespace App\Http\Controllers\Admin\Company\SpecialText;


use App\Admin;
use App\Http\Controllers\Admin\Company\CompanyLevelController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\SpecialText\CatalogFieldsCatalog;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\CatalogField;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Company\Company;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogFieldController extends CompanyLevelController
{
    private $group;
    private $field;

    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SPECIAL_TEXT_VIEW, null)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) {
            $field_id = $request->route('field');
            $field = CatalogField::find($field_id);
            if (!$field) {
                throw new NotFoundHttpException();
            }
            $this->field = $field;

            return $next($request);
        })->only([
            'edit',
            'save',
            'activate',
        ]);

        $this->middleware(function ($request, $next) {
            $group_id = $request->route('group');
            $group = CatalogGroup::find($group_id);
            if (!$group) {
                throw new NotFoundHttpException();
            }
            $this->group = $group;

            return $next($request);
        })->only([
            'index',
            'saveNew',
            'create',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $request->merge([
                'catalogs_groups_id' => $this->group->id,
                'catalogs_id'        => $this->group->catalogs_id,
//                'brands_id'          => $this->group->brands_id,
                'companies_id'       => $company->id,
            ]);

            return $next($request);
        })->only([
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $request->merge([
                'catalogs_groups_id' => $this->field->catalogs_groups_id,
                'catalogs_id'        => $this->field->catalogs_id,
//                'brands_id'          => $this->field->brands_id,
                'companies_id'       => $company->id,
            ]);

            return $next($request);
        })->only([
            'save',
        ]);
    }

    public function index(Request $request, Company $company, int $group)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogFieldsCatalog::class));
        }

        return VistasGafaFit::view('admin.company.special-texts.catalog-fields.index', [
            'group'    => $this->group,
            'catalogo' => new CatalogFieldsCatalog(),
        ]);
    }

    public function edit(Request $request, Company $company, int $field)
    {
        return VistasGafaFit::view('admin.company.special-texts.catalog-fields.form', [
            'form_action' => route('admin.company.special-text.field.save', [
                'company' => $company,
                'field'   => $field,
            ]),
            'field'       => $this->field,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param int     $field
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, int $field)
    {
        $saved = CatalogFacade::save($request, CatalogFieldsCatalog::class);

        return $saved;
    }

    public function create(Request $request, Company $company, int $group)
    {
        return VistasGafaFit::view('admin.company.special-texts.catalog-fields.form', [
            'form_action' => route('admin.company.special-text.field.save.new', [
                'company' => $company,
                'group'   => $group,
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param int     $group
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, int $group)
    {
//        dd($request->all());
        $new = CatalogFacade::save($request, CatalogFieldsCatalog::class);

        return $new;
    }

    public function activate(Request $request, Company $company, int $field)
    {
        $cat_field = $this->field;
        $cat_field->activate();

        return response()->json((string)$cat_field->isActive());
    }
}
