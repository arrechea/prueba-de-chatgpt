<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 29/11/2018
 * Time: 11:43
 */

namespace App\Http\Controllers\Admin\Brand\SpecialText;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\SpecialText\CatalogFieldControl;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\SpecialText\LibCatalogFieldControl;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\CatalogGroup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogControlController extends BrandLevelController
{
    private $group;

    public function __construct(Admin $admin)
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

        $this->middleware(function ($request, $next) {
            $group_id = $request->route('group');
            $group = CatalogGroup::find($group_id);
            if (!$group) {
                throw new NotFoundHttpException();
            }
            $this->group = $group;

            if (!$request->has('activate') || !$request->has('section')) {
                abort(403);
            }

            return $next($request);
        })->only([
            'create',
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogFieldControl::class));
        }

        $brands_id = $request->get('brands_id', 0);

        return VistasGafaFit::view('admin.company.special-texts.control-panel.index', [
            'selected_brand' => (int)$brands_id,
            'catalogo'       => new CatalogFieldControl(),
        ]);
    }

    public function create(Request $request, $group)
    {
        LibCatalogFieldControl::create($request, $this->group);
    }
}
