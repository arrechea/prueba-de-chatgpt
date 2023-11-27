<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/05/2018
 * Time: 12:34 PM
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
use App\Models\Membership\Membership;
use App\Models\Membership\MembershipCredits;
use App\Models\Membership\MembershipRelationship;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogMembership extends LibCatalogoModel
{
    use TraitConImagen, SoftDeletes, MembershipRelationship;

    protected $table = 'memberships';
    protected $casts = [
        'hide_in_home'  => 'boolean',
        'hide_in_front' => 'boolean',
    ];

    public function GetName()
    {
        return 'Membership';
    }

    public function Valores(Request $request = null)
    {
        $membership = $this;
        $brand = $membership->brand;
        $authUser = Auth::user();

        if ($authUser instanceof Admin &&
            LibPermissions::userCan($authUser, LibListPermissions::CREDITS_EDIT, $brand)
        ) {
            $credit_link = (new LibValoresCatalogo($this, __('marketing.Credits'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($membership) {
                $credits_member = $membership->credits->map(function ($credit) {
                    return $credit->id;
                })->toArray();
                $credits = $membership->credits->map(function ($credit) {
                    return $credit->name;
                })->toArray();

                return VistasGafaFit::view('admin.brand.marketing.membership.link', [
                    'credits' => $credits_member,
                    'names'   => $credits,
                ])->render();
            });
        } else {

            $credit_link = (new LibValoresCatalogo($this, __('marketing.Credits'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($membership) {
                $credits = $membership->credits->map(function ($credit) {
                    return $credit->name;
                })->toArray();

                return implode(', ', $credits);
            });
        }

        return [
            new LibValoresCatalogo($this, __('marketing.Name'), 'name', [
                'validator' => '',
            ], function () use ($membership, $request) {
                if (
                    $request->has('show')
                    &&
                    $request->get('show', '') === 'on'
                ) {
                    $membership->hide_in_home = false;
                } else {
                    $membership->hide_in_home = true;
                }
                if (
                    $request->has('showFront')
                    &&
                    $request->get('showFront', '') === 'on'
                ) {
                    $membership->hide_in_front = false;
                } else {
                    $membership->hide_in_front = true;
                }

                if ($request->has('status') && $request->get('status') === 'on') {
                    $membership->status = 'active';
                } else {
                    $membership->status = 'inactive';
                }

                if (
                    $request->has('can_subscribe')
                    &&
                    $request->input('can_subscribe', '') === 'on'
                ) {
                    $membership->subscribable = true;
                    $number_of_tries = $request->input('number_of_tries');
                    $request->merge([
                        'number_of_tries' => $number_of_tries ?? 3,
                    ]);
                } else {
                    $membership->subscribable = false;
                    $request->merge([
                        'number_of_tries' => null,
                    ]);
                }
            }),

            new LibValoresCatalogo($this, __('marketing.Order'), 'order', [
                'validator' => 'integer|min:0',
            ]),
            new LibValoresCatalogo($this, __('marketing.level'), 'level', [
                'validator' => 'nullable|in:location,brand,company',
            ]),
            $credit_link,

            (new LibValoresCatalogo($this, __('marketing.Show'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($membership) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => !$membership->hide_in_home,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.ShowFront'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($membership) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => !$membership->hide_in_front,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.isActive'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($membership) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $membership->isActive(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.Actions'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib) use ($membership) {
                return VistasGafaFit::view('admin.brand.marketing.membership.button', [
                    'id'         => $membership->id,
                    'membership' => $membership,
                ])->render();
            }),

            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id|integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'exists:brands,id|integer|min:0',
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


            new LibValoresCatalogo($this, '', 'description', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'short_description', [
                'validator'    => 'nullable|max:255',
                'hiddenInList' => true,
            ]),


            new LibValoresCatalogo($this, '', 'expiration_days', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'reservations_limit', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'reservations_limit_daily', [
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

            new LibValoresCatalogo($this, '', 'meeting_max_reservation', [
                'validator'    => 'integer|min:1',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'number_of_tries', [
                'validator'    => 'nullable|integer|min:1',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'global_purchase', [
                'validator'    => 'integer|min:1',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'total_purchase', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            $this->getCategoriesValores(),
        ];
    }

    /**
     * @return $this
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
                    Membership::find($id)->categories()->sync($selected);
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

    protected function extenderValidacion(&$validator)
    {
        $req = \request();
        if (!$req->has('credits_id') || $req->get('credits_id') == null) {
            $validator->errors()->add('credits_id', __('validation.required', ['attribute' => 'credits_id']));
        }

        $picture = $req->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }

        return $validator;
    }

    public function runLastSave()
    {
        $req = \request();
        $membership = $this;

        if ($req->has('credits_id') && $req->get('credits_id') !== null) {
            MembershipCredits::updateOrCreate([
                'memberships_id' => $membership->id,
            ], [
                'credits_id' => $req->get('credits_id'),
            ]);
        }
    }


    public function link(): string
    {
        return route('admin.company.brand.marketing.membership.index', [
            'company'    => $this->company,
            'brand'      => $this->brand,
            'Membership' => $this->id,
        ]);
    }

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
            'credits',
        ]);
        $query->orderBy('order');

        //dd($query->get());
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.marketing.info');
    }

}
