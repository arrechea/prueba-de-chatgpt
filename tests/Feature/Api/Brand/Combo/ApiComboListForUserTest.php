<?php

namespace Tests\Feature\Api\Brand\Combo;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiComboListForUserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCombosListForUser()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brand.combos.get.userPosibilities', [
            'brand'        => $brandId,
            'per_page'     => $limit,
            'page'         => 1,
            'only_actives' => 'true',
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
