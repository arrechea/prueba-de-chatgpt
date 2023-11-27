<?php

namespace App\Librerias\Gympass\Helpers;

use App\Librerias\Gympass\SDK\GympassBookingAPI;
use App\Librerias\Gympass\SDK\GympassBookingValidateAPI;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Reservation\LibReservation;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;
use App\Models\Service;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Conekta\Log;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\str;

abstract class GympassAPIBookingFunctions
{
    private const ALLOWED_STATUS = [
        2 => self::RESERVED_STATUS,
        3 => self::REJECTED_STATUS,
        5 => self::CANCELLED_STATUS,
    ];

    private const RESERVED_STATUS = 'RESERVED';
    private const REJECTED_STATUS = 'REJECTED';
    private const CANCELLED_STATUS = 'CANCELLED_BY_GYM';

    private const ALLOWED_REASON_CATEGORIES = [
        self::CLASS_FULL_REASON,
        self::USAGE_RESTRICTION_REASON,
        self::ALREADY_BOOKED_REASON,
        self::SPOT_UNAVAILABLE_REASON,
        self::USER_NOT_EXIST_REASON,
        self::WINDOW_CLOSED_REASON,
        self::CLASS_CANCELLED_REASON,
        self::CLASS_NOT_FOUND_REASON,
        self::USER_PROFILE_CMS_REASON,
        self::TECHNICAL_ERROR_REASON,
        self::PREREQUISITES_REASON,
        self::GENERAL_ERROR_REASON,
    ];

    private const CLASS_FULL_REASON = 'CLASS_IS_FULL';
    private const USAGE_RESTRICTION_REASON = 'USAGE_RESTRICTION';
    private const ALREADY_BOOKED_REASON = 'USER_IS_ALREADY_BOOKED';
    private const SPOT_UNAVAILABLE_REASON = 'SPOT_NOT_AVAILABLE';
    private const USER_NOT_EXIST_REASON = 'USER_DOES_NOT_EXIST';
    private const WINDOW_CLOSED_REASON = 'CHECK_IN_AND_CANCELATION_WINDOWS_CLOSED';
    private const CLASS_CANCELLED_REASON = 'CLASS_HAS_BEEN_CANCELED';
    private const CLASS_NOT_FOUND_REASON = 'CLASS_NOT_FOUND';
    private const USER_PROFILE_CMS_REASON = 'USER_PROFILE_CMS';
    private const TECHNICAL_ERROR_REASON = 'TECHNICAL_ERROR';
    private const PREREQUISITES_REASON = 'PREREQUISITES';
    private const GENERAL_ERROR_REASON = 'GENERAL_ERROR';

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
     * @param Location $location
     * @param          $validator
     * @param string   $booking_number
     * @param string   $status
     * @param string   $reason_category
     * @param string   $reason
     *
     * @return bool
     */
    public static function patchBookingRequest(Location $location, &$validator, string $booking_number, string $status, string $reason_category = '', string $reason = '')
    {
        $sdk = new GympassBookingValidateAPI();
        if (in_array($status, self::ALLOWED_STATUS)) {
            $data = [
                'status' => $status,
            ];

            if ($status === self::REJECTED_STATUS) {
                if (in_array($reason_category, self::ALLOWED_REASON_CATEGORIES)) {
                    $data['reason'] = $reason;
                    $data['reason_category'] = $reason_category;
                } else {
                    self::addErrors([__('gympass.notValidReasonCategory')], $validator);

                    return false;
                }
            }

            $sdk->patchBooking($location, $booking_number, $data);
            $errors = $sdk->getErrors();
            if (count($errors) > 0) {
                self::addErrors($errors, $validator);

                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $event_data
     *
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateBookingRequest(array $event_data): bool
    {
        $status = self::REJECTED_STATUS;
        $reason_category = null;
        $reason = '';
        $slot = $event_data['slot'] ?? null;
        $json_data = json_encode($event_data);
        \Log::info("Booking Request received from Gympass with data: $json_data");
        if ($slot) {
            $gym_id = $slot['gym_id'] ?? null;
            if (!$gym_id) {
                $reason_category = self::TECHNICAL_ERROR_REASON;
                $reason = 'Gym ID not associated to any locations.';
            } else {
                $location = Location::whereDotColumn('extra_fields.gympass.gym_id', (string)$gym_id, true)->first();
                if (!$location) {
                    $location = Location::whereDotColumn('extra_fields.gympass.gym_id', $gym_id, true)->first();
                }
                if (!$location || !$location->isGympassActive()) {
                    $reason_category = self::TECHNICAL_ERROR_REASON;
                    $reason = 'Location inactive';
                } else {
                    $slot_id = $slot['id'] ?? null;
                    $meeting = Meeting::whereDotColumn('extra_fields.gympass.slot_id', $slot_id)
                        ->with(['map.objects.positions', 'reservation'])->first();
                    if (!$meeting) {
                        $meeting = Meeting::whereDotColumn('extra_fields.gympass.slot_id', (string)$slot_id)
                            ->with(['map.objects.positions', 'reservation'])->first();
                    }
                    if (!$meeting) {
                        $reason_category = self::CLASS_NOT_FOUND_REASON;
                        $reason = 'Slot ID not associated to any meeting';
                    } else {
                        if ($meeting->isFull()) {
                            $reason_category = self::CLASS_FULL_REASON;
                            $reason = 'Meeting associated to Slot ID is full';
                        } else {
                            $user_data = $event_data['user'] ?? null;
                            $json_user_data = json_encode($user_data);
                            if (!$user_data || !isset($user_data['unique_token'])) {
                                $reason_category = self::USER_NOT_EXIST_REASON;
                                $reason = "Sent User data incomplete. Data sent: $json_user_data";
                            } else {
                                if (!isset($user_data['unique_token'])) {
                                    $reason_category = self::USER_NOT_EXIST_REASON;
                                    $reason = 'No user token sent';
                                } else {

                                    $user = UserProfile::whereJsonContains('extra_fields', ['gympass' => ['gympass_id' => $user_data['unique_token']]])
                                        ->where('companies_id', $location->companies_id)->first();
                                    if (!$user) {
                                        if (!isset($user_data['email'])) {
                                            $reason_category = self::USER_NOT_EXIST_REASON;
                                            $reason = "Sent User data incomplete. Data sent: $json_user_data";
                                        } else {
                                            $req = new Request();
                                            $pass = str_random();
                                            if (!isset($user_data['first_name']) && isset($user_data['name'])) {
                                                $user_data['first_name'] = $user_data['name'];
                                            }
                                            $req->merge($user_data);
                                            $user = LibUsers::createProfileByEmailAndCompany($req, $user_data['email'], $location->company, $pass);
                                            if (!$user) {
                                                $reason_category = self::USER_PROFILE_CMS_REASON;
                                                $reason = 'User unable to be created';
                                            } else {
                                                $user->setDotValue('extra_fields.gympass.gympass_id', $user_data['unique_token'], true);
                                            }
                                        }
                                    }

                                    if ($user) {
                                        $reservationData = [
                                            'users_id'              => $user->users_id,
                                            'user_profiles_id'      => $user->id,
                                            'meetings_id'           => $meeting->id,
                                            'meeting_start'         => $meeting->start_date,
                                            'cancelation_dead_line' => $meeting->getCancelationDeadLine(),
                                            'rooms_id'              => $meeting->rooms_id,
                                            'locations_id'          => $meeting->locations_id,
                                            'brands_id'             => $meeting->brands_id,
                                            'companies_id'          => $meeting->companies_id,
                                            'staff_id'              => $meeting->staff_id,
                                            'buyer_staff_id'        => null,
                                            'services_id'           => $meeting->services_id,
                                        ];

                                        if ($meeting->isPast()) {
                                            $reason_category = self::WINDOW_CLOSED_REASON;
                                            $reason = 'Booking window is past';
                                        } else {
                                            $selectedMapObject = null;
                                            if ($meeting->map) {
                                                $reservations = $meeting->reservation->pluck('maps_objects_id')->values()->toArray();
                                                $objects = $meeting->map->objects->whereNotIn('id', $reservations)->sortByDesc('position_number');
                                                foreach ($objects as $object) {
                                                    $position = $object->positions;
                                                    if ($position->type === 'public') {
                                                        $selectedMapObject = $object;
                                                        break;
                                                    }
                                                }
                                                if ($selectedMapObject) {
                                                    $reservationData['maps_objects_id'] = $selectedMapObject->id;
                                                    $reservationData['maps_id'] = $selectedMapObject->maps_id;
                                                    $reservationData['meeting_position'] = $selectedMapObject->position_number;
                                                } else {
                                                    $reason_category = self::CLASS_FULL_REASON;
                                                    $reason = 'Ássociated meeting is full';
                                                }
                                            }

                                            $booking_number = $slot['booking_number'];

                                            $booking_reservation = Reservation::whereJsonContains('extra_fields', ['gympass' => ['booking_number' => $booking_number]])
                                                ->first();
                                            if ($booking_reservation) {
                                                $reservation_date = new Carbon($booking_reservation->created_at);
                                                if (Carbon::now()->diffInSeconds($reservation_date) <= 90) {
                                                    \Log::info('Request ignored due to being repeated. Nothing sent back.');
                                                    return false;
                                                }
                                                $reason_category = self::ALREADY_BOOKED_REASON;
                                                $reason = 'Booking number already in use';
                                            } else {
                                                if ($reason_category === null) {

                                                    \Log::info('reservation creation start');
                                                    $reservation = Reservation::makeReservation($reservationData);
                                                    if (!$reservation) {
                                                        $reason_category = self::GENERAL_ERROR_REASON;
                                                        $reason = 'Booking unable to be created';
                                                        \Log::error('general error');
                                                    } else {
                                                        $reservation->setDotValue('extra_fields.gympass.booking_number', $slot['booking_number'], true);
                                                        $status = self::RESERVED_STATUS;
                                                        \Log::info('Reserved');
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $reason_category = self::USER_PROFILE_CMS_REASON;
                                        $reason = $reason !== '' ? $reason : 'User unable to be created';
                                    }
                                }
                            }
                        }
                    }

                    $data = [];
                    $data['status'] = $status;
//                    $data['class_id'] = $meeting->service->getDotValue('extra_fields.gympass.class_id');
                    if ($status !== self::RESERVED_STATUS) {
                        $data['reason_category'] = $reason_category;
                        $data['reason'] = $reason;
                        \Log::error("Error al intentar crear reservación de Gympass. Reason: $reason | Reason Category:$reason_category");
                    }

//                    $data = self::parseData($data, $location, $event_data);
                    $sdk = new GympassBookingValidateAPI();
                    $sdk->patchBooking($location, $slot['booking_number'], $data);

                    return true;
                }
            }
        } else {
            $reason_category = self::CLASS_NOT_FOUND_REASON;
            $reason = 'Slot information missing.';
        }
        \Log::error("Error al intentar crear reservación de Gympass. Reason: $reason | Reason Category:$reason_category");

        return false;
    }

    /**
     * @param array   $data
     * @param Company $company
     * @param array   $event_data
     *
     * @return array
     */
    private static function parseData(array $data, Company $company, array $event_data)
    {
        return $data;

//        if ($company->isGympassProduction()) {
//            return $data;
//        } else {
//            $return_data = [];
//            $status = $data['status'] ?? null;
//            if ($status) {
//                $allowed_statuses = self::ALLOWED_STATUS;
//                $code = array_search($status, $allowed_statuses);
//                if ($code !== false) {
//                    $return_data['status'] = $code;
//                }
//            } else {
//                $return_data['status'] = array_search(self::REJECTED_STATUS, self::ALLOWED_STATUS);
//            }
//            $reason_category = $data['reason_category'] ?? null;
//            if ($reason_category) {
//                $return_data['reason'] = $reason_category;
//            }
//
//            $class_id = $event_data['slot']['class_id'] ?? null;
//            if ($class_id) {
//                $return_data['class_id'] = $class_id;
//            }
//
//            return $return_data;
//        }
    }

    /**
     * @param array $event_data
     *
     * @return void
     */
    public static function processCancellationRequest(array $event_data)
    {
        $slot = $event_data['slot'] ?? null;
        if ($slot) {
            $booking_number = $slot['booking_number'] ?? null;
            $reservation = Reservation::whereJsonContains('extra_fields', ['gympass' => ['booking_number' => $booking_number]])->first();
            if ($reservation) {
                $reservation->setDotValue('extra_fields.gympass.cancelled', 1, true);
                $reservation->cancel(true);
                \Log::info("Se envió la solicitud de cancelación de la reserva #{$reservation->id} - $booking_number");
            } else {
                \Log::error("No existe reservación con número $booking_number");
            }
        }
    }

    /**
     * @param Reservation $reservation
     *
     * @return void
     */
    public static function updateAfterReservationCancellation(Reservation $reservation)
    {
        $meeting = $reservation->meetings;
        $booking_number = $reservation->getDotValue('extra_fields.gympass.booking_number');
        $is_cancelled = $reservation->getDotValue('extra_fields.gympass.cancelled');

        $validator = \Validator::make([], []);
        if ($meeting) {
            if ($booking_number && $is_cancelled != 1) {
                GympassAPIBookingFunctions::patchBookingRequest($meeting->location, $validator, $booking_number, self::CANCELLED_STATUS, self::CLASS_CANCELLED_REASON);
            }

            GympassAPISlotsFunctions::patchSlotFromMeeting($meeting);
        }
    }
}
