<?php

namespace App\Models\CompaniesWebhooks;

use Illuminate\Database\Eloquent\Model;

class CompaniesWebhooks extends Model
{
    //
    protected $table = 'companies_webhooks';

    protected $fillable = ['webhook'];

}
