<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 27/04/2018
 * Time: 11:47 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Admin;
use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibCatalogoRelationCustom;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Errores\LibErrores;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Combos\Combos;
use App\Models\Combos\combosRelationship;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CatalogCombo extends LibCatalogoModel
{
    use combosRelationship, TraitConImagen, SoftDeletes;

    protected $casts = [
        'hide_in_home'  => 'boolean',
        'hide_in_front' => 'boolean',
    ];

    protected $table = 'combos';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Combos';
    }

    /**
     * @param Request|null $request
     *
     * @return array
     */
    public function Valores(Request $request = null)
    {
        $combos = $this;
        $brand = $combos->brand;

        if (Auth::user() instanceof Admin && LibPermissions::userCan(Auth::user(), LibListPermissions::CREDITS_EDIT, $brand)) {
            $credit_link = (new LibValoresCatalogo($this, __('marketing.Credits'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($combos) {
                return [VistasGafaFit::view('admin.brand.marketing.combos.link', [
                    'credit' => $combos->credits_id,
                    'name'   => $combos->credit->name,
                ])->render()];
            });


        } else {
            $credit_link = (new LibValoresCatalogo($this, __('marketing.Credits'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($combos) {
                return $combos->credit->name;
            });
        }

        $botones = new LibValoresCatalogo($this, __('marketing.Actions'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);

        $botones->setGetValueNameFilter(function ($lib, $value) use ($combos) {
            return VistasGafaFit::view('admin.brand.marketing.combos.botones', [
                'id'     => $combos->id,
                'combos' => $combos,


            ])->render();
        });

        return [


            new LibValoresCatalogo($this, __('marketing.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($combos, $request) {
                //extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $combos->status = 'active';
                } else {
                    $combos->status = 'inactive';
                }

                if (
                    $request->has('show')
                    &&
                    $request->get('show', '') === 'on'
                ) {
                    $combos->hide_in_home = false;
                } else {
                    $combos->hide_in_home = true;
                }

                if (
                    $request->has('showFront')
                    &&
                    $request->get('showFront', '') === 'on'
                ) {
                    $combos->hide_in_front = false;
                } else {
                    $combos->hide_in_front = true;
                }

                if (
                    $request->has('can_subscribe')
                    &&
                    $request->get('can_subscribe', '') === 'on'
                ) {
                    $combos->subscribable = true;
                    $number_of_tries = $request->input('number_of_tries');
                    $request->merge([
                        'number_of_tries' => $number_of_tries ?? 3,
                    ]);
                } else {
                    $combos->subscribable = false;
                    $request->merge([
                        'number_of_tries' => null,
                    ]);
                }
            }),
            new LibValoresCatalogo($this, __('marketing.Order'), 'order', [
                'validator' => 'integer|min:0',
            ]),
//            new LibValoresCatalogo($this, __('marketing.level'), 'level', [
//                'validator' => 'nullable|in:brand,company',
//            ]),

            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'exists:brands,id|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'credits_id', [
                'validator'    => 'exists:credits,id|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'credits', [
                'validator'    => 'integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'description', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'short_description', [
                'validator'    => 'nullable|max:255',
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

            new LibValoresCatalogo($this, '', 'price', [
                'validator'    => 'integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'discount_from', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,

            ]),

            new LibValoresCatalogo($this, '', 'discount_to', [
                'validator'    => 'nullable|date|after:discount_from',
                'hiddenInList' => true,

            ]),

            new LibValoresCatalogo($this, '', 'expiration_days', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'reservations_min', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'reservations_max', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),

            $credit_link,

            (new LibValoresCatalogo($this, __('marketing.Show'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($combos) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => !$combos->hide_in_home,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.ShowFront'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($combos) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => !$combos->hide_in_front,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.isActive'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($combos) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $combos->isActive(),
                ])->render();
            }),
            $botones,
            (new LibValoresCatalogo($this, '', 'number_of_tries', [
                'validator'    => 'nullable|integer|min:1',
                'hiddenInList' => true,
            ])),
            $this->getCategoriesValores(),
        ];
    }

    /**
     *
     */
    private function getCategoriesValores()
    {
        $combo = new LibValoresCatalogo(
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

                $validator = \Validator::make($request->all(), ['categories' => 'array']);

                if ($validator->fails()) {
                    LibErrores::lanzarErrores($validator->errors());
                }

                $selected = $request->get('categories', []);
                if ($id = $request->get('id')) {
                    Combos::find($id)->categories()->sync($selected);
                }
            }
        );

        return $combo->setGetValueNameFilter(
            static function ($LibValoresCatalogo, $value) use ($combo) {
                $currentCategories = $combo->categories;

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
    }

    /**
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.brand.marketing.combos.index', [
            'company' => $this->company,
            'brand'   => $this->brand,
            'Combos'  => $this->id,
        ]);
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        $request = \request();

        if ($request->has('filters')) {
            $brands_id = LibFilters::getFilterValue('brands_id', $request);

            $query->where('brands_id', (int)$brands_id);
        } else {
            $query->whereNull('id');
        }
        $query->with([
            'credit',
        ]);
        $query->orderBy('order');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.marketing.info');
    }

    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('marketing.CombosPhoto')]));
            }
        }
    }
}
