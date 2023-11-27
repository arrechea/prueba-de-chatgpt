<?php

namespace Tests\Feature\Api\Brand\Combo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiComboListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testComboList()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brand.combos.get', [
            'brand'        => $brandId,
            'per_page'     => $limit,
            'page'         => 1,
            'only_actives' => 'true',
            'propagate'    => 'true',
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
