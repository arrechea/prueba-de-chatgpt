<?php

namespace App\Http\Controllers\Api\Service;


use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Calendars\LibCalendar;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogService;
use App\Librerias\Helpers\LibRoute;
use App\Models\Brand\Brand;
use App\Models\Service;
use App\Models\Service\ServiceSpecialText;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $brand = LibRoute::getBrand($request);
            $serviceToSee = $request->route('serviceToSee');

            if (!$serviceToSee || $serviceToSee->brands_id !== $brand->id) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'get',
            'specialTexts',
            'meetings',
        ]);
    }

    /**
     * @param Request $request
     *
     * @param Brand   $brand
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
                    'name'  => 'brands_id',
                    'value' => $brand->id,
                ],
                [
                    'name'  => 'with_childrens',
                    'value' => true,
                ],
                [
                    'name'  => 'only_parents',
                    'value' => $request->get('only_parents', false) === 'true',
                ],
            ],
        ]);

        $request = \request();
        $response = CatalogFacade::index($request, CatalogService::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @param Service $serviceToSee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Brand $brand, Service $serviceToSee)
    {

        return response()->json($serviceToSee);
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @param         $serviceToSee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function specialTexts(Request $request, Brand $brand, Service $serviceToSee)
    {
        $specialTexts = ServiceSpecialText::where('services_id', $serviceToSee->id)
            ->orderBy('order', 'asc')
            ->get();

        return response()->json($specialTexts);
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @param Service $serviceToSee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function meetings(Request $request, Brand $brand, Service $serviceToSee)
    {
        $events = LibCalendar::getServiceBrandMeetings($request, $brand, $serviceToSee->id, $request->get('start'), $request->get('end'));

        return response()->json($events);
    }

}
