<?php

namespace Tests\Feature\Api\Brand;

use App\Models\Brand\Brand;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ApiBrandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBrandList()
    {
        $companyId = env('COMPANY');
        $limit = env('PAGE_LIMIT');
        $route = route('api.brands', [
            'per_page'     => $limit,
            'page'         => 1,
            'only_actives' => 'true',
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);

        $brandsCount = Brand::where('companies_id', $companyId)->where('status', 'active')->limit($limit)->count();

        $jsonResponse = new Collection($response->json('data'));
        $total = $jsonResponse->count();

        $this->assertTrue($total === $brandsCount);
        $response->assertStatus(200);
    }
}
