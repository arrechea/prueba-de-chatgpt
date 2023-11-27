<?php

namespace App\Librerias\Gympass\SDK;

use App\Models\Company\Company;
use App\Models\Location\Location;

class GympassAccessControlAPI extends GympassSDK
{
    protected $url_key = 'gympass.access_control_api_url';
    protected $url_dev_key = 'gympass.access_control_dev_api_url';

    /**
     * @param Location $location
     * @param int      $gym_id
     * @param string   $gympass_id
     *
     * @return mixed|null
     */
    public function validate(Location $location, int $gym_id, string $gympass_id)
    {
        $body = [
            'gympass_id' => $gympass_id,
        ];

        $options = $this->parseOptions($location, $gym_id);


        return $this->requestWithAuth($location, 'validate', 'POST', $options, $body);
    }

    /**
     * @param Location $location
     * @param int      $gym_id
     *
     * @return array
     */
    private function parseOptions(Location $location, int $gym_id)
    {
        $options = [];
        if ($location->isGympassProduction()) {
            $options['headers'] = [
                'X-Gym-Id' => $gym_id,
            ];
        } else {
            $testkey = config('gympass.access_control_dev_api_testkey', 'testkey');
            $test_gym_id = config('gympass.access_control_dev_api_test_gym_id', 1234);
            $options['headers'] = [
                'Authorization' => "Bearer $testkey",
                'X-Gym-Id'      => $test_gym_id,
            ];
        }

        return $options;
    }
}
