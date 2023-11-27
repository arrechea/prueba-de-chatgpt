<?php


namespace App\Http\Controllers\Admin\Brand\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogInvitationConfirm;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsInvitationConfirm;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvitationConfirmController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CONFIRM_INVITATION_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CONFIRM_INVITATION_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand)
    {
        $invitationConfirm = MailsInvitationConfirm::where('brands_id', $brand->id)->first();

        return VistasGafaFit::view('admin.brand.mails.invitationConfirm.edit.index', [
            'invitationConfirm' => $invitationConfirm,
            'urlForm'            => route('admin.company.brand.mails.invitation-confirm.save', [
                'company'           => $this->getCompany(),
                'brand'             => $this->getBrand(),
                'invitationConfirm' => $invitationConfirm,
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogInvitationConfirm::class);

        return redirect()->back();
    }
}
