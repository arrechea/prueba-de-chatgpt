<?php

namespace App\Http\Controllers\Gympass;

use App\Events\Gympass\BookingCancelled;
use App\Events\Gympass\CheckinCreated;
use App\Events\Gympass\NewBookingRequested;
use App\Http\Controllers\Controller;
use Conekta\Log;
use Illuminate\Http\Request;


class GympassWebhookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requested(Request $request): \Illuminate\Http\JsonResponse
    {
        $event_type = $request->input('event_type');
        $event_data = $request->input('event_data', []);
        \Log::info('Gympass webhook request received');
        \Log::info($event_type);
        \Log::info(json_encode($event_data));
        switch ($event_type) {
            case 'booking-requested':
                event(new NewBookingRequested($event_data));
                break;
            case 'checkin':
                event(new CheckinCreated($event_data));
                break;
            case 'booking-canceled':
//                break;
            case 'booking-late-canceled':
                event(new BookingCancelled($event_data));
                break;
        }


        return response()->json('1');
    }
}
