<?php

namespace App\Models;


class Cities extends GafaFitModel
{

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id', 'id');
    }
}
