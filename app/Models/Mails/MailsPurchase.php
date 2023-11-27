<?php

namespace App\Models\Mails;

use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailsPurchase extends GafaFitModel
{
    use SoftDeletes;
}
