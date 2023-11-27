<?php

namespace Tests\Feature\Api\User;

use App\Models\User\UserProfile;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiUserTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetMe()
    {
        $route = route('api.me.get');


        $user = User::where('email', env('USER_EMAIL'))->first();
        $user = Passport::actingAs($user);

        $response = $this
            ->actingAs($user, 'api')
            ->json('get', $route, [], $this->headers($user));

        $response->assertStatus(200);
    }

    /**
     *
     */
    public function testPutMe()
    {
        $companyId = env('COMPANY');
        $route = route('api.me.update');

        $user = User::where('email', env('USER_EMAIL'))->first();
        $user = Passport::actingAs($user);
        $userProfile = $user->getProfileByCompanyId($companyId);

        $response = $this
            ->actingAs($user, 'api')
            ->json('post', $route, [
                'email'      => $userProfile->email,
                'first_name' => $userProfile->first_name,
                'last_name'  => $userProfile->last_name,
            ], $this->headers());
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $userProfile->id,
        ]);
    }

    /**
     *
     * @return array
     */
    protected function headers()
    {
        $companyId = env('COMPANY');

        $headers = [
            'Accept'          => 'application/json',
            'GAFAFIT-COMPANY' => $companyId,
        ];

        return $headers;
    }
}
