<?php

namespace App\Librerias\Gympass\SDK;

use App\Models\Company\Company;
use App\Models\Location\Location;

class GympassBookingValidateAPI extends GympassSDK
{
    protected $url_key = 'gympass.booking_validate_api_url';
    protected $url_dev_key = 'gympass.booking_validate_dev_api_url';

    /**
     * @param Location $location
     * @param string   $booking_number
     * @param array    $data
     *
     * @return mixed|null
     */
    public function patchBooking(Location $location, string $booking_number, array $data = [])
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->postFormData($location, "bookings/$booking_number", $gym_id, $data, 'PATCH');

        return null;
    }
}
