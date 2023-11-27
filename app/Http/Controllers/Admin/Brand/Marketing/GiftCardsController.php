<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 20/09/2018
 * Time: 10:24 AM
 */

namespace App\Http\Controllers\Admin\Brand\Marketing;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogGiftCards;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Purchase\Purchase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GiftCardsController extends BrandLevelController
{
    /**
     * GiftCardsController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::GIFTCARD_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($Brands) {
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
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogGiftCards::class));
        }
        $catalogo = new CatalogGiftCards();

        return VistasGafaFit::view('admin.brand.marketing.GiftCards.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function indexSaas(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogGiftCards::class));
        }
        $catalogo = new CatalogGiftCards();

        return VistasGafaFit::view('admin.brand.marketing.GiftCards.index-saas', [
            'catalogo' => $catalogo,
        ]);
    }
}
