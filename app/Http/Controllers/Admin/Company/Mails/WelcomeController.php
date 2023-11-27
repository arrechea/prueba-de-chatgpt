<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 25/06/2018
 * Time: 10:06 AM
 */

namespace App\Http\Controllers\Admin\Company\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Company\CompanyLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\Mails\catalogWelcome;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsWelcome;
use App\Http\Requests\AdminRequest as Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WelcomeController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_WELCOME_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($company) {


//            $welcomeid = $request->route('welcomeEdit');
//
//            if ($welcomeid !== null) {
//                if ($welcomeid instanceof MailsWelcome) {
//                    $welcome = $welcomeid;
//                } else {
//                    $welcome = MailsWelcome::where('id', $welcomeid)->first();
//                }
//
//
//                if ($welcome->companies_id !== $company->id) {
//                    return abort(404);
//                }
//            }
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_WELCOME_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);
    }


    /**
     * Funcion create que valida cuando se tienen correos y cuando no, esto para asignarle una variable de los datos en
     * la tabla, y en caso de que no se tenga el correo, se pone null para crear nuevo.
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company)
    {
        $welcomeEdit = $company->mailWelcomeInfo;

        return VistasGafaFit::view('admin.company.mails.welcome.create.index', [
            'welcomeEdit' => $welcomeEdit,
            'urlForm'     => route('admin.company.mails.welcome.save', [
                'company'     => $this->getCompany(),
                'welcomeEdit' => $welcomeEdit,
            ]),
        ]);

    }

//    public function delete(Request $request, Company $company, mailsWelcome $welcomeEdit)
//    {
//
//        return VistasGafaFit::view('admin.brand.mails.delete.deleteWelcome', [
//            'company'     => $this->getCompany(),
//            'brand'       => $this->getBrand(),
//            'welcomeEdit' => $welcomeEdit,
//
//        ]);
//    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company)
    {
        $nuevo = CatalogFacade::save($request, catalogWelcome::class);

        return redirect()->back();

    }


    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
//    public function deletePost(Request $request, Company $company, mailsWelcome $welcomeEdit)
//    {
//        CatalogFacade::delete($request, catalogWelcome::class);
//
//        return redirect()->back();
//    }

}
