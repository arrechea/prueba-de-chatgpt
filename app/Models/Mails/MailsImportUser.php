<?php

namespace App\Models\Mails;

use App\Models\GafaFitModel;

class MailsImportUser extends GafaFitModel
{
    protected $table = 'mails_users_import';
    protected $casts = [
        'content' => 'array',
    ];
}
