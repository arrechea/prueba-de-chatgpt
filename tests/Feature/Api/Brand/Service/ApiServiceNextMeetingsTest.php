<?php

namespace Tests\Feature\Api\Brand\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiServiceNextMeetingsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testServiceNextMeetings()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $service = env('SERVICE');
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $route = route('api.brand.service.meetings', [
            'brand'        => $brandId,
            'serviceToSee' => $service,
            'start'        => $start,
            'end'          => $end,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
