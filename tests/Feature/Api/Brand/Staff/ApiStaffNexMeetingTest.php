<?php

namespace Tests\Feature\Api\Brand\Staff;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiStaffNexMeetingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaffNextMeetings()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $staff = env('STAFF');
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $route = route('api.brand.staff.get.meetings', [
            'brand'        => $brandId,
            'staff'        => $staff,
            'start'        => $start,
            'end'          => $end,
        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
