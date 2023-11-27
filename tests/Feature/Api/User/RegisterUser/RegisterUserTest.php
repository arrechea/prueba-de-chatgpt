<?php

namespace Tests\Feature\Api\User\RegisterUser;

use App\Models\User\UserProfile;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    private const EMAIL = 'registerMail@test.test';
    private const PASSWORD = 'password';

    /**
     * A basic test example.
     *
     * @return array
     */
    public function testCreateUser()
    {
        $route = route('api.register');

        $response = $this
            ->json('post', $route, [
                'email'                 => self::EMAIL,
                'username'              => self::EMAIL,
                'password'              => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
                'first_name'            => 'Test',
                'last_name'             => 'Test',
                'name'                  => 'Test',
                'client_id'             => env('CLIENT_ID'),
                'client_secret'         => env('CLIENT_SECRET'),
                'grant_type'            => 'password',
                'scope'                 => '*',
                'birth_date'            => '1970-01-01',
                'gender'                => 'male',

            ], $this->headers());

        $response->assertStatus(200);

        $profile = UserProfile::where('email', self::EMAIL)->first();

        $this->assertTrue(!!$profile);

        return $profile->token;
    }

    /**
     * @depends testCreateUser
     */
    public function testVerifyUser($token)
    {
        $route = route('api.user.verify');

        $response = $this->json('get', $route, [
            'token' => $token,
        ], $this->headers());

        $profile = UserProfile::where('email', self::EMAIL)->first();

        $this->assertTrue(!!$profile && $profile->isVerified());

        User::where('email', self::EMAIL)->delete();
        UserProfile::where('email', self::EMAIL)->delete();
    }

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
