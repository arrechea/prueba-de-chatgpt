<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 25/05/18
 * Time: 12:52
 */

namespace App\Librerias\Reservation;

use App\Events\Reservations\ReservationByInvitationCreated;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Location\Location;
use App\Models\Maps\MapsObject;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;
use App\Models\User\UserProfile;
use App\Models\Waitlist\Waitlist;
use App\Models\Waitlist\WaitlistCredits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator;

class LibReservation
{
    /**
     * @var UserProfile
     */
    private $userProfile;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Meeting
     */
    private $meeting;

    /**
     * @var AdminProfile
     */
    private $admin;
    /**
     * @var bool
     */
    private $inAdmin = false;
    /**
     * @var Reservation
     */
    private $reservation;
    /**
     * @var Credit
     */
    private $credit;
    /**
     * @var MapsObject
     */
    private $mapPositions;

    private function __construct(Request $request, UserProfile $userProfile, Meeting $meeting)
    {
        $this->request = $request;
        $this->userProfile = $userProfile;
        $this->meeting = $meeting;
    }

    /**
     * @param Request  $request
     *
     * @param Company  $company
     * @param Location $location
     * @param bool     $isAdmin
     *
     * @return Collection
     * @throws ValidationException
     */
    static public function create(Request $request, Company $company, Location $location, bool $isAdmin): Collection
    {
        //Get variables
        $admin = null;
        $selectedMeeting = Meeting::where('id', $request->get('meetings_id'))
            ->where('locations_id', $location->id)
            ->first();

        if ($selectedMeeting->reserve_auto && $selectedMeeting->recurring_meeting_id != null) {
            $meetings = Meeting::where('recurring_meeting_id', $selectedMeeting->recurring_meeting_id)
                ->withCount('reservation')
                ->get();
        } else {
            $meetings = Meeting::where('id', $selectedMeeting->id)
                ->withCount('reservation')
                ->get();
        }

        //Init reservation process
        $newReservations = new \Illuminate\Database\Eloquent\Collection([]);
        if ($isAdmin) {
            //Es administrador
            $admin = Auth::user()->getProfileInThisCompany();
            $user = UserProfile::where('id', $request->get('users_id'))
                ->where('companies_id', $company->id)
                ->first();
        } else {
            //No es administrador
            $user = Auth::user()->getProfileInThisCompany();
        }

        $credit = null;
        if ($request->has('credits_id')) {
            $credit = Credit::where('id', $request->get('credits_id'))
                ->where('status', 'active')
                ->first();
        }
        $validatorWithErrors = null;
        foreach ($meetings as $meeting) {
            $mapPositions = null;

            $validator = LibReservation::validateRequest($request, $company, $location, false);
            $validator->after(function ($validator) use ($meeting, $isAdmin, $user) {
                $canAcceptPeople = $meeting->canAcceptPeople($isAdmin, $validator);

                $val=Validator::make([],[]);
                if (!$meeting->canAcceptReservations($val, $isAdmin) && $canAcceptPeople) {
                    //Comprobamos si el usuario ya no tiene un compromiso previo
                    if ($user->hasSomeKindOfReservationInSameTime($meeting)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.isFull'));
                    }
                }
                if (!$isAdmin && $meeting->isEnd()) {
                    $validator->errors()->add('meetings_id.passed', __('messages.reservation-meetings_id'));
                } else if (!$meeting->has('room') || !$meeting->room->isActive()) {
                    $validator->errors()->add('room', __('messages.reservation-room'));
                }
            });

            if (
                $request->has('map_objectsSelected')
                &&
                $meeting
            ) {
                $mapObjectInsecure = new Collection($request->get('map_objectsSelected', []));
                if ($mapObjectInsecure->count()) {
                    $mapObjectInsecureIds = $mapObjectInsecure->map(function ($object) {
                        return $object['id'];
                    })->toArray();

                    //Obtenemos ids Seguros
                    $mapPositions = MapsObject::whereIn('id', $mapObjectInsecureIds)
                        ->where('maps_id', $meeting->maps_id)
                        ->get();

                    $mapObjectsSecureIds = $mapPositions
                        ->map(function ($mapOb) {
                            return $mapOb->id;
                        })
                        ->toArray();

                    // comprobar que no se haya reservado ya la misma posicion
                    $reservacionesConEsosIds = Reservation::where('cancelled', 0)
                        ->whereIn('maps_objects_id', $mapObjectsSecureIds)
                        ->where('meetings_id', $meeting->id)
                        ->get();

                    $validator->after(function ($validator) use ($reservacionesConEsosIds) {
                        if ($reservacionesConEsosIds->count() > 0) {
                            $validator->errors()->add('meetings_id', __('reservation-fancy.error.position.selectedYet'));
                        }
                    });
                }
            }
            if ($request->get('test', false) === 'true') {
                // parar aqui porque terminan validaciones
                return new Collection();
            }

            if (!$validator->fails()) {
                //Set variables
                $reserva = new LibReservation($request, $user, $meeting);

                if ($credit) {
                    $reserva->setCredit($credit);
                }
                if ($isAdmin && $admin) {
                    $reserva->setAdmin($admin);
                    $reserva->setInAdmin(true);
                }

                if ($mapPositions) {
                    $invited_data = $request->input('invited_data', []);
                    //reserva en loop
                    $mapPositions->each(function ($mapPosition, $key) use (&$reserva, &$newReservations, &$validator, $isAdmin, $invited_data) {
                        $reserva->setMapPositions($mapPosition);
                        $process = $reserva->process($validator, $isAdmin);
                        $newReservations->push($process);
                        if ($key > 0) {
                            if ($invited_data && isset($invited_data[ $key ]) && isset($invited_data[ $key ]['email'])) {
//                                todo: habilitar de nuevo cuando se resuelva la situación con los correos
//                                event(new ReservationByInvitationCreated($invited_data[ $key ]['email'], $invited_data[ $key ]['name'], $process));
                            }
                        }
                    });
                } else {
                    //reserva simple
                    $process = $reserva->process($validator, $isAdmin);
                    $newReservations->push($process);
                }
            } else {
                $validatorWithErrors = $validator;
            }
        }


        if (count($newReservations) == 0 && $validatorWithErrors != null) {
            throw new ValidationException($validatorWithErrors);
        }

        return $newReservations;
    }

    /**
     * @param \Illuminate\Validation\Validator $validator
     *
     * @param bool                             $isAdmin
     *
     * @throws ValidationException
     */
    private function checkMeeting(\Illuminate\Validation\Validator &$validator, bool $isAdmin = false)
    {
        $meeting = $this->getMeeting();
        /**
         * Check meeting full or is pass
         */
        $validator->after(function ($validator) use ($meeting, $isAdmin) {
            $meeting->canAcceptPeople($isAdmin, $validator);
        });
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Generate reservation
     *
     * Observaciones: el credito se refiere a la tabla credits. Eso luego seleccionará un users_credits para canjear
     *
     * 1. si se envio un membership o un credito (en ese orden) valido se usarán esos.
     * 2. si se envio un membership o un credito (en ese orden) invalido, o no se envio nada,
     * 2.1 se comprobará si el usuario tiene memberships validos para el meeting (se agarra el primero),
     * 2.2 sino luego se comprueba si tiene creditos validos para el meeting (se agarra el primero),
     * 3. Generar reservations con informacion en cuestion
     * 4. Si se uso un credito buscar el primer users_credit del usuario de ese tipo y pasarlo a used = true
     * 5. devolver informacion de reserva
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @param bool                             $isAdmin
     *
     * @return Reservation|Waitlist
     * @throws ValidationException
     */
    private function process(\Illuminate\Validation\Validator &$validator, bool $isAdmin = false)
    {
        $this->checkMeeting($validator, $isAdmin);

        $user = $this->getUserProfile();//User de la compra
        $staff = $this->getAdmin();//Admin de la compra (es nulo en front)

        $meeting = $this->getMeeting();//Meeting donde queremos ir
        $mapPositions = $this->getMapPositions();//posiciones a reservar
        $service = $meeting->service;//servicio del meeting
        $location = $meeting->location;

        $credit = $this->getCredit();//Aun el usuario no puede seleccionar el crédito pero si se podrá en un futuro
        $membership = null;

        //Data valid of user
        $validCredits = $user->validCreditsForServiceInLocation($location, $service);
        $validMemberships = $user->validMembershipsForServiceInLocation($location, $service);

        /*
         * Inicio chequeo validez membresia o credito
         */
        if ($validMemberships->count() > 0) {
            //User have membership
            foreach ($validMemberships as $validMembership) {
                if ($user->canUseUserMembershipInMeeting($validMembership, $meeting)) {
                    $membership = $validMembership;
                    $credit = null;
                    break;
                }
            }
        }

        if (!$membership && $validCredits->count() > 0) {
            //User usará créditos
            if ($credit) {
                dd('Aun no se hizo funcionalidad de seleccion de creditos');
            } else {
                $credit = $validCredits->first()->credit;
            }
        } else if (!$membership && $validCredits->count() < 1) {
            $validator->after(function ($validator) use ($meeting) {
                $validator->errors()->add('meetings_id', __('reservation-fancy.error.withoutCredits'));
            });
        }

        /*
         * Fin chequeo validez membresia o credito
         */
        //Generate reservation
        $reservationData = [
            'users_id'              => $user->users_id,
            'user_profiles_id'      => $user->id,
            'meetings_id'           => $meeting->id,
            'meeting_start'         => $meeting->start_date,
            'cancelation_dead_line' => $meeting->getCancelationDeadLine(),
            'rooms_id'              => $meeting->rooms_id,
            'locations_id'          => $location->id,
            'brands_id'             => $location->brands_id,
            'companies_id'          => $location->companies_id,
            'staff_id'              => $meeting->staff_id,
            'buyer_staff_id'        => $staff->id ?? null,
            'services_id'           => $service->id,
        ];

        //We have membership or credit correctly
        if ($membership) {
            $reservationData['memberships_id'] = $membership->id;
            if (!$user->canUseUserMembershipInMeeting($membership, $meeting)) {
                $validator->after(function ($validator) use ($meeting) {
                    $validator->errors()->add('meetings_id', __('reservation-fancy.error.membershipLimit'));
                });
            }
        }
        //Position in map
        if ($mapPositions) {
            $reservationData['maps_objects_id'] = $mapPositions->id;
            $reservationData['maps_id'] = $mapPositions->maps_id;
            $reservationData['meeting_position'] = $mapPositions->position_number;
            $validator->after(function ($validator) use ($mapPositions, $meeting) {
                $positions_ids = array_pluck($mapPositions, 'position_number');
                $reservations_same_position = Reservation::where([
                    ['meetings_id', $meeting->id],
                ])->whereIn('meeting_position', $positions_ids);
                if ($reservations_same_position->count()) {
                    $validator->errors()->add('position_number', __('reservation-fancy.error.positionOccupied'));
                }
            });
        }

        if ($credit) {
//            $creditosValidosDelUsuario = $user->validCreditsForServiceInLocation($location, $service);
            $creditosNecesariosPorGastar = $service->neccesaryCreditsByCreditType($credit);

            $reservationData['credits_id'] = $credit->id;
            $reservationData['credits'] = $creditosNecesariosPorGastar;

            //recorrer creditos que usara el usuario
            $creditsToUse = $user->getLastsCredits($credit, $creditosNecesariosPorGastar, $location->now());

//            $credits_c = CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
//
//            dd($credits_c);
            if (
                $creditsToUse->count() < $creditosNecesariosPorGastar
//                ||
//                $credits_c->count() <= 0
            ) {
                $validator->after(function ($validator) use ($meeting) {
                    $validator->errors()->add('meetings_id', __('reservation-fancy.error.withoutCredits'));
                });
                throw new ValidationException($validator);
            }
            $creditsToUse->each(function ($userCredit) {
                //descontar creditos de la cuenta del usuario
                $userCredit->markAsUsed();
            });
        }

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if (!$meeting->canAcceptReservations($validator, $isAdmin)) {
            $waitlist = $this->saveWaitlist($reservationData, $meeting, ($creditsToUse ?? null));
            $validator = Validator::make([], []);//hack, no eliminar

            return $waitlist;
        } else {
            /*
             * Save Reservation
             */
            $reservation = $this->saveReservation($reservationData, ($creditsToUse ?? null));

            return $reservation;
        }
    }

    /**
     * @param                 $reservationData
     * @param Meeting         $meeting
     * @param Collection|null $creditsToUse
     *
     * @return Waitlist
     */
    private function saveWaitlist($reservationData, Meeting $meeting, Collection $creditsToUse = null): Waitlist
    {
        if ($meeting->isValidForWaitlist()) {
            //waitlist
            $reservationData['status'] = 'waiting';
        } else {
            //overbooking
            $reservationData['status'] = 'overbooking';
        }

        $waitlist = new Waitlist($reservationData);
        $waitlist->save();

        if ($creditsToUse) {
            //registrarCreditosUsados
            $reservationCredits = $creditsToUse->map(function ($userCredit) use ($waitlist) {
                return [
                    'waitlist_id'      => $waitlist->id,
                    'users_credits_id' => $userCredit->id,
                ];
            });
            WaitlistCredits::insert($reservationCredits->toArray());
        }

        return $waitlist;
    }

    /**
     * @param $reservationData
     *
     * @param $creditsToUse
     *
     * @return Reservation
     */
    private function saveReservation($reservationData, Collection $creditsToUse = null): Reservation
    {
        return Reservation::makeReservation($reservationData, $creditsToUse);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Location $location
     *
     * @return \Illuminate\Validation\Validator
     * @throws ValidationException
     */
    static public function validateRequest(Request $request, Company $company, Location $location, bool $validate = true)
    {
        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($request->all(), [
            'users_id'            => [
                'required',
                Rule::exists('user_profiles', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                        $query->where('companies_id', $company->id);
                    }),
            ],
            'map_objectsSelected' => 'nullable|array',
            'credits_id'          => [
                'nullable',
                Rule::exists('credits', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                    }),
            ],
            'invitation_data'     => 'nullable|array',
        ], [
            'users_id.exists'    => __('messages.reservation-users_id'),
            'meetings_id.exists' => __('messages.reservation-meetings_id'),
            'credits_id.exists'  => __('messages.reservation-payment_types_id'),
        ]);

        if ($validate) {
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        return $validator;
    }

    /**
     * @return UserProfile
     */
    public function getUserProfile(): UserProfile
    {
        return $this->userProfile;
    }

    /**
     * @return Meeting
     */
    public function getMeeting(): Meeting
    {
        return $this->meeting;
    }

    /**
     * @return bool
     */
    public function isInAdmin(): bool
    {
        return $this->inAdmin;
    }

    /**
     * @param bool $inAdmin
     */
    private function setInAdmin(bool $inAdmin)
    {
        $this->inAdmin = $inAdmin;
    }

    /**
     * @return AdminProfile|null
     */
    public function getAdmin(): ?AdminProfile
    {
        return $this->admin;
    }

    /**
     * @param AdminProfile $admin
     */
    private function setAdmin(AdminProfile $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return Reservation
     */
    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    /**
     * @return Credit
     */
    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    /**
     * @param Credit $credit
     */
    private function setCredit(Credit $credit)
    {
        $this->credit = $credit;
    }

    /**
     * Funcion para imprimir proximas reservaciones de usuario
     *
     * @param UserProfile $profile
     *
     * @param Brand|null  $brand
     *
     * @return mixed
     */
    static public function userFuturesReservations(UserProfile $profile, Brand $brand = null)
    {
        $profile->load([
            'reservations.meetings',
        ]);
        $now = $brand ? $brand->now() : Carbon::now();
        $reservationsfutures = $profile->reservations()->whereHas('meetings', function ($query) use ($now) {
            $query->where('meetings.start_date', '>', $now);
        });

        if ($brand) {
            $reservationsfutures->where('reservations.brands_id', $brand->id);
        }

        return $reservationsfutures->get();
    }

    /**
     * @return MapsObject[]
     */
    public function getMapPositions()
    {
        return $this->mapPositions;
    }

    /**
     * @param MapsObject[] $mapPositions
     */
    public function setMapPositions($mapPositions)
    {
        $this->mapPositions = $mapPositions;
    }

}
