<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 19/06/18
 * Time: 11:47
 */

namespace App\Models\Payment;


use Illuminate\Database\Eloquent\Relations\Pivot;

class PaymentPivot extends Pivot
{
    protected $casts = [
        'config' => 'object',
    ];
}
