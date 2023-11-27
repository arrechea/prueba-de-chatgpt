<?php

namespace Tests\Feature\Api\Brand\Staff;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiStaffTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaff()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $limit = env('PAGE_LIMIT');
        $staff = env('STAFF');
        $route = route('api.brand.staff.get', [
            'brand'        => $brandId,
            'staff'        => $staff,
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
