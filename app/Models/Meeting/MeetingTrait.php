<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/05/2018
 * Time: 11:23 AM
 */

namespace App\Models\Meeting;


use App\Librerias\Gympass\Helpers\GympassAPISlotsFunctions;
use App\Models\Brand\Brand;
use App\Models\Catalogs\CatalogsFieldsValues;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Maps\Maps;
use App\Models\Reservation\Reservation;
use App\Models\Room\Room;
use App\Models\Service;
use App\Models\Staff\Staff;
use App\Models\Waitlist\Waitlist;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

trait MeetingTrait
{
    /**
     * @param array $values
     *
     * @return mixed
     */
    public function getArrayableItems(array $values)
    {
        //Fake attributes
        if (!in_array('is_valid_for_waitlist', $this->appends)) {
            $this->appends[] = 'is_valid_for_waitlist';
        }
        if (!in_array('is_valid_for_check_overbooking', $this->appends)) {
            $this->appends[] = 'is_valid_for_check_overbooking';
        }
        if (!in_array('waitlist_size', $this->appends)) {
            $this->appends[] = 'waitlist_size';
        }

        return parent::getArrayableItems($values);
    }

    /**
     * @return int
     */
    public function getWaitlistSizeAttribute(): int
    {
        return $this->waitlist_not_returned()->count();
    }

    /**
     * @return bool
     */
    public function getIsValidForWaitlistAttribute(): bool
    {
        return $this->isValidForWaitlist();
    }

    /**
     * @return bool
     */
    public function getIsValidForCheckOverbookingAttribute(): bool
    {
        return $this->isValidForOverbooking(true);
    }

    /**
     * @return bool
     */
    public function isValidForWaitlist(): bool
    {
        $brand = $this->brand;
        $usesWaitlist = $brand ? $brand->usesWaitlist() : false;
        if ($usesWaitlist && !$this->isEnd() && $this->isFull()) {
            if ($this->canCancellReservations()) {
                //si hay waitlist en marca y no paso el tiempo de cancelacion valido meter a waitlist
                if (!$this->hasReachWaitlistMaxims()) {
                    //si hay waitlist y NO se supera el maximo de waitlist...
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param bool $isAdmin
     *
     * @return bool
     */
    public function isValidForOverbooking(bool $isAdmin = false): bool
    {
        $brand = $this->brand;
        $usesWaitlist = $brand ? $brand->usesWaitlist() : false;
        if (
            $usesWaitlist
            &&
            (
            $this->isFull()
            )
            &&
            !$this->canCancellReservations()
            &&
            $isAdmin
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $isAdmin
     *
     * @return bool
     */
    public function isValidToPosibleInsertPeople(bool $isAdmin = false): bool
    {
        return $this->isValidForWaitlist() || $this->isValidForOverbooking($isAdmin);
    }

    /**
     * Relation with a service
     *
     * @return mixed
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id')->withTrashed();
    }

    /**
     * Relation with a reservation
     *
     * @return mixed
     */
    public function reservation()
    {
        return $this->hasMany(Reservation::class, 'meetings_id', 'id')
            ->where('cancelled', false);
    }

    /**
     * Relations with the  staff
     *
     * @return mixed
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->withTrashed();
    }

    /**
     * Relation with the room
     *
     * @return mixed
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'rooms_id', 'id')->withTrashed();
    }

    public function map()
    {
        return $this->belongsTo(Maps::class, 'maps_id', 'id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id', 'id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'locations_id', 'id')->withTrashed();
    }

    /**
     * Counts the reservations availables in a meeting
     *
     */
    public function available()
    {
        $count = isset($this->reservation_count) ? $this->reservation_count : $this->reservation()->count();

        $quantity = $this->capacity;

        return $quantity >= $count ? $quantity - $count : 0;
    }

    /**
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->available() <= 0;
    }

    /**
     * @return bool
     */
    public function isNotFull(): bool
    {
        return !$this->isFull();
    }

    /**
     * @return bool
     */
    public function isPast(): bool
    {
        return $this->start_date->isPast();
    }

    public function isEnd(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Clona el meeting actual y lo mete a la tabla
     *
     * @return mixed
     */
    public function cloneMeeting()
    {
        //copy attributes
        $new = $this->replicate();

        //save model before you recreate relations (so it has an id)
        $meeting = Meeting::where([
            ['rooms_id', $new->rooms_id],
            ['services_id', $new->services_id],
            ['staff_id', $new->staff_id],
            ['start_date', $new->start_date],
            ['end_date', $new->end_date],
        ])->first();
        if (!$meeting) {
            $new->push();
        }


        //Documentación
        //reset relations on EXISTING MODEL (this way you can control which ones will be loaded
//        $this->relations = [];
//
//        //load relations on EXISTING MODEL
//        $this->load('relation1','relation2');
//
//        //re-sync everything
//        foreach ($this->relations as $relationName => $values){
//            $new->{$relationName}()->sync($values);
//        }

        return $new;
    }

    /**
     * @param bool $forze
     */
    public function cancel($forze = false)
    {
        if (!$this->isPast()) {
            $this->cancelWaitlist();
            $reservations = $this->reservation;
            if ($reservations->count() > 0) {
                /**
                 * @var Reservation $reservation
                 */
                $reservations->each(function ($reservation) use ($forze) {
                    $reservation->cancel($forze);
                });
            }

            if ($this->isGympassActive()) {
                GympassAPISlotsFunctions::deleteSlotFromMeeting($this);
            }
        }
    }

    /**
     * @return mixed
     */
    public function waitlist()
    {
        return $this->hasMany(Waitlist::class, 'meetings_id');
    }

    /**
     * @return mixed
     */
    public function waitlist_not_returned()
    {
        return $this->waitlist()->where('status', '!=', 'returned')->where('status', '!=', 'completed');
    }

    /**
     * @return mixed
     */
    public function awaiting()
    {
        return $this->waitlist()->where('status', 'waiting');
    }

    /**
     * @return Carbon
     */
    public function getCancelationDeadLine(): Carbon
    {
        $brandDeadLine = $this->brand->cancelation_dead_line;

        return $this->start_date->subHours($brandDeadLine);
    }

    /**
     * @return bool
     */
    public function canCancellReservations(): bool
    {
        $deadLine = $this->getCancelationDeadLine();

        return !$deadLine->isPast();
    }

    /**
     * @return int
     */
    public function getMaxNumberOfWaitlistMembers(): int
    {
        $maxPercent = $this->brand->getMaxPercentNumberOfWaitlistMembers();
        $capacity = $this->capacity;

        return (int)($maxPercent * $capacity) / 100;
    }

    /**
     * @return bool
     */
    public function hasReachWaitlistMaxims(): bool
    {
        $maximInWaitlist = $this->getMaxNumberOfWaitlistMembers();
        $waitlistCount = $this->waitlist_not_returned()->count();

        return $waitlistCount >= $maximInWaitlist;
    }

    /**
     * The resulsts depends if is admin or not, the brands waitlist configuration
     *
     * @param bool           $isAdmin
     *
     * @param null|Validator $validator
     *
     * @return bool
     */
    public function canAcceptPeople(bool $isAdmin = false, Validator &$validator = null): bool
    {

        /**
         * @var Brand $brand
         */
        $meeting = $this;

//        if ($meeting && $meeting->isEnd()) {
//            if ($validator) {
//                $validator->errors()->add('meetings_id', __('reservation-fancy.error.isPast'));
//            }
//
//            return false;
//        }

        //Insert via overbooking or waitlist
        if ($this->isValidToPosibleInsertPeople($isAdmin)) {
            return true;
        }

        if ($meeting && $meeting->isFull()) {
            if ($validator) {
                $validator->errors()->add('meetings_id', __('reservation-fancy.error.isFull'));
            }

            return false;
        }

        return true;
    }

    /**
     * @param Validator|null $validator
     *
     * @return bool
     */
    public function canAcceptReservations(Validator &$validator = null, $isAdmin = false)
    {
        if ($this->isFull()) {
            if ($validator) {
                $validator->errors()->add('meetings_id', __('reservation-fancy.error.isFull'));
            }

            return false;
        }
        if ($this->isEnd() && !$isAdmin) {
            if ($validator) {
                $validator->errors()->add('meetings_id', __('reservation-fancy.error.isPast'));
            }

            return false;
        }

        return true;
    }

    /**
     * Cancela todos los créditos de la lista de 'awaiting' (waitlists con status de 'waiting') de el meeting
     */
    public function cancelWaitlist()
    {
        $waitlist = $this->awaiting;
        foreach ($waitlist as $item) {
            $item->cancel();
        }
    }

    /**
     * @return mixed
     */
    public function overbooking()
    {
        return $this->waitlist()->where('status', 'overbooking');
    }

    public function fields_values()
    {
        return $this->hasMany(CatalogsFieldsValues::class, 'model_id')->whereHas('group.catalog', function ($q) {
            $q->where('table', 'meetings');
        });
    }

    public function attendance()
    {
        return $this->reservation()->whereNotNull('attendance');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    /**
     * @return bool
     */
    public function isGympassActive(): bool
    {
        return $this->company->isGympassActive() && $this->getDotValue('extra_fields.gympass.active') == 1;
    }
}
