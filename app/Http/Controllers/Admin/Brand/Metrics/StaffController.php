<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/07/2018
 * Time: 03:15 PM
 */

namespace App\Http\Controllers\Admin\Brand\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogStaffMetrics;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaffController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = $this->getBrand();
        $this->middleware(function($request, $next) use ($brand){
            $user = auth()->user();
            if(LibPermissions::userCannot($user, LibListPermissions::METRICS_VIEW, $brand)){
                throw new NotFoundHttpException();
            }
            return $next($request);
        });


    }

    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogStaffMetrics::class));
        }

        $catalogo = new CatalogStaffMetrics();
        return VistasGafaFit::view('admin.brand.metrics.staff.staff',[
            'catalogo'=> $catalogo
        ]);
    }

}
