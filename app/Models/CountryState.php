<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryState extends Model
{

    public function cities()
    {
        return $this->hasMany(Cities::class, 'country_states_id', 'id');
    }
}
