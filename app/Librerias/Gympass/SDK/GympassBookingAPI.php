<?php

namespace App\Librerias\Gympass\SDK;

use App\Models\Location\Location;
use Carbon\Carbon;

class GympassBookingAPI extends GympassSDK
{
    protected $url_key = 'gympass.booking_api_url';
    protected $url_dev_key = 'gympass.booking_dev_api_url';


//    ********************Start Classes Endpoints**********************//

    /**
     * @param Location $location
     * @param array    $data
     *
     * @return mixed|null
     */
    public function createClass(Location $location, array $data = [])
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');

        if ($gym_id)
            return $this->postFormData($location, 'classes', $gym_id, $data);

        return null;
    }

    /**
     * @param Location $location
     *
     * @return mixed|null
     */
    public function getClasses(Location $location)
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');

        if ($gym_id)
            return $this->requestWithGym($location, 'classes', $gym_id);

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param bool     $showDeleted
     *
     * @return mixed|null
     */
    public function getClass(Location $location, int $class_id, bool $showDeleted = false)
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->requestWithGym($location, "classes/$class_id", $gym_id, 'GET', ['query' => ['show-deleted' => $showDeleted]]);

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param array    $data
     *
     * @return mixed|null
     */
    public function updateClass(Location $location, int $class_id, array $data = [])
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->postFormData($location, "classes/$class_id", $gym_id, $data, 'PUT');

        return null;
    }

    //    ********************End Classes Endpoints**********************//

    //    ********************Start Slots Endpoints**********************//

    /**
     * @param Location $location
     * @param int      $class_id
     * @param array    $data
     *
     * @return mixed|null
     */
    public function createSlot(Location $location, int $class_id, array $data = [])
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->postFormData($location, "classes/$class_id/slots", $gym_id, $data);

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param Carbon   $from
     * @param Carbon   $to
     *
     * @return mixed|null
     */
    public function getSlots(Location $location, int $class_id, Carbon $from, Carbon $to)
    {
        $options = [
            'query' => [
                'from' => $from->toIso8601String(),
                'to'   => $to->toIso8601String(),
            ],
        ];

        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->requestWithGym($location, "classes/$class_id/slots", $gym_id, 'GET', $options);

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param int      $slot_id
     *
     * @return mixed|null
     */
    public function getSlot(Location $location, int $class_id, int $slot_id)
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->requestWithGym($location, "classes/$class_id/slots/$slot_id", $gym_id);

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param int      $slot_id
     * @param array    $data
     *
     * @return mixed|null
     */
    public function updateSlot(Location $location, int $class_id, int $slot_id, array $data = [])
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->postFormData($location, "classes/$class_id/slots/$slot_id", $gym_id, $data, 'PUT');

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param int      $slot_id
     * @param array    $data
     *
     * @return mixed|null
     */
    public function patchSlot(Location $location, int $class_id, int $slot_id, array $data = [])
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->postFormData($location, "classes/$class_id/slots/$slot_id", $gym_id, $data, 'PATCH');

        return null;
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param int      $slot_id
     *
     * @return mixed|null
     */
    public function deleteSlot(Location $location, int $class_id, int $slot_id)
    {
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->requestWithGym($location, "classes/$class_id/slots/$slot_id", $gym_id, 'DELETE');

        return null;
    }

    //    ********************End Slots Endpoints**********************//


    //    ********************Start Bookings Endpoints**********************//


    //    ********************End Bookings Endpoints**********************//


    //    ********************Start Categories Endpoints**********************//

    /**
     * @param Location $location
     * @param string   $locale
     *
     * @return mixed|null
     */
    public function getCategories(Location $location, string $locale = 'es_MX')
    {
        $options = [
            'query' => [
                'locale' => $locale,
            ],
        ];

        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id)
            return $this->requestWithGym($location, 'categories', $gym_id, 'GET', $options);

        return null;
    }

    //    ********************End Categories Endpoints**********************//


    //    ********************Start Health Check Endpoints**********************//

    /**
     * @param Location $location
     *
     * @return mixed|null
     */
    public function getHealthCheck(Location $location)
    {
        return $this->requestWithAuth($location, 'health');
    }

    //    ********************End Health Check Endpoints**********************//
}
