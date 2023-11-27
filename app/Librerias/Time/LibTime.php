<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 13/09/18
 * Time: 11:07
 */

namespace App\Librerias\Time;


use App\Models\Brand\Brand;
use App\Models\Location\Location;
use Carbon\Carbon;
use DateTimeZone;

class LibTime
{
    /**
     * @param int|null $companyId
     * @param int|null $brandId
     * @param int|null $locationId
     *
     * @return Carbon
     */
    static public function getNowIn(int $companyId = null, int $brandId = null, int $locationId = null): Carbon
    {
        if ($brandId) {
            $brand = Brand::withTrashed()->where('id', $brandId)->first();
            if ($brand) {
                return $brand->now();
            }
        } else if ($locationId) {
            $location = Location::withTrashed()->where('id', $locationId)->first();
            if ($location) {
                return $location->now();
            }
        }

        return Carbon::now();
    }

    static public function getTimeZones(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * @return string
     */
    static public function getTimeZoneValidator(): string
    {
        $timezonesString = implode(',', static::getTimeZones());

        return "in:$timezonesString|required";
    }
}
