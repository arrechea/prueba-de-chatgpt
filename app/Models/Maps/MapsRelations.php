<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 27/08/2018
 * Time: 11:13 AM
 */

namespace App\Models\Maps;


use App\Models\Reservation\Reservation;

trait MapsRelations
{
    /**
     * @return mixed
     */
    public function objects()
    {
        return $this->hasMany(MapsObject::class, 'maps_id');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->type === 'private';
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->type === 'public';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'maps_id');
    }
}
