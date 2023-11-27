<?php


namespace App\Models\Mails;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailsInvitationConfirm extends GafaFitModel
{
    use SoftDeletes;
    protected $table = 'mails_invitation_confirm';
}
