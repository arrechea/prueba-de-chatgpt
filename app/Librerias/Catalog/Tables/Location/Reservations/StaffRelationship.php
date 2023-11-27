<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 16/05/2018
 * Time: 03:59 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Reservations;


use App\Models\Brand\Brand;
use App\Models\Catalogs\CatalogsFieldsValues;
use App\Models\Countries;
use App\Models\CountryState;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;
use App\Models\Staff\StaffSpecialText;

trait StaffRelationship
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return mixed
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'staff_brands', 'staff_id', 'brands_id');
    }

    public function special_texts()
    {
        return $this->hasMany(StaffSpecialText::class, 'staff_id')->orderBy('order');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'countries_id');
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id');
    }

    public function reservations()
    {
        return$this->hasMany(Reservation::class, 'staff_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class,'staff_id');
    }

//    public function city()
////    {
////        return $this->belongsTo(Cities::class, 'cities_id');
////    }

    public function fields_values(){
        return $this->hasMany(CatalogsFieldsValues::class, 'model_id')->whereHas('group.catalog', function ($q) {
            $q->where('table', 'staff');
        });
    }
}
