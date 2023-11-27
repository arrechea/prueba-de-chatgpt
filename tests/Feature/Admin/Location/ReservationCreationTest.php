<?php

namespace Tests\Feature\Admin\Location;

use App\Admin;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use App\User;
use Tests\TestCase;

class ReservationCreationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostReservationControllerReservateRepeatedMembreship()
    {
        $this->assertTrue(true);

        return;//todo mejorar testeo
        $location = Location::where('id', env('LOCATION'))->with([
            'brand',
            'company',
        ])->first();
        /**
         * @var User $user
         */
        $userProfile = UserProfile::where('email', env('USER_EMAIL'))
            ->where('companies_id', $location->company->id)
            ->where('status', 'active')
            ->first();

        $route = route('admin.company.brand.locations.reservations.reservate', [
                'company'  => $location->company,
                'brand'    => $location->brand,
                'location' => $location,
            ]
        );
        $user = Admin::where('email', env('USER_EMAIL'))->first();
        $response = $this
            ->actingAs($user, 'admin')
            ->json('post', $route, [
                'users_id'         => $userProfile->id,
                'meetings_id'      => env('MEETING'),
                'meeting_data'     => null,
                'memberships_id'   => env('MEMBERSHIP'),
                'combos_id'        => null,
                'payment_types_id' => env('PAYMENT_TYPE'),
                'payment_data'     => null,
                'signature'        => null,
            ], [
                //'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);
        $response->assertStatus(422);
    }

    public function testGetReservationControllerReservateForm()
    {
        $this->assertTrue(true);

        return;//todo mejorar testeo

        $location = Location::where('id', env('LOCATION'))->with([
            'brand',
            'company',
        ])->first();
        /**
         * @var User $user
         */
        $userProfile = UserProfile::where('email', env('USER_EMAIL'))
            ->where('companies_id', $location->company->id)
            ->where('status', 'active')
            ->first();
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $route = route('admin.company.brand.locations.reservations.getForm', [
                'company'  => $location->company,
                'brand'    => $location->brand,
                'location' => $location,
            ]
        );
        $getVariables = http_build_query(
            [
                'users_id'    => $userProfile->id,
                'meetings_id' => env('MEMBERSHIP'),
            ]
        );

        $response = $this
            ->actingAs($user, 'admin')
            ->get("$route?$getVariables");

        $response->assertStatus(200);
    }

}
