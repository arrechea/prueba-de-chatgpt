<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 30/04/2018
 * Time: 10:46 AM
 */

namespace App\Http\Controllers\Admin\Brand\Marketing;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Controllers\Admin\Brand\MarketingController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogOffer;
use App\Librerias\Models\Offers\LibOffers;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Offer\Offer;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OffersController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($company, $brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::OFFER_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($brand) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $brands_id = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'brands_id';
                    })->first()['value'] ?? 0;

                if ($brands_id !== \request()->route('brand')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);
        $this->middleware(function ($request, $next) use ($company, $brand) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $offer = \request()->route('offer');
            if (!$offer || $offer->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::OFFER_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($company, $brand) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::OFFER_CREATE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($company, $brand) {
            $offer = \request()->route('offer');
            if (!$offer || $offer->brands_id != \request()->route('brand')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::OFFER_DELETE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
    }

    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogOffer::class));
        }

        $catalogo = new CatalogOffer();

        return VistasGafaFit::view('admin.brand.marketing.offers.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function create(Request $request, Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.marketing.offers.create.index', [
            'urlForm' => route('admin.company.brand.marketing.offers.save.new', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
            ]),
        ]);
    }

    public function edit(Request $request, Company $company, Brand $brand, Offer $offer)
    {
        return VistasGafaFit::view('admin.brand.marketing.offers.edit.index', [
            'urlForm' => route('admin.company.brand.marketing.offers.save', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),
                'offer'   => $offer,
            ]),
            'offer'   => $offer,
        ]);
    }

    public function saveEdit(Request $request, Company $company, Brand $brand, Offer $offer)
    {
        $request = LibOffers::nullMarketingFields($request);
        CatalogFacade::save($request, CatalogOffer::class);

        return redirect()->back();
    }

    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $request = LibOffers::nullMarketingFields($request);
        $new = CatalogFacade::save($request, CatalogOffer::class);

        return redirect()->route('admin.company.brand.marketing.offers.edit', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
            'offer'   => $new,
        ]);
    }

    public function delete(Request $request, Company $company, Brand $brand, Offer $offer)
    {

        return VistasGafaFit::view('admin.brand.marketing.offers.delete', [
            'offer' => $offer,
        ]);
    }

    public function deletePost(Request $request, Company $company, Brand $brand, Offer $offer)
    {
        if (!LibOffers::confirmOffer($this->getBrand(), $offer))
            return abort(401);

        CatalogFacade::delete($request, CatalogOffer::class);

        return redirect()->route('admin.company.brand.marketing.offers.index', [
            'company' => $this->getCompany(),
            'brand'   => $this->getBrand(),
        ]);
    }
}
