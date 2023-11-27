<?php

namespace App\Http\Middleware\Gympass;

use App\Http\Controllers\Controller;
use App\Librerias\Gympass\Helpers\GympassHelpers;
use Illuminate\Http\Request;

class WebhooksMiddleware extends Controller
{
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->hasHeader('x-gympass-signature')) {
            \Log::error('Gympass: No Signature');
            abort(401, 'Unauthorized');
        } else {
            $secretKey = GympassHelpers::getWebhookSecret($request);
            if ($secretKey) {
                $requestBody = $request->getContent();

                $signature = strtoupper(hash_hmac('sha1', $requestBody, $secretKey));

                if ($signature !== $request->header('x-gympass-signature')) {
                    \Log::error("Gympass: Signature not correct received: {$request->header('x-gympass-signature')} correct: {$signature}");
                    abort(401, 'Unauthorized');
                }
            }
        }

        return $next($request);
    }
}
