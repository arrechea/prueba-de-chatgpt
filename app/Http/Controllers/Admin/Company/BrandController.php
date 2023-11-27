<?php

namespace App\Http\Controllers\Admin\Company;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Payments\Conekta;
use App\Librerias\Payments\SystemPaymentMethods;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Currency\Currencies;
use App\Models\Language\Language;
use App\Models\Payment\PaymentType;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class BrandController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($company) {
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
        $this->middleware(function ($request, $next) use ($company) {
            $brandId = $request->route('brand');

            if ($brandId instanceof Brand) {
                $brand = $brandId;
            } else {
                $brand = Brand::where('id', $brandId)->first();
            }

            if (!$brand)
                return abort(404);

            if ($brand->company->id !== $company->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('companies_id') || (int)\request()->get('companies_id') !== $this->getCompany()->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_CREATE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($company) {

            $brandId = $request->route('brand');

            if ($brandId instanceof Brand) {
                $brand = $brandId;
            } else {
                $brand = Brand::where('id', $brandId)->first();
            }

            if ($brand->company->id !== $company->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_DELETE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCan($user, LibListPermissions::NOTIFICATION_EMAIL_EDIT)) {
                $request->merge(['has_mail_permission' => true]);
            } else {
                $request->merge(['has_mail_permission' => false]);
            }

            return $next($request);
        })->only([
            'saveNew',
            'saveEdit',
        ]);
    }

    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogBrand::class));
        }

        $catalogo = new CatalogBrand();

        return VistasGafaFit::view('admin.company.Brands.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param int     $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, int $id)
    {
        $brandToEdit = Brand::find($id);

        if ($brandToEdit === null)
            return abort(404);

        LibGafaPay::createOrUpdatePymentSystemOnGafafit();

        $payment_methods = SystemPaymentMethods::get()
            ->filter(function ($item) {
                return !!class_exists($item->model);
            })
            ->map(function ($item) use ($brandToEdit) {
                return new $item->model(
                    $item,
                    $brandToEdit->gafapay_brand_id,
                    $brandToEdit->gafapay_client_id,
                    $brandToEdit->gafapay_client_secret
                );
            });

        return VistasGafaFit::view('admin.company.Brands.edit.index', [
            'brandToEdit'     => $brandToEdit,
            'urlForm'         => route('admin.company.brands.save', [
                'company'     => $this->getCompany(),
                'brandToEdit' => $brandToEdit->id,
            ]),
            'currencies'      => Currencies::all(),
            'languages'       => Language::all(),
            'payment_methods' => $payment_methods,
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     * @param int     $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function saasEdit(Request $request, Company $company, int $id)
    {
        $brandToEdit = Brand::find($id);

        if ($brandToEdit === null)
            return abort(404);

        $payment_methods = SystemPaymentMethods::get()
            ->filter(function ($item) {
                return !!class_exists($item->model);
            })
            ->map(function ($item) {
                return new $item->model($item);
            });

        return VistasGafaFit::view('admin.company.Brands.edit.index-saas', [
            'brandToEdit'     => $brandToEdit,
            'urlForm'         => route('admin.company.brands.save', [
                'company'     => $this->getCompany(),
                'brandToEdit' => $brandToEdit->id,
            ]),
            'currencies'      => Currencies::all(),
            'languages'       => Language::all(),
            'payment_methods' => $payment_methods,
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $brandToEdit
     *
     */
    public function create(Request $request, Company $company)
    {

        LibGafaPay::createOrUpdatePymentSystemOnGafafit();

        $payment_methods = SystemPaymentMethods::get()
            ->filter(function ($item) {
                return !!class_exists($item->model);
            })
            ->map(function ($item) {
                return new $item->model($item);
            });

        return VistasGafaFit::view('admin.company.Brands.create.index', [
            'urlForm'         => route('admin.company.brands.save.new', [
                'company' => $this->getCompany()
//                'brandToEdit' => $brandToEdit,
            ]),
            'currencies'      => Currencies::all(),
            'languages'       => Language::all(),
            'payment_methods' => $payment_methods,
        ]);
    }


    /**
     * Guardado de nueva marca
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company)
    {

        $nuevo = CatalogFacade::save($request, CatalogBrand::class);

        return redirect()->to($nuevo->link());
    }


    /**
     * Guardado de edicion
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, $brand)
    {

        CatalogFacade::save($request, CatalogBrand::class);

        return redirect()->back();
    }

    /**
     * @param $brandToEdit
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, $brandToEdit)
    {
        return VistasGafaFit::view('admin.company.Brands.edit.delete', [
            'id'          => $brandToEdit,
            'company'     => $this->getCompany(),
            'brandToEdit' => $brandToEdit,
        ]);
    }

    /**
     * Formulario de borrar marca
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company)
    {
        CatalogFacade::delete($request, CatalogBrand::class);

        return redirect()->route('admin.company.brands.index', [
            'company' => $company,
        ]);
    }
}
