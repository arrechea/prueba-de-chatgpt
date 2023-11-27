<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 12/09/2018
 * Time: 04:06 PM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\GiftCards\LibGiftCards;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Purchase\PurchaseGiftCard;
use App\Models\User\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GiftCardController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (!LibPermissions::userCan($user, LibListPermissions::GIFTCARD_ASSIGN, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            $code = $request->get('code');
            $user_profiles_id = $request->get('user_id');
            $user = Auth::user();
            if (!$user_profiles_id || !$code || $user_profiles_id === '' || $code == '') {
                abort(400);
            }


            return $next($request);
        })->only([
            'save',
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
    public function assign(Request $request, Company $company, Brand $brand, Location $location)
    {
        $route = route('admin.company.brand.locations.giftcards.assign', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
        ]);

        return VistasGafaFit::view('admin.location.giftcard.modal', [
            'route' => $route,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return mixed
     */
    public function save(Request $request, Company $company, Brand $brand, Location $location)
    {
        $user_profiles_id = $request->get('user_id');
        $code = $request->get('code');

        $user = UserProfile::find($user_profiles_id);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $auth = Auth::user()->getProfileInThisCompany();

        $response = LibGiftCards::saveGiftCard($user, $code, $brand, $auth);

        if (!($response instanceof PurchaseGiftCard)) {
            abort(403, $response);
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function searchUser(Request $request)
    {
        $company = $this->getCompany();

        $search = $request->get('term');
        $users = UserProfile::select(['id', DB::raw('concat(first_name," ",last_name, " (",email,").") as text')])
            ->where('status', 'active')
            ->where('companies_id', $company->id)
            ->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%');
                $q->orWhere('last_name', 'like', '%' . $search . '%');
                $q->orWhere('email', 'like', '%' . $search . '%');
            })
            ->get()->toJson();


        return $users;

    }

}
