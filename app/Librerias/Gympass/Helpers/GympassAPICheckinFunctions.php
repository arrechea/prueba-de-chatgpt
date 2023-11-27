<?php

namespace App\Librerias\Gympass\Helpers;

use App\Admin;
use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibCatalogsTable;
use App\Librerias\Gympass\SDK\GympassAccessControlAPI;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Users\LibUserProfiles;
use App\Models\GympassCheckin\GympassCheckin;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Conekta\Log;

abstract class GympassAPICheckinFunctions
{
    public const CHECKIN_STATUSES = [
        self::APPROVED_STATUS,
        self::REJECTED_STATUS,
        self::PENDING_STATUS,
    ];

    public const APPROVED_STATUS = 'approved';
    public const REJECTED_STATUS = 'rejected';
    public const PENDING_STATUS = 'pending';

    public const BOOKING_STATUS = 'booking';

    /**
     * @param array $errors
     * @param       $validator
     *
     * @return void
     */
    private static function addErrors(array $errors, &$validator)
    {
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $validator->after(function ($val) use ($error) {
                    $val->errors()->add('gympass', $error);
                });
            }
        }
    }

    /**
     * @param array $event_data
     *
     * @return void
     */
    public static function createCheckinFromEvent(array $event_data)
    {
        \Log::info('check-in creation started');
        $gym_id = $event_data['gym']['id'] ?? null;
        if ($gym_id) {
            $location = GympassHelpers::getLocationFromGymId($gym_id);
            if ($location) {
                $company = $location->company;
                $user_pfoile = GympassHelpers::getOrCreateUser($event_data, $company);
                if ($user_pfoile) {
                    $pending_checkins = $user_pfoile->gympass_pending_checkins;
                    $checkin = new GympassCheckin();
                    $checkin->status = self::PENDING_STATUS;
                    $checkin->users_id = $user_pfoile->users_id;
                    $checkin->user_profiles_id = $user_pfoile->id;
                    $checkin->request_data = $event_data;
                    $checkin->request_time = $location->now();
                    $checkin->companies_id = $user_pfoile->companies_id;
                    $checkin->brands_id = $location->brands_id;
                    $checkin->locations_id = $location->id;

                    if ($checkin->save()) {
                        foreach ($pending_checkins as $pending_checkin) {
                            $pending_checkin->status = GympassCheckin::$status_rejected;
                            $pending_checkin->save();
                        }
                        \Log::info('Check-in guardado');
                        if ($location) {
                            if ($location->isCheckinAutomatic()) {
                                $validator = \Validator::make([], []);
                                self::approveCheckin($checkin, $location, $validator);
                                if ($validator->fails()) {
                                    $errors = $validator->errors();
                                    foreach ($errors as $error) {
                                        \Log::error($error);
                                    }
                                }
                            }
                        } else {
                            \Log::error('No hay ubicaciones activas dentro de la compañía');
                        }

                    } else {
                        \Log::error('Error general');
                    }
                } else {
                    \Log::error("Usuario no encontrado");
                }
            } else {
                \Log::error('Location not found');
            }
        }
    }


    /**
     * @param GympassCheckin $checkin
     * @param Location       $location
     * @param                $validator
     *
     * @return bool
     */
    public static function approveCheckin(GympassCheckin $checkin, Location $location, &$validator = false): bool
    {
        $admin = \Auth::user();

        $errors = [];

        $user = $checkin->user;

        if (!$checkin->isPending()) {
            self::addErrors(['Check-in inválido para aprovación'], $validator);

            return false;
        }


        if (!$user) {
            $errors[] = 'Usuario no encontrado';
        } else {
            $gympass_id = $user->getDotValue('extra_fields.gympass.gympass_id');
            if (!$gympass_id) {
                $errors[] = 'Token del usuario no encontrado';
            } else {
                $location = $checkin->location;
                $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
                if ($location && $gym_id) {
                    $sdk = new GympassAccessControlAPI();
                    $response = $sdk->validate($location, $gym_id, (string)$gympass_id);
                    $code = $sdk->getCode();
                    $checkin->response_data = $response;
                    $checkin->response_time = $location->now();
                    $errors = $sdk->getErrors();
                    if ($code >= 400 && $code < 500) {
                        $checkin->status = self::REJECTED_STATUS;
                        $checkin->errors = $errors;
                        $checkin->expired = true;
                        $checkin->check_in_admin = $admin->id ?? null;
                    }
                    if ($code >= 200 && $code < 300) {
                        $checkin->status = self::APPROVED_STATUS;
//                        if (!$company->isCheckinAutomatic())
                        $checkin->check_in_admin = $admin->id ?? null;
                    }
                } else {
                    $errors[] = 'Compañía no encontrada';
                }
            }
        }

        if ($validator !== false) {
            self::addErrors($errors, $validator);
        }

        $checkin->save();

        return !(count($errors) > 0);
    }
}
