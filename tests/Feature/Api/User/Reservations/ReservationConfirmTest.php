<?php

namespace Tests\Feature;

use App\User;
use Laravel\Passport\Passport;
use Tests\Feature\Api\User\UserApiTestCase;

class ReservationConfirmTest extends UserApiTestCase
{
//    use WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testReservationConfirm()
    {
        $companyId = env('COMPANY');
        $brand = env('BRAND_SLUG');
        $reservation = env('RESERVATION');
        $route = route('api.me.brand.reservations.get', [
            'brand'       => $brand,
            'reservation' => $reservation,
        ]);

        $user = User::where('email', env('USER_EMAIL'))->first();
        $user = Passport::actingAs($user);
        $userProfile = $user->getProfileByCompanyId($companyId);

        $response = $this
            ->withHeaders([
                'GAFAFIT-COMPANY' => $companyId,
                'Accept'          => 'application/json',
            ])
            ->actingAs($user, 'api')
            ->json('get', $route);

        $response->assertStatus(200);
    }
}
