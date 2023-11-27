<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 26/06/2018
 * Time: 04:46 PM
 */

namespace App\Models\Reservation;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationMailCancel extends GafaFitModel
{
    use SoftDeletes;
    protected $table='mails_reservation_cancel';
}
