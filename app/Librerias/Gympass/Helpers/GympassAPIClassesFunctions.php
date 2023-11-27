<?php

namespace App\Librerias\Gympass\Helpers;

use App\Librerias\Gympass\SDK\GympassBookingAPI;
use App\Models\Location\Location;
use App\Models\Service;

abstract class GympassAPIClassesFunctions
{
    /**
     * @param array $errors
     * @param       $validator
     *
     * @return void
     */
    private static function addErrors(array $errors, &$validator)
    {
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $validator->after(function ($val) use ($error) {
                    $val->errors()->add('gympass', $error);
                });
            }
        }
    }

    /**
     * @param Service $service
     * @param bool    $update
     *
     * @return array[]
     */
    private static function formDataService(Service $service, Location $location, bool $update = false): array
    {
        $data = [
            'name'        => $service->name,
            'description' => $service->description ?? $service->name,
            'notes'       => $service->getDotValue('extra_fields.notes'),
            'bookable'    => $service->isGympassBookable($location),
            'visible'     => $service->isGympassVisible($location),
            'product_id'  => $service->getDotValue("extra_fields.gympass.info.{$location->id}.product_id"),
            'system_id'   => $service->id,
            'categories'  => $service->getDotValue("extra_fields.gympass.info.{$location->id}.categories"),
        ];

        return $update ? $data : ['classes' => [$data]];
    }

    /**
     * @param Service $service
     * @param         $validator
     *
     * @return void|null
     */
    public static function saveClassFromService(Service $service, &$validator)
    {
        $brand = $service->brand;
        $locations = $brand->gympassActiveLocations;
        foreach ($locations as $location) {
            if (!$location || !$location->isActive() || !$location->isGympassActive() || !$location->getDotValue('extra_fields.gympass.gym_id')) {
                continue;
            }
            if (!$service->isGympassActive($location) || !$service->getDotValue("extra_fields.gympass.info.{$location->id}.product_id")) {
                continue;
            }

            $class_id = $service->getGympassClassId($location);

            $class = self::getGympassClass($service, $location);

            if (!$class && !!$class_id) {
                self::addErrors(['Clase no existe en gympass'], $validator);

                return null;
            }

            $update = !!$class;

            self::createClassFromService($service, $location, $validator, $update);
        }
    }

    /**
     * @param Service  $service
     * @param Location $location
     *
     * @return mixed|null
     */
    public static function getGympassClass(Service $service, Location $location)
    {
        if ($service->getGympassClassId($location)) {

            $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
            if ($gym_id) {
                $sdk = new GympassBookingAPI();

                return $sdk->getClass($location, $service->getGympassClassId($location));
            }
        }

        return null;
    }

    /**
     * @param Service  $service
     * @param Location $location
     * @param          $validator
     * @param bool     $update
     *
     * @return int|null
     */
    private static function createClassFromService(Service $service, Location $location, &$validator, bool $update = false)
    {
        $data = self::formDataService($service, $location, $update);


        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        if ($gym_id) {
            $sdk = new GympassBookingAPI();
            $response = $update ?
                $sdk->updateClass($location, $service->getGympassClassId($location), $data) :
                $sdk->createClass($location, $data);

            $errors = $sdk->getErrors();
            self::addErrors($errors, $validator);

            if (count($errors) > 0) {
                return null;
            }

            if (!!(array)$response) {
                $classes = $response->classes;
                if ($classes && is_array($classes) && isset($classes[0])) {
                    $class = $classes[0];
                    $service->setGympassClassId($location, $class->id, true);
                }
            }

            return $service->getGympassClassId($location);

        }

        return null;
    }
}
