<?php

namespace App\Http\Controllers\Admin\Company;

use App\Admin;
use App\Http\Controllers\Admin\Company\AdministratorController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Librerias\Catalog\Tables\Company\CatalogCompany;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannotAccessTo($user, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'index',
        ]);
    }

    public function dashboard(Request $request, Company $company)
    {
        $user = auth()->user();

        $user_has_saas_role = false;
        foreach ($user->roles as $role) {
            if ($role->name == 'gafa-saas') {
                $user_has_saas_role = true;
                break;
            }
        }

        if ($user_has_saas_role) {
            return $this->renderSaasDashboard($request, $company);
        } else {
            return $this->renderClassicCompanyDashboard($request, $company);
        }
    }

    public function website(Request $request, Company $company)
    {
        $site = null;

        try {
            $token = [
                'sub' => \Auth::user()->email,
            ];

            $jwt = JWT::encode($token, config('app.jwt_secret'));

            $client = new Client();

            $response = $client->get(env('UCRAFT_JWT_API_URL') . '/sites?token=' . $jwt);
            $response_array = json_decode((string)$response->getBody(), true);
            if (isset($response_array[0]) && isset($response_array[0]['site'])) {
                $site = $response_array[0]['site'];
            }
        } catch (ConnectException $e) {
            Log::error($e);
        } catch (ClientException $e) {
            Log::error($e);
        }

        return VistasGafaFit::view('admin.company.website', [
            'site' => $site,
        ]);
    }

    public function ucraftProfile(Request $request, Company $company)
    {
        return VistasGafaFit::view('admin.company.ucraft-profile', []);
    }

    public function renderClassicCompanyDashboard(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogBrand::class));
        }

        $catalogo = new CatalogBrand();

        return VistasGafaFit::view('admin.company.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function renderSaasDashboard(Request $request, Company $company)
    {
        $brand = $company->active_brands()->first();
        $locations = $brand->locations;

        return redirect()->route('admin.company.brand.locations.dashboard', ['company' => $company, 'brand' => $brand, 'location' => $locations[0]]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCompany::class));
        }

        $catalogo = new CatalogCompany();

        return VistasGafaFit::view('admin.company.companies.index', [
            'catalogo' => $catalogo,
        ]);
    }
}
