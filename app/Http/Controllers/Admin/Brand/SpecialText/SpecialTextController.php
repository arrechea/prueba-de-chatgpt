<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 23/11/2018
 * Time: 02:27 PM
 */

namespace App\Http\Controllers\Admin\Brand\SpecialText;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\Tables\Company\CatalogSpecialTextsCatalogGroup;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class SpecialTextController extends BrandLevelController
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

    public function index(Request $request, Company $company, Brand $brand)
    {
        return redirect()->route('admin.company.brand.special-text.group.index', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }
}
