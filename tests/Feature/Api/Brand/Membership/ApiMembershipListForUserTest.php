<?php

namespace Tests\Feature\Api\Brand\Membership;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiMembershipListForUserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMembershipListForUser()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brand.membership.get.userPosibilities', [
            'brand'        => $brandId,
            'per_page'     => $limit,
            'page'         => 1,
            'only_actives' => 'true',
            'propagate'    => 'true',
        ]);

        $user = User::where('email', env('USER_EMAIL'))->first();
        $user = Passport::actingAs($user);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])
            ->actingAs($user, 'api')
            ->json('get', $route);


        $response->assertStatus(200);
    }
}
