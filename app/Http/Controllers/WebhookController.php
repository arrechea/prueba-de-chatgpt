<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facades\GafaPayWebhook;
use App\Facades\ClientSitesWebhook;

class WebhookController extends Controller
{
    /**
     * @param Request $request
     * @param null    $extern
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request, $extern = null)
    {
        switch ($extern) {
            // Servicios de pago y renovaciones GafaPay
            case 'gafa-pay':
                $resp = GafaPayWebhook::process($request);
                $message = $resp['message'];
                $code = $resp['code'];

                //return response()->json(['message'=>$message], $code);
                return response()->json($resp, $code);
                break;
            // Servicios de Sincronización de usuarios con sitios externos del cliente
            case 'clientsites':
                $resp = ClientSitesWebhook::process($request);
                $message = $resp['message'];
                $code = $resp['code'];

                //return response()->json(['message'=>$message], $code);
                return response()->json($resp, $code);
                break;

            default:
                if (empty($extern)) {
                    return response()->json(['message' => "Service not specified.", 'code' => 403], 403);
                } else {
                    return response()->json(['message' => "Service '{$extern}' not found.", 'code' => 403], 403);
                }
                break;
        }
    }
}
