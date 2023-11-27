<?php

namespace App\Librerias\Gympass\Helpers;

use App\Librerias\Gympass\SDK\GympassBookingAPI;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Service;
use Carbon\Carbon;

abstract class GympassAPISlotsFunctions
{
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
     * @param Meeting $meeting
     *
     * @return array[]
     */
    private static function formDataMeeting(Meeting $meeting): array
    {
        $room = $meeting->room;
        $start_date = $meeting->start_date;
        $end_date = $meeting->end_date;
        $length_in_minutes = $start_date->diffInMinutes($end_date);
        $service = $meeting->service;
        $opens_at = clone $start_date;
        $location = $meeting->location;
        $subDays = (int)$location->getDotValue('extra_fields.gympass.days_before_opening');
        $opens_at->subDays($subDays ?? 7);
        $closes_at = $start_date;
        $product_id = $service->getGympassProductId($location);
        $cancellable_until = $meeting->getCancelationDeadLine();

//        todo: arreglo temporal, cuando actualicemos carbon esto puede regresarse a como antes
        $brand = $location->brand;
        $tz = null;
        if ($brand) {
            if ($brand->time_zone === 'America/Mexico_City') {
                $tz = '-6:00';
            }
        }

        if ($tz !== null) {
            $start_date = new Carbon($start_date->toDateTimeString(), $tz);
            $end_date = new Carbon($end_date->toDateTimeString(), $tz);
            $opens_at = new Carbon($opens_at->toDateTimeString(), $tz);
            $closes_at = new Carbon($closes_at->toDateTimeString(), $tz);
            $cancellable_until = new Carbon($cancellable_until->toDateTimeString(), $tz);
        }

        return [
            'occur_date'        => $start_date->toIso8601String(),
            'room'              => $room->name ?? '',
            'status'            => 1,
            'length_in_minutes' => $length_in_minutes,
            'total_capacity'    => $meeting->capacity,
            'total_booked'      => $meeting->reservation()->count(),
            'product_id'        => $product_id,
            'cancellable_until' => $cancellable_until->toIso8601String(),
            'instructors'       => [
                [
                    'name'       => $meeting->staff->name ?? '',
                    'substitute' => false,
                ],
            ],
            'booking_window'    => [
                'opens_at'  => $opens_at->toIso8601String(),
                'closes_at' => $closes_at->toIso8601String(),
            ],
        ];
    }

    /**
     * @param Meeting $meeting
     * @param         $validator
     *
     * @return array|\ArrayAccess|mixed|null
     */
    public static function saveSlotFromMeeting(Meeting $meeting, &$validator)
    {
        $location = $meeting->location;
        if (!$location || !$location->getDotValue('extra_fields.gympass.gym_id')) {
            return null;
        }

        $slot = self::getGympassSlot($meeting);
        $update = !!$slot;

        return self::createSlotFromMeeting($meeting, $validator, $update);

    }

    /**
     * @param Meeting $meeting
     *
     * @return mixed|null
     */
    public static function getGympassSlot(Meeting $meeting)
    {
        $location = $meeting->location;
        $slot_id = $meeting->getDotValue('extra_fields.gympass.slot_id');
        if ($slot_id) {
            if ($location) {
                $class_id = self::getClassId($meeting, $location);
                $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
                if ($gym_id) {
                    $sdk = new GympassBookingAPI();

                    return $sdk->getSlot($location, $class_id, $slot_id);
                }
            }
        }

        return null;
    }

    /**
     * @param Meeting $meeting
     * @param         $validator
     * @param bool    $update
     *
     * @return array|\ArrayAccess|mixed|null
     */
    private static function createSlotFromMeeting(Meeting $meeting, &$validator, bool $update = false)
    {
        $data = self::formDataMeeting($meeting);

        $location = $meeting->location;
        if ($location) {
            $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
            if ($gym_id) {
                $class_id = self::getClassId($meeting, $location);
                if ($update) {
                    $slot_id = $meeting->getDotValue('extra_fields.gympass.slot_id');
                }

                if (!$class_id) {
                    $validator->after(function ($val) {
                        $val->errors()->add('gympass', __('gympass.errorServiceNotInGympass'));
                    });

                    return null;
                }

                $sdk = new GympassBookingAPI();
                $response = $update ?
                    $sdk->updateSlot($location, $class_id, $slot_id, $data) :
                    $sdk->createSlot($location, $class_id, $data);

                $errors = $sdk->getErrors();
                self::addErrors($errors, $validator);

                if (count($errors) > 0) return null;

                if (!!(array)$response) {
                    $results = $response->results;
                    if ($results && is_array($results) && isset($results[0])) {
                        $slot = $results[0];
                        $meeting->setDotValue('extra_fields.gympass.slot_id', $slot->id, true);

                        return $slot->id;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param Meeting  $meeting
     * @param Location $location
     *
     * @return mixed
     */
    public static function getClassId(Meeting $meeting, Location $location)
    {
        $service = $meeting->service;

        return $service->getGympassClassId($location);
    }

    /**
     * @param Meeting $meeting
     *
     * @return void|null
     */
    public static function patchSlotFromMeeting(Meeting $meeting)
    {
        $location = $meeting->location;
        if (!$location || !$location->getDotValue('extra_fields.gympass.gym_id')) {
            return null;
        }

        if (!$location->isGympassActive()) {
            return null;
        }

        $slot = self::getGympassSlot($meeting);
        if ($slot) {
            $data = [
                'total_capacity' => $meeting->capacity,
                'total_booked'   => $meeting->reservation()->count(),
            ];
            $class_id = self::getClassId($meeting, $location);
            $sdk = new GympassBookingAPI();
            $sdk->patchSlot($location, $class_id, $slot->id, $data);

            if (count($sdk->getErrors()) > 0) {
                return null;
            } else {
                return $slot->id;
            }
        }

        return null;
    }

    /**
     * @param Meeting $meeting
     *
     * @return bool|null
     */
    public static function deleteSlotFromMeeting(Meeting $meeting): ?bool
    {
        $location = $meeting->location;
        if (!$location || !$location->getDotValue('extra_fields.gympass.gym_id')) {
            return null;
        }

        $slot = self::getGympassSlot($meeting);
        if ($slot) {
            $class_id = self::getClassId($meeting, $location);
            $sdk = new GympassBookingAPI();
            $sdk->deleteSlot($location, $class_id, $slot->id);

            if (count($sdk->getErrors()) > 0) {
                return false;
            } else {
                $meeting->unsetDotValue('extra_fields.gympass.slot_id', true);

                return true;
            }
        }

        return false;
    }

    /**
     * @param Meeting $meeting
     *
     * @return array|\ArrayAccess|mixed|null
     */
    public static function regenerateSlotID(Meeting $meeting, &$validator)
    {
        if ($meeting->isGympassActive()) {
            $location = $meeting->location;
            $sdk = new GympassBookingAPI();
            $class_id = self::getClassId($meeting, $location);
            $response = $sdk->getSlots($location, $class_id, $meeting->start_date, $meeting->end_date);
            $room = $meeting->room;
            if ($response && $sdk->getCode() >= 200 && $sdk->getCode() < 300) {
                $results = $response->results ?? null;

                if (is_array($results)) {
                    if (count($results) >= 1) {
                        $current_slot = (function () use ($results, $room) {
                            $return = null;
                            if (count($results) === 1) {
                                $return = array_first($results);
                            } else {
                                foreach ($results as $result) {
                                    if (strtoupper($result->room ?? '') === strtoupper($room->name)) {
                                        $return = $result;
                                        break;
                                    }
                                }
                            }

                            return $return;
                        })();

                        if ($current_slot) {
                            $slot_id = $current_slot->id ?? null;
                            if ($slot_id) {
                                $del_sdk = new GympassBookingAPI();
                                $del_sdk->deleteSlot($location, $class_id, $slot_id);
                                if ($del_sdk->getCode() >= 200 && $del_sdk->getCode() < 300) {
                                    $meeting->unsetDotValue('extra_field.gympass.slot_id', true);

                                    return self::saveSlotFromMeeting($meeting, $validator);
                                }
                            } else {
                                $meeting->unsetDotValue('extra_field.gympass.slot_id', true);

                                return self::saveSlotFromMeeting($meeting, $validator);
                            }
                        }
                    } else {
                        $meeting->unsetDotValue('extra_fields.gympass.slot_id', true);

                        return self::saveSlotFromMeeting($meeting, $validator);
                    }
                }
            }
        }

        return null;
    }
}
