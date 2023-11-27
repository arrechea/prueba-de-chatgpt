<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 29/11/2018
 * Time: 16:43
 */

namespace App\Librerias\SpecialText;


use App\Http\Requests\AdminRequest;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Catalogs\CatalogsGroupsControl;

class LibCatalogFieldControl
{
    public static function create(AdminRequest $request, CatalogGroup $group)
    {
        if ($request->has('activate') && $request->has('section')) {
            $section = (string)$request->get('section', '');
            $activate = (boolean)$request->get('activate', true);

            if ($activate) {
                CatalogsGroupsControl::updateOrCreate([
                    'catalogs_groups_id' => $group->id,
                    'section'            => $section,
                ]);
            } else {
                CatalogsGroupsControl::where([
//                    ['companies_id', $group->companies_id],
//                    ['brands_id', $group->brands_id],
                    ['catalogs_groups_id', $group->id],
                    ['section', $section],
                ])->delete();
            }
        }
    }
}
