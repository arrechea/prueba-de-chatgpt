<?php

namespace App\Models\Mails;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailsReservationCancel extends GafaFitModel
{
    use SoftDeletes;

    protected $table = 'mails_reservation_cancel';
}
