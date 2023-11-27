<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 04/04/2018
 * Time: 03:22 PM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogCompany extends \App\Librerias\Catalog\Tables\GafaFit\CatalogCompany
{
    public function Valores(Request $request = null)
    {
        $arr = parent::Valores($request);

        unset($arr['gympass']);
        unset($arr['gympass_checkin']);
        foreach ($arr as $k => $column) {
            if ($column->getTag() === __('gafacompany.Edit')) {
                $column->setGetValueNameFilter(function ($lib, $val) {
                    $company = $lib->getModel();
                    $view_route = route('admin.company.dashboard', ['company' => $company]);
                    $edit_route = route('admin.company.settings.index', ['company' => $company]);
                    $delete_route = route('admin.company.settings.delete', ['company' => $company, 'compToEdit' => $company->id]);

                    return VistasGafaFit::view('admin.company.companies.buttons', [
                        'company'      => $company,
                        'view_route'   => $view_route,
                        'edit_route'   => $edit_route,
                        'delete_route' => $delete_route,
                        'active'       => $company->isActive(),
                    ])->render();
                });
            }
        }

        return $arr;
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $query->where('admins_id', '=', auth()->id());
    }
}
