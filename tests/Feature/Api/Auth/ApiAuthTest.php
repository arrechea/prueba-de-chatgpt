<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetToken()
    {
        $companyId = env('COMPANY');

        $route = '/oauth/token';

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('post', $route, [
            'grant_type'    => 'password',
            'client_id'     => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'username'      => env('USER_EMAIL'),
            'password'      => env('PASSWORD'),
            'scope'         => '*',
        ]);

        $response->assertStatus(200);
    }
}
