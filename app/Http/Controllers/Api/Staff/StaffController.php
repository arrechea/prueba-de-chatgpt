<?php

namespace App\Http\Controllers\Api\Staff;


use App\Http\Controllers\ApiController;
use App\Librerias\Calendars\LibCalendar;
use App\Librerias\Catalog\CatalogFacade;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Catalog\Tables\Brand\CatalogStaff;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffBrands;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class StaffController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $brand = \request()->route('brand');
            $staff = \request()->route('staff');

            $brandsStaff = StaffBrands::where('brands_id', $brand->id)
                ->where('staff_id', $staff->id)
                ->first();

            if (!$brandsStaff) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'get',
            'meetings',
            'specialTexts',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            if ((!$request->has('start') || $request->get('start') === '') || (!$request->has('end') || $request->get('end') === '')) {
                abort(403, __('staff.MessageErrorPeriodMissing'));
            }

            return $next($request);
        })->only([
            'meetings',
        ]);
    }

    /**
     * @param Request $request
     *
     * @param Brand $brand
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Brand $brand
     *
     */
    public function index(Request $request, Brand $brand)
    {
        \request()->merge([
            'filters' => [
                [
                    'name' => 'brands_id',
                    'value' => $brand->id,
                ],
            ],
        ]);
        $response = CatalogFacade::index($request, CatalogStaff::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     * @param Brand $brand
     * @param Staff $staff
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Brand $brand, Staff $staff)
    {
        return response()->json($staff);
    }

    /**
     * @param Request $request
     * @param Brand $brand
     * @param Staff $staff
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function meetings(Request $request, Brand $brand, Staff $staff)
    {
//        $now = Carbon::now()->toDateString();
//        $end = Carbon::now()->addDays(15)->toDateString();

        $nextMeetings = LibCalendar::getStaffBrandMeetings($request, $brand, $staff->id, $request->get('start'), $request->get('end'));

        return response()->json($nextMeetings);
    }

    /**
     * Obtiene los textos especiales asociados al id del instructor dado
     *
     * @param Request $request
     * @param Brand $brand
     * @param Staff $staff
     *
     * @return mixed
     */
    public function specialTexts(Request $request, Brand $brand, Staff $staff)
    {
        return $staff->special_texts;
    }


    public function create(Request $request)
    {
//        if (!$request->header('gafafit-company'))
//            abort(403, __('api-user.MessageNoCompany'));
        $company = $this->getCompany();

//        $this->validate($request, [
//            'name' => 'nullable|string|max:191',
//            'lastname' => 'nullable|string|max:191',
//            'slug' => 'required|string|max:191',
//            'admin_profile_id' => 'nullable|numeric',
//            'job' => 'nullable|string|max:191',
//            'quote' => 'nullable|string|max:191',
//            'description' => 'nullable|string|max:191',
//            'email' => 'nullable|email|max:191',
//            'phone' => 'nullable|string|max:191',
//            'birth_date' => 'nullable|date',
//            'gender' => 'nullable|in:male,female',
//            'order' => 'required|numeric',
//            'picture_web' => 'nullable|string|max:191',
//            'picture_web_list' => 'nullable|string|max:191',
//            'picture_web_over' => 'nullable|string|max:191',
//            'picture_movil' => 'nullable|string|max:191',
//            'picture_movil_list' => 'nullable|string|max:191',
//            'hide_in_home' => 'nullable|boolean',
//            'address' => 'nullable|string|max:191',
//            'external_number' => 'nullable|numeric',
//            'municipality' => 'nullable|string|max:191',
//            'postal_code' => 'nullable|numeric',
//            'city' => 'nullable|string|max:191',
//            'country_states_id' => 'nullable|numeric',
//            'countries_id' => 'nullable|numeric',
//        ]);
//
        $status = ($request->status == 1 || !isset($request->status)) ? 'on' : null;

        $request->merge(['companies_id' => $company->id, 'status' => $status]);

        $staff = CatalogFacade::save($request, CatalogStaff::class);

        return response()->json($staff);
    }

    public function update(Request $request,  $company,  $staff)
    {
        $staff = CatalogStaff::find($staff);

        if(!$staff){
            abort(404, __('staff.NotFound'));
        }

        $status = ($request->status == 1 || !isset($request->status)) ? 'on' : $staff->status;

        request()->merge([ 'companies_id' => $staff->companies_id, 'status' => $status]);
        $request = request();

        $staff = CatalogFacade::save($request, CatalogStaff::class, $staff);

        return response()->json($staff);
    }

    public function delete(Request $request,  $company,  $staff)
    {
        $staff = CatalogStaff::find($staff);

        if(!$staff){
            abort(404, __('staff.NotFound'));
        }

        $request->merge([ 'id' => $staff->id]);

        $staff = CatalogFacade::delete($request, CatalogStaff::class);

        return response()->json($staff);
    }

    public function restore(Request $request, $company, $id)
    {

//        $request->merge([ 'id' => $id]);
//        $staff = CatalogFacade::restore($request, CatalogStaff::class);

        $staff = Staff::withTrashed()->find($id);
        $staff->restore();

        return response()->json($staff);
    }
}