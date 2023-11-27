<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 23/11/2018
 * Time: 02:27 PM
 */

namespace App\Http\Controllers\Admin\Company\SpecialText;


use App\Admin;
use App\Http\Controllers\Admin\Company\CompanyLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogSpecialTextsCatalogGroup;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Company\Company;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Librerias\Catalog\Tables\Company\CatalogGroupsCatalogs;
use App\Http\Requests\AdminRequest as Request;

class SpecialTextController extends CompanyLevelController
{

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
    }

    public function index(Request $request, Company $company)
    {
        return redirect()->route('admin.company.special-text.group.index', [
            'company' => $company,
        ]);
    }
}
