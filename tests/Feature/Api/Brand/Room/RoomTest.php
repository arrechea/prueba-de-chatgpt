<?php

namespace Tests\Feature\Api\Brand\Room;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoomsInBrandList()
    {
        $companyId = env('COMPANY');
        $brand = env('BRAND_SLUG');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brand.room.brand.list', [
            'brand'    => $brand,
            'per_page' => $limit,
            'page'     => 1,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }

    public function testRoomsInLocationList()
    {
        $companyId = env('COMPANY');
        $brand = env('BRAND_SLUG');
        $location = env('LOCATION_SLUG');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brand.room.location.list', [
            'brand'    => $brand,
            'location' => $location,
            'per_page' => $limit,
            'page'     => 1,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
