<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/05/2018
 * Time: 05:12 PM
 */

namespace App\Librerias\Staff;

use App\Http\Requests\AdminRequest as Request;
use App\Models\Company\Company;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffSpecialText;


class LibStaff
{
    /**
     * Obtiene el staff para la compañía con status activo y con un término
     * opcional de búsqueda
     *
     * @param Request $request
     * @param Company $company
     *
     * @return mixed
     */
    public static function getStaff(Request $request, Company $company)
    {
        $search = $request->get('search', null);

        return '{"results":' . Staff::select('id', 'name as text')->where([
                ['companies_id', $company->id],
                ['status', 'active'],
            ])->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('email', 'like', '%' . $search . '%');
                $q->orWhere('lastname', 'like', '%' . $search . '%');
            })->get()->values() . '}';
    }

    public static function setOrderSpecialTexts(StaffSpecialText $text)
    {
        $prev = $text->otherTexts;
        $prev->map(function ($item, $key) use ($text) {
            if ($item->id !== $text->id && $item->order >= $text->order) {
                $item->order++;
            }

            return $item;
        });
        $sorted = $prev->sortBy('order');

        $i = 0;
        foreach ($sorted as $v) {
            $v->order = $i;
            $v->save();
            $i++;
        }
    }
}
