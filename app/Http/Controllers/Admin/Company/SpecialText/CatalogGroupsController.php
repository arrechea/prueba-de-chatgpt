<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 26/11/2018
 * Time: 12:20 PM
 */

namespace App\Http\Controllers\Admin\Company\SpecialText;


use App\Admin;
use App\Http\Controllers\Admin\Company\CompanyLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\SpecialText\CatalogSpecialTextsCatalogGroup;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Company\Company;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogGroupsController extends CompanyLevelController
{
    private $group;

    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SPECIAL_TEXT_VIEW, null)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($company) {
            if ($request->ajax()) {
                $filters = $request->get('filters', []);
                $filters[] = [
                    'name'  => 'companies_id',
                    'value' => $company->id,
                ];
                $request->merge([
                    'filters' => $filters,
                ]);
            }

            return $next($request);
        })->only([
            'index',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $request->merge([
                'companies_id' => $company->id,
            ]);

            if ($request->has('shared')) {
                $req = $request->all();
                unset($req['brands_id']);
                $request->replace($req);
            } else {
                $brands_id = $request->get('brands_id', null);
                if ($brands_id) {
                    $brand_ids = $company->brands->pluck('id')->values()->toArray();
                    if (!in_array($brands_id, $brand_ids)) {
                        throw new NotFoundHttpException();
                    }
                } else {
                    throw new NotFoundHttpException();
                }
            }

            $catalogs_id = $request->get('catalogs_id', null);

            if (!$catalogs_id) {
                abort(403);
            }


            return $next($request);
        })->only([
            'save',
            'saveNew',
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
            'edit',
            'save',
        ]);
    }

    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSpecialTextsCatalogGroup::class));
        }

        $brands_id = $request->get('brands_id', 0);

        return VistasGafaFit::view('admin.company.special-texts.catalog-groups.index', [
            'catalogo'       => new CatalogSpecialTextsCatalogGroup(),
            'selected_brand' => (int)$brands_id,
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param CatalogGroup $group
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, int $group)
    {
        return VistasGafaFit::view('admin.company.special-texts.catalog-groups.form', [
            'groupCatalog' => $this->group,
            'form_action'  => route('admin.company.special-text.group.save', [
                'company' => $company,
                'group'   => $group,
            ]),
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     *
     * @param CatalogGroup $group
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, int $group)
    {
        $nuevo = CatalogFacade::save($request, CatalogSpecialTextsCatalogGroup::class);

        return $nuevo;
    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new(Request $request, Company $company)
    {
        $catalogs_id = $request->get('catalogs_id');
        $brands_id = $request->get('brands_id');

        return VistasGafaFit::view('admin.company.special-texts.catalog-groups.form', [
            'form_action' => route('admin.company.special-text.group.save.new', [
                'company' => $company,
            ]),
            'catalogs_id' => $catalogs_id,
            'brands_id'   => $brands_id,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company)
    {
        $nuevo = CatalogFacade::save($request, CatalogSpecialTextsCatalogGroup::class);

        return $nuevo;
    }

}
