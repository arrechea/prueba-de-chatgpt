<?php

namespace App\Librerias\Gympass\SDK;

use App\Models\Company\Company;
use App\Models\Location\Location;

class GympassSetupAPI extends GympassSDK
{
    protected $url_key = 'gympass.setup_api_url';

    protected $url_dev_key = 'gympass.setup_dev_api_url';

    //    ********************Start Products Endpoints**********************//

    /**
     * @param Location $location
     * @param int      $gym_id
     *
     * @return mixed|null
     */
    public function getProducts(Location $location, int $gym_id)
    {
        return $this->requestWithGym($location, 'products', $gym_id);
    }

    //    ********************End Products Endpoints**********************//
}
