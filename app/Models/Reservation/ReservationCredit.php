<?php

namespace App\Models\Reservation;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationCredit extends GafaFitModel
{
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservations_id');
    }
}
