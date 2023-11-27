<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 30/08/2018
 * Time: 04:34 PM
 */

namespace App\Models\Log;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;

class ResendVerificationRequest extends Model
{
    protected $table = 'resend_verification_requests';
    protected $fillable = [
        'date',
        'email',
        'companies_id',
    ];
}
