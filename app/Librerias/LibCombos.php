<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 09:42 AM
 */

namespace App\Librerias;

use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Combos\Combos;
use App\Models\Combos\CombosServices;
use App\Models\Company\Company;
use App\Models\Service;
use Illuminate\Support\Collection;

class LibCombos
{
    public static function saveServices(Request $request, Company $company, Brand $brand, Combos $combos)
    {
        $services_array = array_where($request->get('services'), function ($v, $k) {
            return isset($v['active']) && $v['active'] === 'on';
        });
        $ids = array_pluck($services_array, 'id');
        $services = Service::find($ids);
        $saved = [];
        foreach ($services as $service) {
            if ($service->companies_id !== $company->id)
                continue;
            if ($service->brands_id !== $brand->id)
                continue;
            if ($service->parent_id !== null)
                continue;

            CombosServices::updateOrCreate([
                'combos_id'   => $combos->id,
                'services_id' => $service->id,
            ]);
            $saved[] = $service->id;
        }

        CombosServices::whereNotIn('services_id', $saved)->where('combos_id', $combos->id)->delete();
    }
}
