<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 30/11/2018
 * Time: 10:17
 */

namespace App\Http\Controllers\Admin;


use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AdminRequest;
use App\Librerias\SpecialText\LibSpecialTextCatalogs;
use App\Models\Brand\Brand;
use App\Models\Catalogs\Catalogs;
use App\Models\Company\Company;

class CatalogController extends AdminController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);
    }

    /**
     * @param AdminRequest $request
     * @param Company      $company
     * @param Catalogs     $catalog
     * @param Brand        $brand
     *
     * @return mixed
     */
    public function fields(AdminRequest $request, Company $company, Catalogs $catalog, Brand $brand)
    {
        return response()->json(LibSpecialTextCatalogs::getGroupsWithFields($company, $catalog, $brand));
    }

    /**
     * @param AdminRequest $request
     * @param Company      $company
     * @param Brand        $brand
     * @param Catalogs     $catalog
     * @param int          $id
     *
     * @return array
     */
    public function values(AdminRequest $request, Company $company, Catalogs $catalog, int $id, Brand $brand)
    {
        return LibSpecialTextCatalogs::getModelValues($company, $catalog, $id, $brand);
    }
}
