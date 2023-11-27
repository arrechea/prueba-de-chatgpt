<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 27/04/2018
 * Time: 08:56 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Offer\OfferRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogOffer extends LibCatalogoModel
{
    use SoftDeletes, OfferRelations, TraitConImagen;

    protected $table = 'offers';

    public function GetName()
    {
        return 'Offers';
    }

    public function Valores(Request $request = null)
    {
        $offer = $this;

        $period = new LibValoresCatalogo($this, __('marketing.Period'), '', [
            'validator' => '',
        ]);
        $period->setGetValueNameFilter(function ($lib, $val) use ($offer) {
            if ($offer->from && $offer->to)
                return $offer->from . ' - ' . $offer->to;
            else
                return __('messages.undefined');
        });

        $buttons = new LibValoresCatalogo($this, __('marketing.Actions'), '', [
            'validator' => '',
        ]);
        $buttons->setGetValueNameFilter(function ($lib, $val) use ($offer) {
            return VistasGafaFit::view('admin.brand.marketing.offers.buttons', ['offer' => $offer])->render();
        });


        return [
            new LibValoresCatalogo($this, __('administrators.Name'), 'name', [
                'validator' => 'required|string|max:190',
            ], function () use ($offer, $request) {
                $offer->active = $request->has('active') && $request->get('active', '') === 'on';
            }),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'exists:brands,id|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'from', [
                'validator'    => 'date|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'to', [
                'validator'    => 'date|after:from|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'image', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),
            new LibValoresCatalogo($this, '', 'type', [
                'validator'    => 'required|in:discount,credits,buy_get',
                'hiddenInList' => true,
            ], function () use ($offer) {
                if (\request()->get('type') === 'discount') {
                    $offer->discount_type = \request()->get('discount_type_check', null) === 'on' ? 'percent' : 'price';
                } else {
                    $offer->discount_type = null;
                }
            }),
            new LibValoresCatalogo($this, '', 'buy_get_get', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'buy_get_buy', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'code', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'discount_number', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'discount_type', [
                'validator'    => 'nullable|in:price,percent',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'credits', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'user_limit', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            $period,
            $buttons,
        ];
    }

    public function link(): string
    {
        return '';
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $brands_id = LibFilters::getFilterValue('brands_id');

        $query->where('brands_id', $brands_id);
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.marketing.info');
    }

    protected function extenderValidacion(&$validator)
    {
        $request = \request();

        /*
         * Validar imagen
         */
        $picture = $request->file('image', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('image', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }
    }
}
