<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 06/08/2018
 * Time: 05:54 PM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Events\Gympass\TriggerGympassApprovalEmail;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Gympass\Helpers\GympassHelpers;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Models\Location\Location;
use Illuminate\Http\Request;

class CatalogLocation extends \App\Librerias\Catalog\Tables\Brand\CatalogLocation
{
    use JsonColumnTrait;

    public function Valores(Request $request = null)
    {
        $valores = parent::Valores($request);
        foreach ($valores as $k => $column) {
            if ($column->getColumna() === 'name') {
                unset($valores[ $k ]);
                array_values($valores);
            }
            if ($column->getColumna() === 'order') {
                unset($valores[ $k ]);
                array_values($valores);
            }
        }

        array_push($valores, new LibValoresCatalogo($this, __('brand.Name'), 'name', [
            'validator' => 'required|string|max:100',
        ]));

        $company = $this->company;
        $location = Location::find($this->id);
        $this_location = $this;
        if ($company->isGympassActive() && LibPermissions::userCan(\Auth::user(), LibListPermissions::GYMPASS_LOCATION_EDIT, $location)) {
            $valores[] = new LibValoresCatalogo($this, '', 'extra_fields.gympass.days_before_opening', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]);


            $valores[] = (new LibValoresCatalogo($this, '', 'extra_fields.gympass.gym_id', [
                'validator'    => 'integer|required_with:gympass_active',
                'hiddenInList' => true,
            ], function () use ($this_location, $request) {
                //Extras
                if (
                    $request->has('gympass_active')
                    &&
                    $request->get('gympass_active', '') === 'on'
                ) {
                    $this_location->setDotValue('extra_fields.gympass.active', 1);
                } else {
                    $this_location->setDotValue('extra_fields.gympass.active', 0);
                }

                if (
                    $request->has('gympass_production')
                    &&
                    $request->get('gympass_production', '') === 'on'
                ) {
                    $this_location->setDotValue('extra_fields.gympass.production', 1);
                } else {
                    $this_location->setDotValue('extra_fields.gympass.production', 0);
                }

                if (
                    $request->has('gympass_approved')
                    &&
                    $request->get('gympass_approved', '') === 'on'
                ) {
                    $this_location->setDotValue('extra_fields.gympass.approved', 1);
                } else {
                    $this_location->setDotValue('extra_fields.gympass.approved', 0);
                }
            }));

            $checkin_types = GympassHelpers::getCheckinTypes(true);
            $valores[] = (new LibValoresCatalogo($this, '', 'extra_fields.gympass.checkin_type', [
                'validator'    => "nullable|in:$checkin_types",
                'hiddenInList' => true,
            ]));
        }

        return $valores;
    }

    public function runLastSave()
    {
        //        Send Gympass e-mail
        $request = \request();
        $location = Location::find($this->id);
        if (
            ($request->has('gympass_resend_email') && $request->input('gympass_resend_email')) ||
            ($this->isMarkedGympassActive() && !$this->isGympassEmailSent() && $this->isGympassPendingApproval() && $this->getDotValue('extra_fields.gympass.gym_id'))) {
            event(new TriggerGympassApprovalEmail($location));
        }
    }
}
