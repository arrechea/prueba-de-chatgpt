<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 15/05/2018
 * Time: 05:51 PM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogLocation;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Location\Location;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingsController extends LocationLevelController
{

    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $location = $this->getLocation();


        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SETTINGS_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id)
                    return abort(404);
            }
            $Brands = $this->getBrand();

            $locationid = $request->route('location');

            if ($locationid instanceof Location) {
                $Locations = $locationid;
            } else {
                $Locations = Location::where('slug', $locationid)->first();
            }


            if ($Locations->brand->id !== $Brands->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SETTINGS_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveLocation',
        ]);

        $this->middleware(function ($request, $next) use ($location) {

            if (\request()->method() === 'POST') {
                if (!\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id)
                    return abort(404);
            }
            $Brands = $this->getBrand();
            $locationid = $request->route('location');

            if ($locationid instanceof Location) {
                $Locations = $locationid;
            } else {
                $Locations = Location::where('id', $locationid)->first();
            }


            if ($Locations->brand->id !== $Brands->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SETTINGS_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
    }

    public function index()
    {
        $services = Service::whereNull('parent_id')->where('brands_id', $this->getBrand()->id)->get();

        $company = $this->getCompany();
        $brand = $this->getBrand();
        $location = $this->getLocation();
        $urlForm = route('admin.company.brand.locations.settings.save.location', ['company' => $company, 'brand' => $brand, 'location' => $location]);


        return VistasGafaFit::view('admin.location.settings.index', [
            'LocationToEdit' => $location,
            'urlForm'        => $urlForm,
            'services'       => $services,
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
    public function saveLocation(Request $request, Company $company, Brand $brand)
    {
        CatalogFacade::save($request, CatalogLocation::class);

        return redirect()->back();
    }

    public function delete(Request $request, Company $company, Brand $brand, Location $location, int $id)
    {
        $LocationToEdit = Location::find($id);


        return VistasGafaFit::view('admin.location.settings.delete', [
            'LocationToEdit' => $LocationToEdit,
        ]);

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
    public function deletePost(Request $request, Company $company, Brand $brand, Location $location, int $id)
    {
        CatalogFacade::delete($request, CatalogLocation::class);

        return redirect()->route('admin.company.brand.dashboard', [
            'company' => $company,
            'brand'   => $brand,
        ]);
    }

}
