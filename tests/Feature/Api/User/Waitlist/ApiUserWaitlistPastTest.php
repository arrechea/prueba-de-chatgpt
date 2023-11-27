<?php

namespace Tests\Feature\Api\User\Waitlist;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiUserWaitlistPastTest extends TestCase
{
    use WithoutMiddleware;

    public function testUserPastWaitlist()
    {
        $companyId = env('COMPANY');
        $brand = env('BRAND_SLUG');
        $route = route('api.me.brand.waitlist.past', [
            'brand'    => $brand,
            'per_page' => 5,
            'page'     => 1,
        ]);

        $user = User::where('email', env('USER_EMAIL'))->first();
        $user = Passport::actingAs($user);
        $userProfile = $user->getProfileByCompanyId($companyId);

        $response = $this
            ->withHeaders([
                'GAFAFIT-COMPANY' => $companyId,
                'Accept'          => 'application/json',
            ])->actingAs($user, 'api')
            ->json('get', $route);

        $response->assertStatus(200);
    }
}
