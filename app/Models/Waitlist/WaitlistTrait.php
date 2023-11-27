<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 03/10/2018
 * Time: 09:17 AM
 */

namespace App\Models\Waitlist;


use App\Events\Waitlist\WaitlistCanceled;
use App\Events\Waitlist\WaitlistCreated;
use App\Librerias\Helpers\EmptyRelation;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Location\Location;
use App\Models\Maps\MapsObject;
use App\Models\Meeting\Meeting;
use App\Models\Membership\Membership;
use App\Models\Reservation\Reservation;
use App\Models\Service;
use App\Models\Staff\Staff;
use App\Models\User\UserProfile;
use App\Models\User\UsersCredits;
use App\Models\User\UsersMemberships;
use App\User;

trait WaitlistTrait
{
    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meetings_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservations_id');
    }

    /**
     * @return mixed
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->withTrashed();
    }

    public function meetings()
    {
        return $this->belongsTo(Meeting::class, 'meetings_id')->withTrashed();
    }

    /**
     * @return null
     */
    public function object()
    {
        return new EmptyRelation($this->newQuery(), $this, '', '');
    }

    /**
     * @return mixed
     */
    public function user_credits()
    {
        return $this->belongsToMany(UsersCredits::class, 'waitlist_credits', 'waitlist_id', 'users_credits_id');
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
    public function user_membership()
    {
        return $this->belongsTo(UsersMemberships::class, 'memberships_id');
    }

    /**
     * @return mixed
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credits_id');
    }

    /**
     * @return mixed
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (Waitlist $waitlist) {
            $waitlist->load('profile');
            event(new WaitlistCreated($waitlist, $waitlist->profile));
        });
    }

    /**
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        return
            $this->status === 'waiting'
            ||
            (
                $this->status === 'overbooking'
                &&
                !$this->meeting->isPast()
            );
    }

    /**
     * Cancelación de los créditos asociados a un waitlist con estatus de espera o de overbooking
     */
    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $credits = $this->user_credits;
            foreach ($credits as $credit) {
                $credit->used = false;
                $credit->save();
            }
            $this->status = 'returned';
            $this->save();
            event(new WaitlistCanceled($this, $this->profile));
        }
    }

    /**
     * @return bool
     */
    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    /**
     * @return mixed
     */
    public function waitlist_credits()
    {
        return $this->hasMany(WaitlistCredits::class);
    }

    /**
     * @param Reservation $reservation
     */
    public function approveFromReservation(Reservation $reservation)
    {
        if (
            $this->isWaiting()
            &&
            $reservation->isCancelled()
        ) {
            $reservationData = $this->toArray();

            $reservationData['meeting_position'] = $reservation->meeting_position;
            $reservationData['maps_id'] = $reservation->maps_id;
            $reservationData['maps_objects_id'] = $reservation->maps_objects_id;
            $newReservation = Reservation::makeReservation($reservationData, $this->user_credits);

            if ($newReservation) {
                $this->status = 'completed';
                $this->reservations_id = $newReservation->id;
                $this->save();
            }
        }
    }

    /**
     * Si es un elemento de la lista de espera y no ha terminado el meeting, pasar a overbooking
     */
    public function passToOverbooking()
    {
        if ($this->status === 'waiting' && !$this->meeting->isEnd()) {
            $this->status = 'overbooking';
            $this->save();
        }
    }
}
