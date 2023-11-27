<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MarketingController extends BrandLevelController
{
    /**
     * MarketingController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($company, $brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MARKETING_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($company, $brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MARKETING_CREATE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'createModal',
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        return redirect()->route('admin.company.brand.marketing.combos.index', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, Company $company, Brand $brand)
    {
        if ($request->has('type')) {
            $type = $request->get('type');
            switch ($type) {
                case 'promotion':
                    return redirect()->route('admin.company.brand.marketing.offers.create', [
                        'company' => $this->getCompany(),
                        'brand'   => $this->getBrand(),
                    ]);
                case 'combo';
                    return redirect()->route('admin.company.brand.marketing.combos.create', [
                        'company' => $this->getCompany(),
                        'brand'   => $this->getBrand(),
                    ]);
                case 'membership';
                    return redirect()->route('admin.company.brand.marketing.membership.create', [
                        'company' => $this->getCompany(),
                        'brand'   => $this->getBrand(),
                    ]);
                case 'discount_codes';
                    return redirect()->route('admin.company.brand.discount-code.create', [
                        'company' => $this->getCompany(),
                        'brand'   => $this->getBrand(),
                    ]);
            }
        }

        return abort(500);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createModal(Request $request, Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.marketing.create');
    }
}
