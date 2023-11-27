<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 29/08/2018
 * Time: 05:25 PM
 */

namespace App\Models\Log;


use Illuminate\Database\Eloquent\Model;

class CompaniesRequests extends Model
{
    protected $table = 'companies_requests';

    protected $fillable = [
        'companies_id',
        'month',
        'year',
        'month_requests',
    ];
}
