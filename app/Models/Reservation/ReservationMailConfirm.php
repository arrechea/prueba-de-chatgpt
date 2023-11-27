<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/06/2018
 * Time: 11:44 AM
 */

namespace App\Models\Reservation;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationMailConfirm extends GafaFitModel
{
    use SoftDeletes;
    protected $table = 'mails_reservation_confirms';
}
