<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Http\Controllers\Admin\Company\CompanyLevelController;
use App\Librerias\Helpers\LibRoute;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;

class BrandLevelController extends CompanyLevelController
{
    /**
     * @var Brand|\Illuminate\Routing\Route|object|string
     */
    private $brand;

    /**
     * BrandLevelController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = LibRoute::getBrand(\request());
        if (!$brand)
            return abort(404);
        if ($brand->companies_id != $this->getCompany()->id)
            return abort(404);

        $this->brand = $brand;
    }


    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
    }
}
