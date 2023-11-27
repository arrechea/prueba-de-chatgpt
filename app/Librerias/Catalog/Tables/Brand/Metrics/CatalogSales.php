<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 23/07/2018
 * Time: 10:29 AM
 */

namespace App\Librerias\Catalog\Tables\Brand\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Money\LibMoney;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Currency\Currencies;
use App\Models\Purchase\PurchaseItemsRelations;
use App\Models\Purchase\PurchaseRelation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CatalogSales extends LibCatalogoModel
{
    use SoftDeletes, PurchaseItemsRelations;
    protected $table = 'purchase_items';

    public function GetName()
    {
        return 'purchase_items';
    }

    /**
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]|array
     *
     */
    public function Valores(Request $request = null)
    {
        $item_purchase = $this;

        return [
            new LibValoresCatalogo($this, __('metrics.Sale'), 'item_name'),
            (new LibValoresCatalogo($this, __('metrics.value'), 'price'))->setGetValueNameFilter(function () use ($item_purchase) {
                $currency = $item_purchase->purchase->currency;
                $price = $item_purchase->price;

                return LibMoney::currencyFormat($currency, $price);
            }),
            (new LibValoresCatalogo($this, __('metrics.quantity'), 'quantity')),

        ];
    }

    protected static function QueryToOrderBy()
    {
        return 'price';
    }

    protected static function QueryToOrderByOrder()
    {
        return 'desc';
    }

    public function link(): string
    {
        return '';
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
        $brands_id = LibFilters::getFilterValue('brands_id');
        $currencies_id = LibFilters::getFilterValue('currency');
        $locations = LibFilters::getFilterValue('locations', null, []);

        $query->select(DB::raw('ANY_VALUE(purchases_id) as purchases_id,ANY_VALUE(item_name) as item_name,sum(item_price_final) as price,count(id) as quantity,buyed_id as id'))
            ->groupBy(['buyed_type', 'buyed_id'])
            ->where([
                ['brands_id', (int)$brands_id],
            ])
            ->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            })
            ->with(['purchase.currency' => function ($q) {
                $q->select(DB::raw('id, prefijo, sufijo'));
            }]);

        $start_date = LibFilters::getFilterValue('start');
        $end_date = LibFilters::getFilterValue('end');

        $query->where(function ($q) use ($start_date, $end_date) {
            $q->whereDate('created_at', '>=', $start_date);
            $q->whereDate('created_at', '<=', $end_date);
        });

        $query->whereHas('purchase', function ($q) {
            $q->where('status', 'complete');
        });

        $query->whereHas('purchase.payment_type', function ($q) {
            $q->where('slug', '!=', 'courtesy');
        });

        $query->whereHas('purchase.currency', function ($q) use ($currencies_id) {
            $q->where('currencies_id', $currencies_id);
        });
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.metrics.sales.info');
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
