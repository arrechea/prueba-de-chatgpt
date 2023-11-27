<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 23/04/2018
 * Time: 12:10 PM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Gympass\Helpers\GympassAPIClassesFunctions;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\HasSpecialTexts;
use App\Models\Company\Company;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Models\Location\Location;
use App\Models\Service;
use App\Models\Service\Servicetrait;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CatalogService extends LibCatalogoModel implements HasSpecialTexts
{
    use Servicetrait, TraitConImagen, SoftDeletes, JsonColumnTrait;

    protected $table = 'services';
    protected $casts = [
        'hide_in_home' => 'boolean',
    ];
    protected $json = [
        'extra_fields',
    ];

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Services';
    }

    /**
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]|array
     */
    public function Valores(Request $request = null)
    {
        $service = $this;

        $credit_link = (new LibValoresCatalogo($this, __('services.Credits'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]))->setGetValueNameFilter(function ($lib, $value) use ($service) {
            $credits = $service->serviceModel->neccesaryCredits();
            //dd($credits->toArray());
            if (count($credits) <= 0) {
                return "<span style='color: red; text-transform: uppercase'><i class='material-icons'>report_problem</i>" . __('services.noCredits') . "</span>";
            } else {
                return VistasGafaFit::view('admin.brand.services.creditButton', [
                    'credits' => $credits,
                ])->render();
            }
        });

        $botones = (new LibValoresCatalogo($this, __('gafacompany.Edit'), '', [
            'validator' => '',
        ]))->setGetValueNameFilter(function ($lib, $value) use ($service) {
            return VistasGafaFit::view('admin.brand.services.buttons', ['service' => $service])->render();
        });

        $parent = (new LibValoresCatalogo($this, __('services.Parent'), '', [
            'validator' => '',
        ]))->setGetValueNameFilter(function ($lib, $val) use ($service) {
            return VistasGafaFit::view('admin.brand.services.parent', ['service' => $service->parentService])->render();
        });


        $return = [
            new LibValoresCatalogo($this, __('administrators.Name'), 'name', [
                'validator' => 'string|required',
            ], function () use ($service, $request) {
                //Name
                $service->name = $request->get('name');
                //Status
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $service->status = 'active';
                } else {
                    $service->status = 'inactive';
                }

                if (
                    $request->has('show')
                    &&
                    $request->get('show', '') === 'on'
                ) {
                    $service->hide_in_home = false;
                } else {
                    $service->hide_in_home = true;
                }
            }),
            new LibValoresCatalogo($this, '', 'description', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),
            new LibValoresCatalogo($this, '', 'category', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'integer|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'parent_id', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),
            $parent,
            (new LibValoresCatalogo($this, __('marketing.Show'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($service) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => !$service->hide_in_home,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('marketing.isActive'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($service) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $service->isActive(),
                ])->render();
            }),
            new LibValoresCatalogo($this, __('marketing.Order'), 'order', [
                'validator' => 'integer|min:0',
            ]),
            $credit_link,
            $botones,
        ];

        $brand = $this->brand;
        if ($brand) {
            if (LibPermissions::userCan(\Auth::user(), LibListPermissions::GYMPASS_CLASS_EDIT, $brand)) {
                $locations = $brand->gympassActiveLocations;

                foreach ($locations as $location) {
                    if ($location && $location->isGympassActive()) {
                        $locationId = $location->id;
                        $return[] = new LibValoresCatalogo($this, '', "extra_fields.gympass.info.$locationId.product_id", [
                            'validator'    => "integer|required_with:gympass_active.$locationId",
                            'hiddenInList' => true,
                        ], function () use ($service, $request, $locationId) {
                            //Extras
                            if (
                                $request->has('gympass_active')
                                &&
                                $request->get('gympass_active', '') === 'on'
                            ) {
                                $service->setDotValue('extra_fields.gympass.active', 1);
                            } else {
                                $service->setDotValue('extra_fields.gympass.active', 0);
                            }

                            if (
                                $request->has("gympass_actives.$locationId")
                                &&
                                isset($request->get("gympass_actives", [])[ $locationId ]) &&
                                $request->get("gympass_actives", [])[ $locationId ] === 'on'
                            ) {
                                $service->setDotValue("extra_fields.gympass.info.$locationId.active", 1);
                            } else {
                                $service->setDotValue("extra_fields.gympass.info.$locationId.active", 0);
                            }

                            if (
                                $request->has("gympass_visible.$locationId")
                                &&
                                isset($request->get("gympass_visible", [])[ $locationId ]) &&
                                $request->get("gympass_visible", [])[ $locationId ] === 'on'
                            ) {
                                $service->setDotValue("extra_fields.gympass.info.$locationId.visible", 1);
                            } else {
                                $service->setDotValue("extra_fields.gympass.info.$locationId.visible", 0);
                            }

                            if (
                                $request->has("gympass_bookable.$locationId")
                                &&
                                isset($request->get("gympass_bookable", [])[ $locationId ]) &&
                                $request->get("gympass_bookable", [])[ $locationId ] === 'on'
                            ) {
                                $service->setDotValue("extra_fields.gympass.info.$locationId.bookable", 1);
                            } else {
                                $service->setDotValue("extra_fields.gympass.info.$locationId.bookable", 0);
                            }
                        });

                    }
                }
            }
        }

        return $return;
    }

    public function runLastSave()
    {
        if ($this->isGympassActive()) {
            $validator = \Validator::make([], []);
            $service = Service::find($this->id);


            GympassAPIClassesFunctions::saveClassFromService($service, $validator);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }

    /**
     * @return string
     */
    public function link(): string
    {
        return route('admin.brand.services.edit', [
            'service' => $this,
        ]);
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $query->with('parentService');

        $brands_id = LibFilters::getFilterValue('brands_id');

        $query->where('brands_id', $brands_id);

        if (LibFilters::getFilterValue('with_childrens', null, false) === true) {
            $query->with('childServicesRecursive');
        }
        if (LibFilters::getFilterValue('only_parents', null, false) === true) {
            $query->whereNull('parent_id');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.services.info');
    }

    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('services.ServicePicture')]));
            }
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceModel()
    {
        return $this->belongsTo(Service::class, 'id', 'id');
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where('name', 'like', "%{$criterio}%");
        };
    }

    /**
     * @return string
     */
    static protected function QueryToOrderBy()
    {
        return 'order';
    }

    static protected function QueryToOrderByOrder()
    {
        return 'asc';
    }
}
