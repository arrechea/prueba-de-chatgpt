<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 03/05/2018
 * Time: 05:55 PM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Helpers\LibRoute;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;

class LocationLevelController extends BrandLevelController
{
    /**
     * @var Location
     */
    private $location;

    /**
     * LocationLevelController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = LibRoute::getLocation(\request());
        if (!$location)
            return abort(404);
        if ($location->brands_id != $this->getBrand()->id)
            return abort(404);
        if (!$location->isActive()) {
            return abort(403, __('location.InactiveError'));
        }

        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }
}
