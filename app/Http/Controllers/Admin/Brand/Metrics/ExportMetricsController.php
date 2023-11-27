<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 5/14/2019
 * Time: 18:08
 */

namespace App\Http\Controllers\Admin\Brand\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics\activeUsersCombos;
use App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics\activeUsersMemberships;
use App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics\AllUsersController;
use App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics\UsersbyMonth;
use App\Librerias\Metrics\ExportMetrics\LibExportMetrics;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\User\ProfileTrait;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportMetricsController extends BrandLevelController
{


    public function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::EXPORT_METRICS, $brand)) {
                throw new NotFoundHttpException();
            }
            return $next($request);
        });
    }

    public function index(Request $request, Company $company, Brand $brand)
    {

        if ($request->ajax()) {
            return response();

        }

        return VistasGafaFit::view('admin.brand.metrics.export.index', [
            'company' => $company,
            'brand' => $brand
        ]);
    }

    public function Combosexport(Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.metrics.export.exporCombos', [
            'company' => $company,
            'brand' => $brand
        ]);
    }

    public function MembershipExport(Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.metrics.export.exportMembership', [
            'company' => $company,
            'brand' => $brand
        ]);

    }

    public function usersByMonth(Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.metrics.export.exportUserByMonth', [
            'company' => $company,
            'brand' => $brand
        ]);
    }

    public function export(Request $request, Company $company, Brand $brand , $start_date, $end_date)
    {
        $now = (Carbon::now());

        return (new AllUsersController($company,$start_date, $end_date))->download('Usuarios.xlsx');

    }

    public function exportCombos(Request $request, Company $company, Brand $brand, $start_date, $end_date)
    {
        $now = (Carbon::now());

        return (new activeUsersCombos($now, $company, $start_date, $end_date))->download('Combos.xlsx');

    }

    public function exportMembership(Request $request,Company $company, Brand $brand, $start_date, $end_date)
    {
        $now = (Carbon::now());

        return (new activeUsersMemberships($now, $company, $start_date, $end_date))->download('Membership.xlsx');

    }

    public function exportMonthly(Request $request,Company $company, Brand $brand, $start_date, $end_date)
    {

        $now = (Carbon::now());

        return (new UsersbyMonth($company,$start_date, $end_date))->download('Fechas_de_nacimiento_Edades.xlsx');

    }

}
