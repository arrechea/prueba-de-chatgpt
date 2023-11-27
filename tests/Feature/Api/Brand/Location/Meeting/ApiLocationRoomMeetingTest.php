<?php

namespace Tests\Feature\Api\Brand\Location\Meeting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiLocationRoomMeetingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoomMeettingList()
    {
        $companyId = env('COMPANY');
        $brandId = env('BRAND_SLUG');
        $locationID = env('LOCATION');
        $roomId = env('ROOM');
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $route = route('api.brand.location.room.meetings', [
            'brand'         => $brandId,
            'locationToSee' => $locationID,
            'room'          => $roomId,
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
