<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 06/03/2019
 * Time: 13:13
 */

namespace App\Models\Subscriptions;


use App\Models\GafaFitModel;
use App\Models\Payment\PaymentType;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRecurrentPayment extends GafaFitModel
{
    use SoftDeletes;
    protected $table = 'users_recurrent_payment';
    protected $fillable = [
        'payment_types_id',
        'users_id',
        'users_profiles_id',
        'companies_id',
        'brands_id',
        'locations_id',
        'payment_data',
    ];

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_types_id');
    }
}
