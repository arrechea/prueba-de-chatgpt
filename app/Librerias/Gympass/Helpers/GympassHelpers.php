<?php

namespace App\Librerias\Gympass\Helpers;

use App\Librerias\Gympass\SDK\GympassSetupAPI;
use App\Librerias\Models\Users\LibUsers;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Illuminate\Http\Request;

abstract class GympassHelpers
{
    const CHECKIN_TYPE_FRONTDESK = 'frontdesk';
    const CHECKIN_TYPE_AUTOMATIC = 'automatic';
    const CHECKIN_TYPE_TURNSTILE = 'turnstile';

    /**
     * @param $implode
     *
     * @return string|string[]
     */
    public static function getCheckinTypes($implode = false)
    {
        $return = [
            self::CHECKIN_TYPE_FRONTDESK,
            self::CHECKIN_TYPE_AUTOMATIC,
//            self::CHECKIN_TYPE_TURNSTILE,
        ];

        return $implode ? implode(',', $return) : $return;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isValidCheckinType(string $type)
    {
        return in_array($type, self::getCheckinTypes());
    }

    /**
     * @param array $event_data
     *
     * @return UserProfile|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function getOrCreateUser(array $event_data, Company $company)
    {
        $user = null;
        $user_data = $event_data['user'] ?? null;
        if ($company) {
            if ($user_data) {
                $user = UserProfile::whereJsonContains('extra_fields',
                    ['gympass' => ['gympass_id' => $user_data['unique_token']]])
                    ->first();
                if (!$user) {
                    if (isset($user_data['email'])) {

                        $req = new Request();
                        $pass = str_random();
                        $req->merge([
                            'first_name' => $user_data['first_name'] ?? '',
                            'last_name'  => $user_data['last_name'] ?? '',
                        ]);
                        $user = LibUsers::createProfileByEmailAndCompany($req, $user_data['email'], $company, $pass);
                        if ($user) {
                            $user->setDotValue('extra_fields.gympass.gympass_id', $user_data['unique_token'], true);
                        } else {
                            \Log::error('Error en la creación de usuario');
                        }
                    } else {
                        \Log::error('Request malformed');
                    }
                }
            } else {
                \Log::error('Request malformed');
            }
        } else {
            \Log::error('Compañía no encontrada en el sistema');
        }

        return $user;
    }

    /**
     * @param Location $location
     * @param bool     $is_html
     *
     * @return null
     */
    public static function getGympassProducts(Location $location, bool $is_html = false, $selected_product = null)
    {
        $sdk = new GympassSetupAPI();
        $gym_id = $location->getDotValue('extra_fields.gympass.gym_id');
        $response = $sdk->getProducts($location, $gym_id);
        if ($response) {
            $products = $response->products;
            if (!$is_html) {
                return $products;
            } else {
                foreach ($products as $product) {
                    ?>
                    <option
                        value="<?php echo $product->product_id ?>"
                        <?php if ($selected_product && $selected_product == $product->product_id) echo 'selected' ?> >
                        <?php echo $product->name ?>
                    </option>
                    <?php
                }
            }
        }

        return null;
    }

    /**
     * @param Company|null $company
     *
     * @return bool
     */
    public static function isGympassActive(Company $company = null): bool
    {
        if ($company) {
            return $company->isGympassActive();
        }

        return config('gympass.is_active', false) == 1;
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    public static function getWebhookSecret(Request $request): ?string
    {
        $return = config('gympass.webhook_secret');

        return $return ?? null;
    }

    /**
     * @param int $gym_id
     *
     * @return mixed
     */
    public static function getLocationFromGymId(int $gym_id)
    {
        $location = Location::whereJsonContains('extra_fields', ['gympass' => ['gym_id' => $gym_id]])->first();
        if (!$location) {
            $location = Location::whereJsonContains('extra_fields', ['gympass' => ['gym_id' => (string)$gym_id]])->first();
        }

        return $location;
    }
}
