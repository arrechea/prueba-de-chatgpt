<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/25/2019
 * Time: 10:02
 */

namespace App\Http\Controllers\Admin\GafaFit;


use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogGafaCredits;
use App\Librerias\Credits\LibCredits;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Servicies\LibServices;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditsBrand;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreditsGafaFitController extends AdminController
{

    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next){
            $user = auth()->user();
            if(LibPermissions::userCannot($user, LibListPermissions::CREDITSGF_VIEW)){
                throw new NotFoundHttpException();
            }

            return $next($request);
        });


    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogGafaCredits::class));
        }

        $catalogo = new CatalogGafaCredits();

        return VistasGafaFit::view('admin.gafafit.creditsgf.index', [
            'catalogo' => $catalogo
        ]);
    }

    public function create()
    {
        $companies= Company::all()->where('status','active');

        return VistasGafaFit::view('admin.gafafit.creditsgf.create',[
            'urlForm' => route('admin.credits.save.new'),
            'companies' => $companies,
        ]);

    }

    public function edit(int $id)
    {
        $gafacredit = Credit::find($id);
        $companies= Company::all()->where('status','active');
        $brands_c = CreditsBrand::select('brands_id')->where('credits_id', $id)->get();

        return VistasGafaFit::view('admin.gafafit.creditsgf.edit',[
           'gafacredit' => $gafacredit,
            'companies' => $companies,
            'brands_c' => $brands_c,
            'urlForm' => route('admin.credits.save',['gafacredit'=>$gafacredit])
        ]);

    }

    public function delete(int $id)
    {
        $gafacredit = Credit::find($id);
        return VistasGafaFit::view('admin.gafafit.creditsgf.delete',[
            'gafacredit' => $gafacredit,
        ]);
    }

    public function saveNew(Request $request, Credit $credit)
    {

        $nuevo = CatalogFacade::save($request, CatalogGafaCredits::class);

        return redirect()->route('admin.credits.edit', [
            'gafacredit' => $nuevo->id
        ]);

    }

    public function save(Request $request, int $id)
    {
        $gafacredit = Credit::find($id);

        if ($request->input('brandCompanies')) {
            LibCredits::saveBrandsInGafa($request, $gafacredit);
        }
        CatalogFacade::save($request, CatalogGafaCredits::class);

        return redirect()->route('admin.credits.edit',[
            'creditsGafa' => $gafacredit->id
        ]);

    }

    public function deletePost(Request $request, Credit $credit)
    {
        CatalogFacade::delete($request,CatalogGafaCredits::class);

        return redirect()->route('admin.credits.index');
    }

    /**
     * Servicios aplicables para las marcas
     *
     * @param Request $request
     * @param Company $company
     * @param Credit $credit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function services(Request $request,int $id)
    {
        $gafacredit = Credit::find($id);

        $brands_c = CreditsBrand::select('brands_id')->where('credits_id', $gafacredit->id)->where('deleted_at', null)->get();

        $services = LibServices::getParentServiceswithChildsCompany($brands_c);
        $credits_services = $gafacredit->services;

        return VistasGafaFit::view('admin.gafafit.creditsgf.services', [
            'services'         => $services ?? [],
            'combos'           => $gafacredit,
            'credits_services' => $credits_services
        ]);

    }

    public function saveServices(Request $request, int $id)
    {
        $gafacredit = Credit::find($id);

        LibCredits::saveServicesInGafa($request, $gafacredit);
    }

}
