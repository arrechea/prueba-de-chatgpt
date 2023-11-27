<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/10/2018
 * Time: 12:28
 */

namespace App\Models\Mails;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailsWaitlistConfirm extends GafaFitModel
{
    use SoftDeletes;

    protected $table = 'mails_waitlist_confirm';
}
