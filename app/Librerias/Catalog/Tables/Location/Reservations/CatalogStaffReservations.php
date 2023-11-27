<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 15/05/2018
 * Time: 12:27 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Reservations;

use App\Librerias\Catalog\LibValoresCatalogo;
use \App\Librerias\Catalog\Tables\Brand\CatalogStaff;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogStaffReservations extends CatalogStaff
{

    public function Valores(Request $request = null)
    {
        $staff = $this;
        $active = $staff->isActive();

        $age = new LibValoresCatalogo($this, __('staff.Age'), '', [
            'validator' => '',
        ]);

        $age->setGetValueNameFilter(function () use ($staff) {

            if ($staff) {
                if (!$staff->birth_date){
                    return '--';
                }else {
                    $birth_date = Carbon::parse($staff->birth_date);

                    return $birth_date->diffInYears(Carbon::now());
                }
            }

            return null;
        });

        $actives = new LibValoresCatalogo($this, __('gafacompany.Active'), '', [
            'validator' => '',
        ]);
        $actives->setGetValueNameFilter(function () use ($active) {
            return $active ?
                '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="green" />
                </svg>' :
                '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="red" />
                </svg>';
        });

        $botones = new LibValoresCatalogo($this, __('staff.Reservations'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);
        $botones->setGetValueNameFilter(function ($lib, $value) use ($staff) {
            return VistasGafaFit::view('admin.location.reservations.staff.button', [
                'id'         => $staff->id,
                'staff'      => $staff,
                'view_route' => $staff->link(),
            ])->render();
        });

        return [
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
            }),

            new LibValoresCatalogo($this, __('staff.Email'), 'email', [
                'validator' => 'email|nullable',
            ]),

            $age,

            (new LibValoresCatalogo($this, __('reservations.gender'), 'gender', [
                'validator'    => 'nullable|in:male,female',
                'hiddenInList' => false,
            ]))->setGetValueNameFilter(function ($lib, $value) {
                return __("gender.$value");
            }),


            $actives,

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

            $botones,

        ];

    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.reservations.staff.info');
    }

    static protected function filtrarQueries(&$query)
    {
        $request = \request();

        $brands_id = LibFilters::getFilterValue('brands_id', $request, null);

        $query->whereHas('brands', function ($q) use ($brands_id) {
            $q->where('brands.id', (int)$brands_id);
        });
    }

}
