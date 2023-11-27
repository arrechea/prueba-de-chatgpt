<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/09/2018
 * Time: 05:07 PM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibCatalogoRelationCustom;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\DiscountCode\DiscountCode;
use App\Models\DiscountCode\DiscountRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CatalogDiscountCode extends LibCatalogoModel
{
    use DiscountRelations, SoftDeletes, TraitConImagen;
    protected $table = 'discount_codes';
    protected $dates = [
        'discount_from',
        'discount_to',
    ];

    /**
     * @return string
     */
    public function link(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return 'DiscountCode';
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $brands_id = LibFilters::getFilterValue('brands_id');

        $query->where('brands_id', $brands_id)->withCount('purchaseDiscountCodesApplied');
    }

    /**
     * @return \Closure
     */
    static protected function ColumnToSearch()
    {

        return function ($query, $criterio) {
            $query->where('code', 'like', "%{$criterio}");
        };
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.DiscountCode.info');
    }

    /**
     * @param Request|null $request
     *
     * @return array
     */
    public function Valores(Request $request = null)
    {
        $discountCode = $this;

        $categories = new LibValoresCatalogo(
            $this,
            __('discount_codes.DiscountCategories'),
            null,
            [
                'type'         => 'select',
                'validator'    => 'nullable|exists:user_categories,id',
                'hiddenInList' => true,
                'other'        => new LibCatalogoRelationCustom([]),
            ],
            static function () {
                $request = request();

                $validator = Validator::make($request->all(), ['categories' => 'array']);

                if ($validator->fails()) {
                    return back()->withErrors($validator->errors());
                }

                $selected = $request->get('categories', []);
                if ($id = $request->get('id')) {
                    DiscountCode::find($id)->categories()->sync($selected);
                }
            }
        );

        $categories->setGetValueNameFilter(
            static function ($LibValoresCatalogo, $value) use ($discountCode) {
                $currentCategories = $discountCode->categories;

                $selected = null;
                if (count($currentCategories)) {
                    $selected =
                        $currentCategories
                            ->map(
                                static function ($category) {
                                    return $category->name;
                                }
                            )
                            ->implode(',');
                }

                return $selected;
            }
        );

        return [
            (new LibValoresCatalogo($this, __('discounts.code'), 'code', [
                'validator' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique($discountCode->GetTable(), 'code')
                        ->ignore($discountCode->id, 'id')
                        ->where('brands_id', $discountCode->brands_id)
                        ->whereNull('deleted_at'),
                ],

            ], function () use ($discountCode, $request) {
                //extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $discountCode->status = 'active';
                } else {
                    $discountCode->status = 'inactive';
                }
                //extras
                if (
                    $request->has('discount_type')
                    &&
                    $request->get('discount_type', '') === 'on'
                ) {
                    $discountCode->discount_type = 'price';
                } else {
                    $discountCode->discount_type = 'percent';
                }
                //isPublic
                if (
                    $request->has('is_public')
                    &&
                    $request->get('is_public', '') === 'on'
                ) {
                    $discountCode->is_public = true;
                } else {
                    $discountCode->is_public = false;
                }
                //isVisibleInHome
                if (
                    $request->has('in_home')
                    &&
                    $request->get('in_home', '') === 'on'
                ) {
                    $discountCode->in_home = true;
                } else {
                    $discountCode->in_home = false;
                }
            })),

            (new LibValoresCatalogo($this, __('discounts.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($discountCode) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $discountCode->isActive(),
                ])->render();
            }),

            (new LibValoresCatalogo($this, __('discounts.type'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($discountCode) {
                if ($discountCode->isPrice()) {
                    $type = __('discounts.price');
                } else {
                    $type = __('discounts.percent');
                }

                return [$type];
            }),

            (new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'nullable|exists:companies,id',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'nullable|exists:brands,id',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, '', 'locations_id', [
                'validator'    => 'nullable|exists:locations,id',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, '', 'short_description', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, '', 'terms', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('discounts.discountNumber'), 'discount_number', [
                'validator' => 'integer|min:0',
            ])),
            (new LibValoresCatalogo($this, __('discounts.validity'), '', [
                'validator' => 'nullable|date',
            ]))->setGetValueNameFilter(function () use ($discountCode) {
                $dateFrom = $discountCode->discount_from ? $discountCode->discount_from->toFormattedDateString() : '';
                $dateTo = $discountCode->discount_to ? $discountCode->discount_to->toFormattedDateString() : '';

                return "$dateFrom - $dateTo";
            }),
            (new LibValoresCatalogo($this, __('discounts.discountFrom'), 'discount_from', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('discounts.discountTo'), 'discount_to', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('discounts.totalUse'), 'total_uses', [
                'validator'    => 'nullable|integer|min:1',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('discounts.UserUse'), 'users_uses', [
                'validator'    => 'integer|min:1',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('discounts.purchases_min'), 'purchases_min', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('discounts.purchases_max'), 'purchases_max', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ])),
            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),

            (new LibValoresCatalogo($this, __('discounts.Used'), ''))
                ->setGetValueNameFilter(function () use ($discountCode) {
                    return $discountCode->total_uses
                        ? $discountCode->purchase_discount_codes_applied_count . '/' . $discountCode->total_uses
                        : $discountCode->purchase_discount_codes_applied_count;
                }),

            (new LibValoresCatalogo($this, __('discounts.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($discountCode) {
                return VistasGafaFit::view('admin.brand.DiscountCode.button', [
                    'discountCode' => $discountCode,
                ])->render();
            }),
            $categories
        ];
    }

}
