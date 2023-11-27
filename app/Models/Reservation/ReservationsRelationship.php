<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 30/05/2018
 * Time: 12:50 PM
 */

namespace App\Models\Reservation;


use App\Admin;
use App\Events\Reservations\ReservationCancelled;
use App\Events\Reservations\ReservationCreated;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Models\Brand\Brand;
use App\Models\Credit\Credit;
use App\Models\Location\Location;
use App\Models\Maps\Maps;
use App\Models\Maps\MapsObject;
use App\Models\Meeting\Meeting;
use App\Models\Room\Room;
use App\Models\Service;
use App\Models\Staff\Staff;
use App\Models\User\UserProfile;
use App\Models\User\UsersCredits;
use App\Models\User\UsersMemberships;
use App\Models\Waitlist\Waitlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

trait ReservationsRelationship
{
    /**
     * @return mixed
     */
    public function object()
    {
        return $this->belongsTo(MapsObject::class, 'maps_objects_id');
    }

    /**
     * Relation with meetings
     *
     * @return mixed
     */
    public function meetings()
    {
        return $this->belongsTo(Meeting::class, 'meetings_id')->withTrashed();
    }

    /**
     * Relation with users
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credits_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user_membership()
    {
        return $this->belongsTo(UsersMemberships::class, 'memberships_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function membership()
    {
        return $this->user_membership ? $this->user_membership->membership() : $this->user_membership();
    }

    /**
     * Relation reservation with credits
     *
     * @return mixed
     */
    public function userCredits()
    {
        return $this->belongsToMany(UsersCredits::class, 'reservation_credits', 'reservations_id', 'users_credits_id');
    }

    /**
     * Permission to cancel a reservation
     *
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        $user = Auth::user();
        /**
         * @var Carbon $deadline
         */
        if (
            $user instanceof Admin
            &&
            LibPermissions::userCan($user, LibListPermissions::RESERVATIONS_SPECIAL_CANCEL, $this->location)
        ) {
//            return !$this->meeting_start->isPast() && !$this->isCancelled();
            return !$this->isCancelled();
        }

        $deadline = $this->cancelation_dead_line;

        return !$deadline->isPast() && !$this->isCancelled();
    }

    /**
     * Reservation Cancel, returns credits to user.
     *
     * @param bool $forze
     */
    public function cancel($forze = false)
    {
        if (
            $this->canBeCancelled()
            ||
            $forze
        ) {

            $creditos = $this->userCredits;
            if ($creditos->count() > 0) {
                $creditos->each(function ($credito) {
                    $credito->used = false;
                    $credito->save();
                });
            }

            $this->cancelled = true;
            $this->save();

            event(new ReservationCancelled($this, $this->user));
        }
    }

    public function isCancelled()
    {
        return $this->cancelled === true || $this->cancelled === 1;
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id')->withTrashed();
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'rooms_id')->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

    public function tableModel()
    {
        return $this->hasOne(Reservation::class, 'id', 'id')->withTrashed();
    }

    /**
     * @param                 $reservationData
     * @param Collection|null $creditsToUse
     *
     * @return Reservation
     */
    public static function makeReservation($reservationData, Collection $creditsToUse = null): Reservation
    {
        $reservation = new Reservation($reservationData);
        $profile = $reservation->user;
        $reservation->user_profiles_categories = $profile->generateUserCategoriesString();
        $reservation->user_profiles_email = $profile->email ?? '';

        $reservation->save();

        if ($creditsToUse) {
            //registrarCreditosUsados
            $reservationCredits = $creditsToUse->map(function ($userCredit) use ($reservation) {
                return [
                    'reservations_id'  => $reservation->id,
                    'users_credits_id' => $userCredit->id,
                ];
            });
            ReservationCredit::insert($reservationCredits->toArray());
        }

        return $reservation;
    }

    /**
     * Lanza un evento ReservationCreated al crear una reservación
     */
    public static function boot()
    {
        parent::boot();

        static::created(function (Reservation $reservation) {
            $reservation->load('user');
            event(new ReservationCreated($reservation, $reservation->user));
        });
    }

    /**
     * @return mixed
     */
    public function waitlist()
    {
        return $this->hasOne(Waitlist::class, 'reservations_id');
    }

    public function room_map()
    {
        return $this->belongsTo(Maps::class, 'maps_id');
    }

    /**
     * @return bool
     */
    public function isGympass(): bool
    {
        return $this->getGympassBookingNumber() !== null;
    }

    /**
     * @return array|\ArrayAccess|mixed|null
     */
    public function getGympassBookingNumber()
    {
        return $this->getDotValue('extra_fields.gympass.booking_number');
    }
}
