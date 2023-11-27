<?php

namespace Tests\Feature\Api\Places;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCountryCityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCountryCityList()
    {
        $companyId = env('COMPANY');
        $limit = env('PAGE_LIMIT');
        $country = env('COUNTRY');
        $route = route('api.countries.cities', [
            'country'      => $country,
            'per_page'     => $limit,
            'page'         => 1,
            'only_actives' => 'true',
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);

        $response->assertStatus(200);
    }
}
