<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 14/05/2018
 * Time: 09:31 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Catalog\Tables\Location\Reservations\StaffRelationship;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\HasSpecialTexts;
use App\Models\Staff\StaffBrands;
use App\Traits\TraitConImagen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogStaff extends LibCatalogoModel implements HasSpecialTexts
{
    use TraitConImagen, SoftDeletes, StaffRelationship;
    protected $casts = [
        'hide_in_home' => 'boolean',
    ];

    protected $table = 'staff';

    public function GetName()
    {
        return 'Staff';
    }

    /**
     * @return array
     */
    public function getColumnsForSlugify(): array
    {
        return [
            'name',
            'lastname'
        ];
    }

    public function Valores(Request $request = null)
    {
        $staff = $this;

        $age = new LibValoresCatalogo($this, __('staff.Age'), '', [
            'validator' => '',
        ]);

        $age->setGetValueNameFilter(function () use ($staff) {
            if ($staff) {
                if (!$staff->birth_date) {
                    return '--';
                } else {
                    $birth_date = Carbon::parse($staff->birth_date);

                    return $birth_date->diffInYears(Carbon::now());
                }
            }

            return null;
        });

        $botones = new LibValoresCatalogo($this, __('staff.Actions'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);
        $botones->setGetValueNameFilter(function ($lib, $value) use ($staff) {
            return VistasGafaFit::view('admin.brand.staff.botones', [
                'id'         => $staff->id,
                'staff'      => $staff,
                'view_route' => $staff->link(),
            ])->render();
        });

        return [
            new LibValoresCatalogo($this, 'Orden', 'order', [
                'validator' => 'integer|nullable',
            ]),
            new LibValoresCatalogo($this, __('staff.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($staff, $request) {
                //extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $staff->status = 'active';
                } else {
                    $staff->status = 'inactive';
                }

                if (
                    $request->has('show')
                    &&
                    $request->get('show', '') === 'on'
                ) {
                    $staff->hide_in_home = false;
                } else {
                    $staff->hide_in_home = true;
                }
            }),

            new LibValoresCatalogo($this, __('staff.Email'), 'email', [
                'validator' => 'email|nullable',
            ]),

            new LibValoresCatalogo($this, __('staff.Phone'), 'phone', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => false,
            ]),


            new LibValoresCatalogo($this, __('staff.Photo'), 'picture_web_list', [
                'validator'    => '',
                'type'         => 'file',
                'notOrdenable' => true,
            ]),

            new LibValoresCatalogo($this, __('staff.Photo'), 'picture_web', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, __('staff.Photo'), 'picture_web_over', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, __('staff.Photo'), 'picture_movil', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, __('staff.Photo'), 'picture_movil_list', [
                'validator'    => '',
                'type'         => 'file',
                'hiddenInList' => true,
            ]),


            new LibValoresCatalogo($this, '', 'lastname', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'birth_date', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'address', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'external_number', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'municipality', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'postal_code', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'city', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'countries_id', [
                'validator'    => 'exists:countries,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'country_states_id', [
                'validator'    => 'exists:country_states,id|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'gender', [
                'validator'    => 'nullable|in:male,female',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'admin_profiles_id', [
                'validator'    => 'exists:admin_profiles,id|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'job', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'quote', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'description', [
                'validator'    => 'string|nullable|max:6000',
                'hiddenInList' => true,
            ]),

            (new LibValoresCatalogo($this, __('marketing.Show'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($staff) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => !$staff->hide_in_home,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.isActive'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($staff) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $staff->isActive(),
                ])->render();
            }),
            $botones,

        ];
    }

    public function runLastSave()
    {
        $request = \request();
        $staff = $this;
        if ($request->has('brands_id')) {
            StaffBrands::updateOrCreate([
                'staff_id'  => $staff->id,
                'brands_id' => $request->get('brands_id'),
            ]);
        }
    }

    public function link(): string
    {
        return route('admin.company.brand.staff.edit', [
            'company' => $this->company,
            'brand'   => $this->brand,
            'staff'   => $this,
        ]);
    }

    static protected function filtrarQueries(&$query)
    {
        $request = \request();

        if ($request->has('filters')) {
            $brands_id = LibFilters::getFilterValue('brands_id');

            $query->whereHas('brands', function ($q) use ($brands_id) {
                $q->where('brands.id', (int)$brands_id);
            });
        } else {
            $query->whereNull('id');
        }
        $query->orderBy('order');
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.staff.info');
    }

    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('picture_web_list', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('staff.picture-web-list')]));
            }
        }

        $picture = $request->file('picture_web', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('staff.picture-web')]));
            }
        }

        $picture = $request->file('picture_web_over', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('staff.picture-web-over')]));
            }
        }

        $picture = $request->file('picture_movil', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('staff.picture-movil')]));
            }
        }

        $picture = $request->file('picture_movil_list', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('staff.picture-movil-list')]));
            }
        }
    }
}
