<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 14/05/2018
 * Time: 11:01 AM
 */

namespace App\Librerias\Servicies;


use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Service;
use Illuminate\Support\Collection;

class LibServices
{
    use Service\Servicetrait;

    public static function getServices(Brand $brand)
    {
        return Service::where([
            ['brands_id', $brand->id],
            ['status', 'active'],
        ])->get();
    }

    public static function getParentServices(Brand $brand)
    {
        return Service::whereNull('parent_id')
            ->where([
                ['brands_id', $brand->id],
                ['status', 'active'],
            ])->get();
    }

    public static function getParentServiceswithChilds(Brand $brand)
    {
        return Service::whereNull('parent_id')
            ->with('childServicesRecursive')
            ->where([
                ['brands_id', $brand->id],
                ['status', 'active'],
            ])->get();
    }

    /**
     * Servicios de los brands compartidos en la company
     *
     * @param $brands_array
     * @return array
     */
    public static function getParentServiceswithChildsCompany($brands_array)
    {
        //dd($brands_array);
        $services_c = [];
        foreach ($brands_array as $brand) {
            $services = Service::whereNull('parent_id')
                ->with('childServicesRecursive')
                ->with('brand')
                ->where([
                    ['brands_id', $brand->brands_id],
                    ['status', 'active'],
                ])->get();

            foreach ($services as $service) {
                array_push($services_c, $service);
            }

        }

        return $services_c;

    }

    /**
     * @param Service $service
     * @param Collection $credits_services
     *
     * @return string
     * @throws \Throwable
     */
    public static function printServicesInCredits(Service $service, Collection $credits_services)
    {
        return VistasGafaFit::view('admin.brand.credits.creditslist', [
            'service' => $service,
            'credits_services' => $credits_services,
            'i' => $service->id
        ])->render();
    }

    /**
     * @param Service $service
     * @param Collection $credits_services
     * @return string
     * @throws \Throwable
     */
    public static function printServicesInCreditsCompany(Service $service, Collection $credits_services)
    {
        return VistasGafaFit::view('admin.company.credits.creditslist', [
            'service' => $service,
            'credits_services' => $credits_services,
            'i' => $service->id
        ])->render();
    }
}
