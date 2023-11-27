<?php

namespace App\Librerias\Webhooks;

use Illuminate\Http\Request;

//use App\Models\Purchase;
use App\Models\User\UsersMemberships;
use App\Models\Membership\Membership;
use App\Models\Brand\Brand;
use App\Models\Purchase\Purchase;
use Illuminate\Support\Facades\Log;

/**
 *  Procesamiento de Webhooks para proveedor de pagos GafaPay
 *
 */
class GafaPay
{
    var $error = false;
    var $error_msj = '';
    var $usermembership = null;
    var $membership = null;
    var $purchase = null;
    var $brand = null;
    var $gafapay_order = null;  // array with order data from GafaPay

    /**
     * Process Webhooks requests
     *
     * @return array|void
     */
    public function process(Request $request)
    {

        $this->authrequest($request);
        if ($this->error) {
            Log::error("GafaPay process: Error in authrequest. message: {$this->error_msj}");

            return ['status' => 'error', 'message' => $this->error_msj, 'code' => 403];
        }

        $type = ($request->has('type') ? $request['type'] : '');

        switch ($type) {
            case 'permission':
                return $this->ping($request);
                break;
            case 'paymentresult':
                return $this->paymentResult($request);
                break;
            default:
                return ['message' => 'Servicio no definido', 'code' => 403];
                break;
        }
    }


    /**
     * Validate webhook request credentials.
     *
     * @return void
     */
    private function authrequest(Request $request)
    {
        $this->error = false;
        $this->error_msj = '';

        $type = ($request->has('type') ? $request['type'] : '');
        $id = ($request->has('id') ? $request['id'] : '');
        $secret = ($request->has('secret') ? $request['secret'] : '');
        $data = ($request->has('data') ? json_decode($request['data']) : null);

        // validate type exists
        if (!$type) {
            $this->error = true;
            $this->error_msj = 'Tipo de servicio no especificado';

            return false;
        }

        // validate client id exists
        if (!$id) {
            $this->error = true;
            $this->error_msj = 'No se encuentra id de cliente';

            return false;
        }
        // validate client secret exists
        if (!$secret) {
            $this->error = true;
            $this->error_msj = 'No se encuentra clave de cliente';

            return false;
        }

        // validate data exists and purchase_id exists
        if ((!$data) || (empty($data->purchase_id))) {
            $this->error = true;
            $this->error_msj = 'No se recibieron datos de compra (purchase_id). ' . (empty($data) ? '' : json_encode($data));

            return false;
        }

        // validate membership
        $purchase_id = $data->purchase_id;

        // validate membership
        $usermembership = UsersMemberships::where('purchases_id', $purchase_id)
            ->whereNull('deleted_at')
            ->first();
        if ($usermembership) {
            $this->usermembership = $usermembership;
            $this->membership = Membership::find($usermembership->memberships_id);
            $this->purchase = Purchase::find($purchase_id);
        }

        // find purchase
        if ($this->purchase) {
            $this->brand = $this->purchase->brand;
        }


        // Buscar credenciales del Brand asociado al id y secret.
        if (empty($this->brand)) {
            $this->error = true;
            $this->error_msj = "No se pueden validar las credenciales del cliente. Brand asociado no localizado en el purchase id {$purchase_id}";

            return false;
        }

        // validate client id
        $valid_id = ($id == $this->brand->gafapay_client_id);

        if (!$valid_id) {
            $this->error = true;
            $this->error_msj = 'Id de cliente incorrecto';

            return false;
        }

        // validate client secret
        $valid_secret = ($secret == $this->brand->gafapay_client_secret);
        if (!$valid_secret) {
            $this->error = true;
            $this->error_msj = 'Clave de cliente incorrecta';

            return false;
        }

        return true;
    }


    /**
     * Recibir solicitud de autorización para aplicar cargo de pago recurrente
     *
     * @return array
     */
    private function ping(Request $request)
    {

        // validate membership
        if (!$this->usermembership || !is_null($this->usermembership->deleted_at)) {
            Log::error("GafaPay ping: Membresia de usuario no encontrada");

            return ['status' => 'error', 'message' => 'No se encuentra membresía de usuario', 'code' => 403];
        }

        // validate membership active
        if (empty($this->membership) || ($this->membership->status !== 'active')) {
            Log::error("GafaPay ping: Tipo de Membresia inactiva");

            return ['status' => 'error', 'message' => 'Tipo de Membresia inactiva', 'code' => 403];
        }

        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }


    /**
     * Resultado de pagos recurrentes
     *
     * información de orden, sistema de pago, variable extra y resultado del pago
     *
     * @return void
     */
    private function paymentResult(Request $request)
    {

        $data = ($request->has('data') ? json_decode($request['data']) : null);

        $order = (empty($data->order) ? null : $data->order);
        // validate order sent
        if (!$order) {
            Log::error("GafaPay paymentResult: No se recibieron datos de orden");

            return ['status' => 'error', 'message' => 'No se recibieron datos de orden', 'code' => 403];
        }

        $pay_status = (empty($data->pay_status) ? null : $data->pay_status);
        // validate order sent
        if (!$pay_status) {
            Log::error("GafaPay paymentResult: No se recibio el estado del pago (pay_status)");

            return ['status' => 'error', 'message' => 'No se recibio el estado del pago (pay_status)', 'code' => 403];
        }

        // Analizar el resultado del pago
        if ($pay_status !== 'paid') {
            Log::error("GafaPay paymentResult: Status del pago no completado ({$pay_status})");

            return ['status' => 'error', 'message' => 'Membresia no actualizada por pago no aplicado', 'code' => 403];
        }

        // Si pago exitoso, renovar suscripción
        $status_renovacion = $this->renew_membership();
        if (!$status_renovacion) {
            Log::error("GafaPay paymentResult: Error al renovar subscripción por pago aplicado " . ($this->error ? $this->error_msj : ''));

            return ['status' => 'error', 'message' => 'Error en renovación de Membresia', 'code' => 403];
        }

        // liberar webhook
        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];

    }

    private function renew_membership()
    {

        $this->error = false;
        $this->error_msj = '';

        // verify original purchase
        if (empty($this->purchase)) {
            $this->error = true;
            $this->error_msj = 'No existe un purchase para renovar.';

            return false;
        }

        $purchase = new Purchase();
        $purchase->payment_types_id = $this->purchase->payment_types_id;
        $purchase->currencies_id = $this->purchase->currencies_id;

        $purchase->status = $purchase::$statusPending;
        $purchase->is_gift_card = false;
        $purchase->has_discount_code = false;
        $purchase->has_discount_code = false;
        $purchase->subscription = $this->purchase->subscription??null;

        $purchase->locations_id = $this->purchase->locations_id;
        $purchase->brands_id = $this->purchase->brands_id;
        $purchase->companies_id = $this->purchase->companies_id;

        $purchase->user_profiles_id = $this->purchase->user_profiles_id;
        $purchase->users_id = $this->purchase->users_id;

        $purchase->admin_profiles_id = $this->purchase->admin_profiles_id; //null;
        $purchase->subtotal = 0;
        $purchase->total = 0;
        $purchase->save();


        // obtener los productos del purchase original
        if (!empty($this->purchase->items) && ($this->purchase->items->count() > 0)) {
            foreach ($this->purchase->items as $original_item) {
                $item = $purchase->addItem($original_item->buyed);
                //Actualizado de precios
                $purchase->subtotal += $item->item_price_final;
                $purchase->total += $item->item_price_final;
            }
            $purchase->save();
        }
        $purchase->assignToUser(true);

        Log::info("GafaPay renew_membership: Membresia {$this->usermembership->id} renovada. Nuevo Purchase {$purchase->id}");

        return true;

    }
}
