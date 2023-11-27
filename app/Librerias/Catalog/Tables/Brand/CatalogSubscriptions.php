<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 12/03/2019
 * Time: 16:07
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Combos\Combos;
use App\Models\Membership\Membership;
use App\Models\Subscriptions\SubscriptionTrait;
use Illuminate\Http\Request;

class CatalogSubscriptions extends LibCatalogoModel
{
    use SubscriptionTrait;

    protected $table = 'subscriptions';

    public function GetName()
    {
        return 'Subscriptions';
    }

    public function link(): string
    {
        return '';
    }

    public function Valores(Request $request = null)
    {
        $subscription = $this;

        return [
            (new LibValoresCatalogo($this, __('subscriptions.User'), ''))
                ->setGetValueNameFilter(function () use ($subscription) {
                    return $subscription->user_profile->first_name . ' ' . $subscription->user_profile->last_name;
                }),
            (new LibValoresCatalogo($this, __('subscriptions.Product'), ''))
                ->setGetValueNameFilter(function () use ($subscription) {
                    return $subscription->product->name;
                }),
            (new LibValoresCatalogo($this, __('subscriptions.CreationDate'), 'created_at')),
            (new LibValoresCatalogo($this, __('subscriptions.Actions'), ''))
                ->setGetValueNameFilter(function () use ($subscription) {
                    return VistasGafaFit::view('admin.brand.catalogs.subscriptions.buttons', [
                        'subscription' => $subscription,
                    ])->render();
                }),
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $combos_id = LibFilters::getFilterValue('combos_id');
        $memberships_id = LibFilters::getFilterValue('memberships_id');
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');
        $users_id = LibFilters::getFilterValue('users_id');

        $brands_id = LibFilters::getFilterValue('brands_id');

        $query->where('brands_id', $brands_id);

        $query->with(['product', 'user_profile'])
            ->when($combos_id, function ($q, $combos_id) {
                return $q->where([
                    ['product_type', Combos::class],
                    ['product_id', $combos_id],
                ]);
            })->when($memberships_id, function ($q, $memberships_id) {
                return $q->where([
                    ['product_type', Membership::class],
                    ['product_id', $memberships_id],
                ]);
            })->when($start && $end, function ($q, $value) use ($start, $end) {
                $q->whereDate('created_at', '>=', $start);
                $q->whereDate('created_at', '<=', $end);

                return $q;
            })->when($users_id, function ($q, $users_id) {
                return $q->where([
                    ['users_profiles_id', $users_id],
                ]);
            });
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.catalogs.subscriptions.filters');
    }

    public function GetSearchable()
    {
        return false;
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }
}
