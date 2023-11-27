<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 4/3/2019
 * Time: 15:52
 */

namespace App\Http\Controllers\Admin\Company;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogBrandsCredits;
use App\Librerias\Catalog\Tables\Company\CatalogCompanyCredits;
use App\Librerias\Credits\LibCredits;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Servicies\LibServices;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditsBrand;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class CompanyCreditsController extends CompanyLevelController
{

    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $companyCredits = $this->getCompany();
        $this->middleware(function ($request, $next) use ($companyCredits){

            $user = auth()->user();

            if (LibPermissions::userCannot($user, LibListPermissions::CREDITSCOMPANY_VIEW, $companyCredits)) {
                throw new NotFoundHttpException();
            }
            return $next($request);

        });
        $this->middleware(function ($request, $next) use ($companyCredits){
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $companies_id = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'companies_id';
                    })->first()['value'] ?? 0;

                if ($companies_id !== \request()->route('company')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);
        $this->middleware(function($request, $next) use ($companyCredits){

           if(\request()->method() === 'POST'){

               if(!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)

                   return abort(404);
           }
           $user = auth()->user();
           if(LibPermissions::userCannot($user, LibListPermissions::CREDITSCOMPANY_CREATE, $companyCredits)) {

               throw new NotFoundHttpException();
           }

           return $next($request);
        })->only([
            'create',
            'saveNew'
        ]);
        $this->middleware(function($request, $next) use ($companyCredits){
           $credit = $this->getCredits($request);
           if(!$credit || $credit->companies_id != \request()->route('company')->id){
               return abort(404);
           }
           $user = auth()->user();
            if(LibPermissions::userCannot($user, LibListPermissions::CREDITSCOMPANY_DELETE, $companyCredits)) {
                throw new NotFoundHttpException();
            }
            return $next($request);
        })->only([
            'delete',
            'deletePost'
        ]);

        $this->middleware(function($request, $next) use ($companyCredits){
            $credit = $this->getCredits($request);
            if(!$credit || $credit->companies_id != \request()->route('company')->id){
                return abort(404);
            }
            if(\request()->method() === 'POST'){
                if(!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }
            $user = auth()->user();
            if(LibPermissions::userCannot($user, LibListPermissions::CREDITSCOMPANY_EDIT, $companyCredits)) {
                throw new NotFoundHttpException();
            }
            return $next($request);
        })->only([
            'edit',
            'saveCredit'
        ]);

        $this->middleware(function ($request, $next) use ($companyCredits) {
            $credit = \request()->route('credit');
            if (!$credit || $credit->companies_id != \request()->route('company')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CREDITSCOMPANY_EDIT, $companyCredits)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'services',
            'saveServices',
        ]);
    }

    private function getCredits($request)
    {
        $creditId = $request->route('credit');

        if ($creditId instanceof Credit) {
            $credit = $creditId;
        } else {
            $credit = Credit::find($creditId);
        }

        return $credit;
    }

    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCompanyCredits::class));

        }
        $catalogo = new CatalogCompanyCredits();

        return VistasGafaFit::view('admin.company.credits.index', [
            'catalogo' => $catalogo,
        ]);

    }
    public function edit(Request $request, Company $company, Credit $credit)
    {
        if ($credit === null) {
            return abort(404);
        }
        $brands_c = CreditsBrand::select('brands_id')->where('credits_id', $credit->id)->get();

        return VistasGafaFit::view('admin.company.credits.edit.index', [
            'credit'  => $credit,
            'brands_c'=> $brands_c,
            'urlForm' => route('admin.company.credits.save.credit', [
                'company' => $this->getCompany(),
                'credit'  => $credit->id,
            ]),
        ]);
    }

    public function create(Request $request, Company $company)
    {

        return VistasGafaFit::view('admin.company.credits.create.index', [
            'urlForm' => route('admin.company.credits.save.new', [
                'company' => $company,
            ]),
        ]);
    }

    public function delete(Request $request, Company $company, Credit $credit)
    {
        return VistasGafaFit::view('admin.company.credits.edit.delete', [
            'company' => $company,
            'credit'  => $credit,
        ]);
    }

    public function saveNew(Request $request, Company $company)
    {
        $nuevo = CatalogFacade::save($request, CatalogCompanyCredits::class);

        return redirect()->route('admin.company.credits.edit', [
            'company' => $company,
            'credit'  => $nuevo->id,
        ]);
    }

    /**
     * Guardado de brands en las que aplica el credito
     *
     * @param Request $request
     * @param Company $company
     * @param Credit $credit
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveCredit(Request $request, Company $company, Credit $credit)
    {

        LibCredits::saveBrands($request,$company,$credit);

        CatalogFacade::save($request, CatalogCompanyCredits::class);

        return redirect()->back();
    }
    public function deletePost(Request $request, Company $company)
    {
        CatalogFacade::delete($request, CatalogCompanyCredits::class);

        return redirect()->route('admin.company.credits.index', [
            'company' => $company,
        ]);

    }

    /**
     * Servicios aplicables para las marcas
     *
     * @param Request $request
     * @param Company $company
     * @param Credit $credit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function services(Request $request, Company $company, Credit $credit)
    {
        $brands_c = CreditsBrand::select('brands_id')->where('credits_id', $credit->id)->where('deleted_at', null)->get();

        $services = LibServices::getParentServiceswithChildsCompany($brands_c);
        $credits_services = $credit->services;

        return VistasGafaFit::view('admin.company.credits.services', [
            'services'         => $services ?? [],
            'combos'           => $credit,
            'credits_services' => $credits_services
        ]);

    }

    public function saveServices(Request $request, Company $company, Credit $credit)
    {
        LibCredits::saveServicesInCompany($request, $this->getCompany(), $credit);
    }

}
