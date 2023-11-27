<?php

namespace Tests\Feature\Api\Brand\Location\Meeting;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiLocationMeetingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLocationMeeting()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $locationID = env('LOCATION');
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $route = route('api.brand.location.meetings', [
            'brand'         => $brandId,
            'locationToSee' => $locationID,
            'start'         => $start,
            'end'           => $end

        ]);

        $response = $this->withHeaders([
            'GAFAFIT-COMPANY' => $companyId,
            'Accept'          => 'application/json',
        ])->json('get', $route);


        $response->assertStatus(200);
    }
}
