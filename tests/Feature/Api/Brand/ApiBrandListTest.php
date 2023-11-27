<?php

namespace Tests\Feature\Api\Brand;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiBrandListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBrandList()
    {
        $companyId = env('COMPANY');
        $brandSlug = env('BRAND_SLUG');
        $route = route('api.brand.brands', [
            'brand' => $brandSlug,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }

}
