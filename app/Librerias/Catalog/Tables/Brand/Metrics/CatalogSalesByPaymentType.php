<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 14/09/2018
 * Time: 12:27 PM
 */

namespace App\Librerias\Catalog\Tables\Brand\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Money\LibMoney;
use App\Models\Currency\Currencies;
use App\Models\Payment\PaymentsRelations;
use App\Models\Purchase\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogSalesByPaymentType extends LibCatalogoModel
{
    use PaymentsRelations;
    protected $table = 'payment_types';

    private $currency;

    public function GetName()
    {
        return 'Purchases By Payment Types';
    }

    public function Valores(Request $request = null)
    {
        $payment = $this;

        return [
            (new LibValoresCatalogo($this, __('metrics.sales.payment-name'), 'name'))->setGetValueNameFilter(function () use ($payment) {
                return __($payment->name);
            }),
            (new LibValoresCatalogo($this, __('metrics.value'), 'total'))->setGetValueNameFilter(function () use ($payment) {
                $currency = Currencies::find(LibFilters::getFilterValue('currency'));

                return LibMoney::currencyFormat($currency, $payment->total);
            }),
            (new LibValoresCatalogo($this, __('metrics.quantity'), 'purchases_count')),
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');
        $brands_id = LibFilters::getFilterValue('brands_id');
        $currencies_id = LibFilters::getFilterValue('currency');
        $locations = LibFilters::getFilterValue('locations', null, []);

        $purchases = Purchase::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where([
                ['brands_id', $brands_id],
                ['status', 'complete'],
                ['currencies_id', $currencies_id],
            ])
            ->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            })
            ->selectRaw('sum(total) total,payment_types_id')
            ->groupBy('payment_types_id');


        $purchases_sql = $purchases->toSql();
        foreach ($purchases->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $purchases_sql = preg_replace('/\?/', $value, $purchases_sql, 1);
        }

        $query->select('payment_types.*', 'pu.total')->leftJoin(DB::raw('(' . $purchases_sql . ') pu'), 'pu.payment_types_id', 'payment_types.id');

        $query->withCount(['purchases' => function ($q) use ($brands_id, $currencies_id, $start, $end,$locations) {
            $q->where('brands_id', $brands_id);
            $q->where('currencies_id', $currencies_id);
            $q->whereDate('created_at', '>=', $start);
            $q->whereDate('created_at', '<=', $end);
            $q->where('status', 'complete');
            $q->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            });
        }]);
    }

    public function link(): string
    {
        return '';
    }

    public function GetOtherFilters()
    {
        return 'filtros--ventas';
    }

    public function GetSearchable()
    {
        return false;
    }
}
