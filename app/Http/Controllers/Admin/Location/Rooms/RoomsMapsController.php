<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/08/2018
 * Time: 12:36 PM
 */

namespace App\Http\Controllers\Admin\Location\Rooms;


use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Librerias\Map\LibMapsGenerator;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Rooms\CatalogRoomMaps;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Maps\Maps;
use App\Models\Maps\MapsPosition;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;
use Illuminate\Support\Facades\Lang;

class RoomsMapsController extends LocationLevelController
{
    /**
     * RoomsMapsController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAPS_VIEW, $location)) {
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
            $map = $request->route('maps');
            if ($map->locations_id !== $location->id) {
                abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id
                )
                    throw new NotFoundHttpException();
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAPS_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAPS_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            $map = $request->route('maps');

            if ($map->locations_id !== $location->id) {
                abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAPS_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'cloneMaps',
            'clonePost',
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoomMaps::class));
        }

        $catalogo = new CatalogRoomMaps();

        return VistasGafaFit::view('admin.location.rooms.maps.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand, Location $location)
    {
        $urlForm = route('admin.company.brand.locations.room-maps.save.new', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
        ]);
        $locationPositions = LibMapsGenerator::getPositionsMapsByLocation($location);

        return VistasGafaFit::view('admin.location.rooms.maps.create.index', [
            'langFile'          => new Collection(Lang::get('map-generator')),
            'urlForm'           => $urlForm,
            'locationPositions' => $locationPositions,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Maps     $maps
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand, Location $location, Maps $maps)
    {

        $urlEdit = route('admin.company.brand.locations.room-maps.save.edit', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'maps'     => $maps,
        ]);

        $urlForm = route('admin.company.brand.locations.room-maps.edit.map.save', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'map'      => $maps,
        ]);

        $locationPositions = LibMapsGenerator::getPositionsMapsByLocation($location);

        $map = LibMapsGenerator::getInitialMap($maps);

        return VistasGafaFit::view('admin.location.rooms.maps.edit.index', [
            'urlEdit'           => $urlEdit,
            'maps'              => $maps,
            'langFile'          => new Collection(Lang::get('map-generator')),
            'urlForm'           => $map ? $urlForm : [],
            'locationPositions' => $locationPositions,
            'initialMap'        => $map,
        ])->with('alert', 'Reservas');

    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Location $location)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'map'  => 'required|json',
        ]);
        $name = $request->get('name', '');
        $active = true;
        $background = $request->file('image_background');
        $backgroundURL = $request->get('image_background');
        $map = new Collection(json_decode($request->get('map', '[]'), true));

        $newMap = LibMapsGenerator::generateMap($name, $map, $location, $active, $background, $backgroundURL);

        return response()->json(route('admin.company.brand.locations.room-maps.edit', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'maps'     => $newMap,
        ]));
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @param Maps     $maps
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Location $location, Maps $maps)
    {

        CatalogFacade::save($request, CatalogRoomMaps::class);

        return redirect()->route('admin.company.brand.locations.room-maps.index', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Maps     $maps
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cloneMaps(Request $request, Company $company, Brand $brand, Location $location, Maps $maps)
    {
        $urlClone = route('admin.company.brand.locations.room-maps.clone.map', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'maps'     => $maps,
        ]);

        return VistasGafaFit::view('admin.location.rooms.maps.cloneForm', [
            'maps'     => $maps,
            'urlclone' => $urlClone,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Maps     $maps
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clonePost(Request $request, Company $company, Brand $brand, Location $location, Maps $maps)
    {
        $urlForm = route('admin.company.brand.locations.room-maps.save.new', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
        ]);
        $locationPositions = LibMapsGenerator::getPositionsMapsByLocation($location);

        $initialMap = LibMapsGenerator::parseInitialMap($maps);

        return VistasGafaFit::view('admin.location.rooms.maps.create.index', [
            'langFile'          => new Collection(Lang::get('map-generator')),
            'urlForm'           => $urlForm,
            'locationPositions' => $locationPositions,
            'initialMap'        => $initialMap,
            'map'               => $maps,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Maps     $maps
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMap(Request $request, Company $company, Brand $brand, Location $location, Maps $maps)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'map'  => 'required|json',
        ]);
        $name = $request->input('name', '');
        $active = $request->has('active') ? (bool)$request->input('active') : false;
        $background = $request->file('image_background');
        $backgroundURL = $request->input('image_background');
        $map = new Collection(json_decode($request->input('map', '[]'), true));

        $maps->objects()->delete();

        $newMap = LibMapsGenerator::generateMap($name, $map, $location, $active, $background, $backgroundURL, $maps);

        return response()->json(route('admin.company.brand.locations.room-maps.edit', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'maps'     => $newMap,
        ]));
    }
}
