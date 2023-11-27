<?php

namespace Tests\Feature\Api\Brand\Staff;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiStaffSpecialTextTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaffSpecialText()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $staff = env('STAFF');

        $route = route('api.brand.staff.get.special-texts', [
            'brand'        => $brandId,
            'staff'        => $staff,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
