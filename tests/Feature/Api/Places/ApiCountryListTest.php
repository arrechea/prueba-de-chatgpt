<?php

namespace Tests\Feature\Api\Places;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCountryListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCountryList()
    {
        $companyId = env('COMPANY');
        $limit = env('PAGE_LIMIT');
        $route = route('api.countries', [
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
