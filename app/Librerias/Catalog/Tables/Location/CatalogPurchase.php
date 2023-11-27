<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 21/06/2018
 * Time: 05:26 PM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Models\Purchase\PurchaseRelation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogPurchase extends LibCatalogoModel
{
    use SoftDeletes, PurchaseRelation;
    protected $table = 'purchases';

    public function GetName()
    {
        return 'Purchase';
    }

    public function Valores(Request $request = null)
    {
        $purchase = $this;

        return [
            new LibValoresCatalogo($this, '', 'payment_types_id', [
                'validator'    => 'integer|exists:payment_types,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'status', [
                'validator'    => 'nullable|in:pending,complete',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'locations_id', [
                'validator'    => 'integer|exists:locations,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'integer|exists:brands,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'user_profiles_id', [
                'validator'    => 'exists:user_profiles,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'admin_profiles_id', [
                'validator'    => 'exists:admin_profiles,id|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'users_id', [
                'validator'    => 'exists:users,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'subtotal', [
                'validator'    => 'default:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'iva', [
                'validator'    => 'default:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'total', [
                'validator'    => 'default:0',
                'hiddenInList' => true,
            ]),
        ];
    }

    public function link(): string
    {
        //
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        $userprofile = LibFilters::getFilterValue('profile');
        $locationId = LibFilters::getFilterValue('locations_id');
        $brandsId = LibFilters::getFilterValue('brands_id');
        $status = LibFilters::getFilterValue('status');

        $query->where('user_profiles_id', $userprofile->id);

        if ($locationId) {
            $query->where('locations_id', $locationId);
        }

        if ($brandsId) {
            $query->where('brands_id', $brandsId);
        }

        if ($status) {
            $query->where('status', $status);
        }
        $query->with([
            'items',
            'currency',
        ]);
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        //
    }
}
