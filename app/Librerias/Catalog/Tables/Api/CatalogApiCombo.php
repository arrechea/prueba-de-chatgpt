<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 04/06/18
 * Time: 15:02
 */

namespace App\Librerias\Catalog\Tables\Api;


use App\Librerias\Catalog\Tables\Brand\CatalogCombo;
use App\Librerias\Helpers\LibFilters;
use App\User;
use Illuminate\Support\Facades\Auth;

class CatalogApiCombo extends CatalogCombo
{
    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $brand_id = LibFilters::getFilterValue('brands_id', null);
        if (request()->get('propagate', 'false') === 'true') {
            $query->with('credit.services.parentService');
        }
        $query->orderBy('order', 'asc');
        $query->where('brands_id', $brand_id);

        /**
         * @var User $user
         */
        if ($user = Auth::user()) {
            $userProfile = $user->getProfileInThisCompany();
            if ($userProfile) {
                $categoriasUser = $userProfile->categories->pluck('id');
                if ($categoriasUser->count() > 0) {
                    $query->where(function ($query) use ($categoriasUser) {
                        $query
                            ->whereHas('categories', function ($query) use ($categoriasUser) {
                                $query->whereIn('category_id', $categoriasUser->toArray());
                            })
                            ->orWhereHas('categories', null, '<=', 0);
                    });
                }
                $cantidadComprasUser = $userProfile
                    ->purchasesComplete()
                    ->where('brands_id', $brand_id)
                    ->count();
                $query->where(function ($query) use ($cantidadComprasUser) {
                    $query->whereNull('reservations_min');
                    $query->orWhere('reservations_min', '<=', $cantidadComprasUser);
                });
                $query->where(function ($query) use ($cantidadComprasUser) {
                    $query->whereNull('reservations_max');
                    $query->orWhere('reservations_max', '>=', $cantidadComprasUser);
                });
            }
        }
    }
}
