<?php

namespace Tests\Feature\Api\Brand\Service;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testService()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $service = env('SERVICE');
        $route = route('api.brand.service.see', [
            'brand'        => $brandId,
            'serviceToSee' => $service,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
