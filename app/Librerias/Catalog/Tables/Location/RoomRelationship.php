<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 16/05/2018
 * Time: 03:23 PM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Models\Brand\Brand;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;

trait RoomRelationship
{
    public function isActive()
    {
        return $this->status === "active";
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'rooms_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'rooms_id', 'id');
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id', 'id')->withTrashed();
    }
}
