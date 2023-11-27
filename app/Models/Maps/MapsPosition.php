<?php

namespace App\Models\Maps;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapsPosition extends GafaFitModel
{
    use SoftDeletes;

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
}
