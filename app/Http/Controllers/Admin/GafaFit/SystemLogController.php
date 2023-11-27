<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 26/09/2018
 * Time: 12:28 PM
 */

namespace App\Http\Controllers\Admin\GafaFit;


use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\SystemLog\CatalogSystemLog;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class SystemLogController extends AdminController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_VIEW)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    /**
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSystemLog::class));
        }

        return VistasGafaFit::view('admin.gafafit.system-log.index', [
            'catalogo' => new CatalogSystemLog(),
        ]);
    }

    /**
     * Devuelve la vista de parámetros. Esta hace un dd del json de parámetros
     *
     * @param Request $request
     * @param int     $log
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function parameters(Request $request, int $log)
    {
        $system_log = DB::table('system_logs')->find($log);
        $parameters = json_decode($system_log->parameters, false);

        return VistasGafaFit::view('admin.gafafit.system-log.parameters', [
            'parameters' => $parameters,
        ]);
    }
}
