<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 13/08/2018
 * Time: 05:26 PM
 */

namespace App\Models\Payment;


use App\Librerias\Payments\PaymentMethodInterface;
use App\Models\Purchase\Purchase;
use Illuminate\Database\Eloquent\Model;

trait PaymentsRelations
{

    /**
     * @param Model  $parent
     * @param array  $attributes
     * @param string $table
     * @param bool   $exists
     * @param null   $using
     *
     * @return PaymentPivot|PaymentType
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        return $using ? $using::fromRawAttributes($parent, $attributes, $table, $exists)
            : PaymentPivot::fromAttributes($parent, $attributes, $table, $exists);
    }

    /**
     * @return mixed
     */
    public function getPaymentEspecificHandler(): PaymentMethodInterface
    {
        return new $this->model($this);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'payment_types_id', 'id');
    }
}
