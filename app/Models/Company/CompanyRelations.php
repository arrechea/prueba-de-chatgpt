<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 15/03/18
 * Time: 17:15
 */

namespace App\Models\Company;


use App\Admin;
use App\Librerias\Gympass\Helpers\GympassHelpers;
use App\Librerias\Permissions\Role;
use App\Models\AuthClient\AuthClient;
use App\Models\Brand\Brand;
use App\Models\CompaniesColors\companiesColors;
use App\Models\Countries;
use App\Models\CountryState;
use App\Models\Language\Language;
use App\Models\Location\Location;
use App\Models\Mails\MailsForgetPassword;
use App\Models\Mails\MailsImportUser;
use App\Models\Mails\MailsWelcome;
use App\Models\User\UserCategory;
use App\Models\CompaniesWebhooks\CompaniesWebhooks;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait CompanyRelations
{

    /**
     * Relation uses for permissions
     *
     * @return Collection
     */
    public function childsLevels(): Collection
    {
        $brands = $this->brands;
        $locations = $this->locations;

        $childs = new Collection();
        $brands->each(function ($brand) use ($childs) {
            $childs->push($brand);
        });
        $locations->each(function ($location) use ($childs) {
            $childs->push($location);
        });

        return $childs;
    }

    /**
     * Brands
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function brands()
    {
        return $this->hasMany(Brand::class, 'companies_id');
    }

    /**
     * Marcas activas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function active_brands()
    {
        return $this->brands()->where('status', 'active');
    }

    /**
     * Locations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'companies_id');
    }

    /**
     * Ubicaciones activas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function active_locations()
    {
        return $this->locations()->where('status', 'active');
    }

    /**
     * Roles
     */
    public function roles()
    {
        return $this->morphMany(Role::class, 'owner')->orderBy('name', 'desc');
    }

    /**
     * Company's Roles and GafaFitRoles
     */
    public function getPosibleRoles()
    {
        $roles = $this->roles;
        $rolesGafaFit = Role::whereNull('owner_type')->orderBy('name', 'desc')->get();

        return $rolesGafaFit->concat($roles);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'countries_id');
    }

    public function contact()
    {
        return $this->belongsTo(Admin::class, 'admins_id');
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id');
    }

    public function mailWelcomeInfo()
    {
        return $this->hasOne(MailsWelcome::class, 'companies_id');
    }

    public function mailForgotPassword()
    {
        return $this->hasOne(MailsForgetPassword::class, 'companies_id');
    }

    public function mailImportUser()
    {
        return $this->hasOne(MailsImportUser::class, 'companies_id');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    public function client()
    {
        return $this->hasOne(AuthClient::class, 'companies_id');
    }

    public function companyColor()
    {
        return $this->hasOne(companiesColors::class, 'companies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_categories(): HasMany
    {
        return $this->hasMany(UserCategory::class, 'companies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(CompaniesWebhooks::class, 'companies_id');
    }

    /**
     * @return bool
     */
//    public function isGympassActive(): bool
//    {
//        $extra_fields = $this->extra_fields;
//
//        return config('gympass.is_active', false) == 1 &&
//            Arr::get($extra_fields, 'gympass.active', false) == 1 &&
//            $this->getDotValue('extra_fields.gympass.approved') == 1;
//    }

    public function isGympassActive(): bool
    {
        return config('gympass.is_active', false) == 1 &&
            $this->getDotValue('extra_fields.gympass.active') == 1;
    }

    /**
     * @return bool
     */
    public function isMarkedGympassActive(): bool
    {
        return config('gympass.is_active', false) == 1 &&
            $this->getDotValue('extra_fields.gympass.active') == 1;
    }

    /**
     * @return bool
     */
    public function isGympassProduction(): bool
    {
        $extra_fields = $this->extra_fields;

        return config('gympass.is_production', false) == 1 && Arr::get($extra_fields, 'gympass.is_production', false) == 1;
    }

    /**
     * @return bool
     */
    public function isCheckinAutomatic(): bool
    {
        return $this->getDotValue('extra_fields.gympass.checkin_type') === GympassHelpers::CHECKIN_TYPE_AUTOMATIC;
    }

    /**
     * @return bool
     */
    public function isGympassPendingApproval(): bool
    {
        return $this->getDotValue('extra_fields.gympass.approved') != 1;
    }

    /**
     * @return bool
     */
    public function isGympassApproved(): bool
    {
        return $this->getDotValue('extra_fields.gympass.approved') == 1;
    }

    /**
     * @return bool
     */
    public function isGympassEmailSent(): bool
    {
        return $this->getDotValue('extra_fields.gympass.email_sent') == 1;
    }
}
