<?php

namespace Tests\Feature\Api\Brand\Location;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiLocationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLocation()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $locationID = env('LOCATION');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brand.location.see', [
            'brand'         => $brandId,
            'locationToSee' => $locationID,
            'per_page'      => $limit,
            'page'          => 1,
            'only_actives'  => 'true',
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
