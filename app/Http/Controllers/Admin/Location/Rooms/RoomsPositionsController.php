<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/08/2018
 * Time: 02:32 PM
 */

namespace App\Http\Controllers\Admin\Location\Rooms;


use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Rooms\CatalogRoomMapsPositions;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Maps\MapsPosition;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class RoomsPositionsController extends LocationLevelController
{

    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::POSITIONS_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            if ($request->ajax()) {
                $request_array = \request()->all();
                $request_array['filters'][] = [
                    'name'  => 'locations_id',
                    'value' => $location->id,
                ];
                \request()->replace($request_array);
            }

            return $next($request);
        })->only([
            'index',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $position = $request->route('position');
            if ($position->locations_id !== $location->id) {
                abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    throw new NotFoundHttpException();
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::POSITIONS_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    throw new NotFoundHttpException();
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::POSITIONS_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

    }

    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoomMapsPositions::class));
        }

        $catalogo = new CatalogRoomMapsPositions();

        return VistasGafaFit::view('admin.location.rooms.positions.index', [
            'catalogo' => $catalogo,
        ]);

    }

    public function create(Request $request)
    {
        $urlForm = route('admin.company.brand.locations.maps-position.save.new', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
        ]);

        return VistasGafaFit::view('admin.location.rooms.positions.create.index', [
            'urlForm' => $urlForm,
        ]);
    }

    public function edit(Request $request, Company $company, Brand $brand, Location $location, MapsPosition $position)
    {
        $urlForm = route('admin.company.brand.locations.maps-position.save', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'position' => $position,
        ]);

        return VistasGafaFit::view('admin.location.rooms.positions.edit.index', [
            'urlForm'  => $urlForm,
            'position' => $position,
        ]);

    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Location $location)
    {

        $new = CatalogFacade::save($request, CatalogRoomMapsPositions::class);

        return redirect()->route('admin.company.brand.locations.maps-position.edit', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'position' => $new,
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Brand        $brand
     * @param Location     $location
     *
     * @param MapsPosition $position
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Location $location, MapsPosition $position)
    {
        CatalogFacade::save($request, CatalogRoomMapsPositions::class);

        return redirect()->back();

    }

}
