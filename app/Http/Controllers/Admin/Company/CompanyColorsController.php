<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 03/08/2018
 * Time: 10:18 AM
 */

namespace App\Http\Controllers\Admin\Company;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogCompaniesColors;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyColorsController extends CompanyLevelController
{

    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COLORS_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company)
    {
        $colorComp = $company->companyColor;

        return VistasGafaFit::view('admin.company.settings.edit.colorIndex', [
            'colorComp' => $colorComp,
            'urlForm'   => route('admin.company.settings.colors.save', [
                'company'   => $this->getCompany(),
                'colorComp' => $colorComp,
            ]),
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save( Request $request, Company $company)
    {
        $new = CatalogFacade::save($request, CatalogCompaniesColors::class);

        return redirect()->back();
    }
}
