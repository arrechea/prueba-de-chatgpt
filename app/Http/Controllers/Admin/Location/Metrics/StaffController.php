<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/07/2018
 * Time: 03:15 PM
 */

namespace App\Http\Controllers\Admin\Location\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogStaffMetrics;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Http\Requests\AdminRequest as Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaffController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();
        $this->middleware(function($request, $next) use ($location){
            $user = auth()->user();
            if(LibPermissions::userCannot($user, LibListPermissions::METRICS_VIEW, $location)){
                throw new NotFoundHttpException();
            }
            return $next($request);
        });


    }

    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogStaffMetrics::class));
        }

        $catalogo = new CatalogStaffMetrics();
        return VistasGafaFit::view('admin.location.metrics.staff',[
            'catalogo'=> $catalogo
        ]);
    }

}
