<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:23 PM
 */

namespace App\Models\Payment;


use App\Librerias\Payments\PaymentMethodInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use SoftDeletes, PaymentsRelations;

    protected $table = 'payment_types';
    protected $fillable = [
        'slug',
        'name',
        'model',
    ];


}
