<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 23/07/2018
 * Time: 04:08 PM
 */

namespace App\Http\Controllers\Admin\Brand\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogRoom;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogNewUsers;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogTopUser;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersMetricsController extends BrandLevelController
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

    /**
     * Vista de las métricas de usuarios para brand
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoom::class));
        }

        return VistasGafaFit::view('admin.brand.metrics.users.chart', [
            'catalogs' => [
                'new_users' => [
                    'title'    => __('metrics.new-users'),
                    'catalogo' => new CatalogNewUsers(),
                    'url'      => route('admin.company.brand.metrics.users.new', [
                        'company'  => $company,
                        'brand'    => $brand,
                    ]),
                ],
                'top_users' => [
                    'title'    => __('metrics.top-users'),
                    'catalogo' => new CatalogTopUser(),
                    'url'      => route('admin.company.brand.metrics.users.top', [
                        'company'  => $company,
                        'brand'    => $brand,
                    ]),
                ],
            ],
        ]);
    }

    /**
     * Regresa los usuarios nuevos.
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnUsers(Request $request, Company $company, Brand $brand)
    {
        return response()->json(CatalogFacade::dataTableIndex($request, CatalogNewUsers::class));
    }

    /**
     * Regresa los usuarios por reservaciones.
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topUsers(Request $request, Company $company, Brand $brand)
    {
        return response()->json(CatalogFacade::dataTableIndex($request, CatalogTopUser::class));
    }
}
