<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\ApiController;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Reservation\LibHandlePurchase;
use App\Librerias\Reservation\LibReservationForm;
use App\Librerias\Reservation\ReservationControllerTrait;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Illuminate\Http\Request;

class ApiReservationController extends ApiController implements ReservationControllerTrait
{
    /**
     * @var Location
     */
    private $location;

    /**
     * ApiReservationController constructor.
     */
    function __construct()
    {
        parent::__construct();
        $location = LibRoute::getLocation(\request(), 'locationToSee');
        if (!$location)
            return abort(404);

        $brand = \request()->route('brand');
        if (!($brand instanceof Brand)) {
            $brand = Brand::where('slug', $brand)->first();
        }

        $location_ids = $brand->locations->pluck('id')->values()->toArray();

        if (!in_array($location->id, $location_ids)) {
            abort(404);
        }


        $this->location = $location;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function getFormTemplate(Request $request)
    {
        return LibReservationForm::generateForm($request, $this->getCompany(), $this->getLocation(), false);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm(Request $request)
    {
        return LibReservationForm::generateForm($request, $this->getCompany(), $this->getLocation(), false);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reservate(Request $request)
    {
        return LibHandlePurchase::purchase($request, $this->getCompany(), $this->getLocation(), false);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @internal param Company $company
     * @internal param Brand $brand
     *
     */
    public function generateGiftCode()
    {
        $location = $this->getLocation();

        return LibReservationForm::generateGiftCode($this->getCompany(), $location->brand);
    }

    /**
     * @param Brand    $brand
     * @param Location $location
     * @param string   $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkGiftCode(Brand $brand, Location $location, string $code)
    {
        $isValid = LibReservationForm::isGiftCodeValidToGenerate($code, $brand);
        if ($isValid) {
            return response()->json(true, 200);
        } else {
            return response()->json(true, 409);
        }
    }

    /**
     * @param Request     $request
     * @param Brand       $brand
     * @param Location    $location
     * @param string      $code
     *
     * @param UserProfile $userProfile
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkDiscountCode(Request $request, Brand $brand, $location, string $code, UserProfile $userProfile)
    {
        return LibReservationForm::responseDiscountCodeValid($code, $brand, $userProfile, $request);
    }


    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
}
