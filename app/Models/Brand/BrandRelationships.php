<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 12/04/2018
 * Time: 09:21 AM
 */

namespace App\Models\Brand;

use App\Librerias\Permissions\Role;
use App\Models\Catalogs\CatalogsFieldsValues;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\CountryState;
use App\Models\Currency\Currencies;
use App\Models\Language\Language;
use App\Models\Location\Location;
use App\Models\Mails\MailsInvitationConfirm;
use App\Models\Mails\MailsWaitlistCancel;
use App\Models\Mails\MailsWaitlistConfirm;
use App\Models\Payment\PaymentType;
use App\Models\Purchase\MailsPurchase;
use App\Models\Reservation\ReservationMailCancel;
use App\Models\Reservation\ReservationMailConfirm;
use App\Models\Subscriptions\MailSubscriptionPayment;
use App\Models\Subscriptions\MailSubscriptionPaymentFailed;
use Carbon\Carbon;


trait BrandRelationships
{
    /**
     * Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }


    /**
     * Locations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'brands_id');
    }


    /**
     * @return mixed
     */
    public function getPosibleRoles()
    {
        $rolesCompany = $this->company->getPosibleRoles();

        return $rolesCompany->concat($this->roles);
    }

    /**
     * Roles
     */
    public function roles()
    {
        return $this->morphMany(Role::class, 'owner')->orderBy('name', 'desc');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    public function is24hrsFormat()
    {
        return $this->time_format === "24";
    }

    /**
     * @return mixed
     */
    public function currency()
    {
        return $this->belongsTo(Currencies::class, 'currencies_id');
    }

    /**
     * @return mixed
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * @return mixed
     */
    public function payment_types()
    {
        return $this->belongsToMany(PaymentType::class, 'brands_payment_types', 'brands_id', 'payment_types_id')
            ->withPivot('config', 'front', 'back')
            ->orderBy('payment_types.order', 'asc');
    }

    /**
     * @param bool $isAdmin
     *
     * @return
     */
    public function validPaymentTypes(bool $isAdmin = false)
    {
        $column = $isAdmin ? 'back' : 'front';

        return $this->payment_types()->where("brands_payment_types.$column", true)->get();
    }

    /**
     * @return bool
     */
    public function isForze()
    {
        return $this->waiver_forze === true;
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'countries_id');
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id');
    }

//    public function citie()
//    {
//        return $this->belongsTo(Cities::class, 'cities_id');
//    }

    public function mailReservationCancel()
    {
        return $this->hasOne(ReservationMailCancel::class, 'brands_id');
    }

    public function mailReservationConfirm()
    {
        return $this->hasOne(ReservationMailConfirm::class, 'brands_id');
    }

    public function mailPurchaseConfirm()
    {
        return $this->hasOne(MailsPurchase::class, 'brands_id');
    }

    /**
     * @return Carbon
     */
    public function now(): Carbon
    {
        return Carbon::now($this->time_zone);
    }

    /**
     * @return mixed
     */
    public function getTimezone(): string
    {
        return $this->time_zone;
    }

    public function usesWaitlist()
    {
        return $this->waitlist === true || $this->waitlist === 1;
    }

    public function getWaitlistAttribute($value)
    {
        return $value === 1 || $value === true;
    }

    /**
     * @return int
     */
    public function getMaxPercentNumberOfWaitlistMembers(): int
    {
        $maximo = $this->max_waitlist;

        return is_null($maximo) ? 100 : (int)$maximo;
    }

    public function mailWaitlistCancel()
    {
        return $this->hasOne(MailsWaitlistCancel::class, 'brands_id');
    }

    public function mailWaitlistConfirm()
    {
        return $this->hasOne(MailsWaitlistConfirm::class, 'brands_id');
    }


    /**
     * @return mixed
     */
    public function mailInvitationConfirm()
    {
        return $this->hasOne(MailsInvitationConfirm::class, 'brands_id');
    }

    public function fields_values()
    {
        return $this->hasMany(CatalogsFieldsValues::class, 'model_id')->whereHas('group.catalog', function ($q) {
            $q->where('table', 'brands');
        });
    }

    public function mailSubscriptionPaymentConfirm()
    {
        return $this->hasOne(MailSubscriptionPayment::class, 'brands_id');
    }

    public function mailSubscriptionPaymentFailed()
    {
        return $this->hasOne(MailSubscriptionPaymentFailed::class, 'brands_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gympassActiveLocations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->locations()->whereJsonContains('extra_fields', ['gympass' => ['active' => 1]]);
    }
}
