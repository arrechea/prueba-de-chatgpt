<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 27/03/18
 * Time: 12:15
 */

namespace App\Models\Admin;


use App\Admin;
use App\Models\Cities;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\CountryState;
use Illuminate\Support\Facades\Hash;

trait AdminProfileTrait
{
    /**
     * @return mixed
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admins_id');
    }

    /**
     * @return mixed
     */
    public function country()
    {
        return $this->belongsTo(Countries::class, 'countries_id');
    }

    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * @param $email
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id');
    }

//    public function city()
//    {
//        return $this->belongsTo(Cities::class, 'cities_id');
//    }
}
