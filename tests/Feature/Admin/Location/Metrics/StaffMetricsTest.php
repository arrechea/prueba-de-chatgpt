<?php

namespace Tests\Feature\Admin\Location\Metrics;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffMetricsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaffMetrics()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));

        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.locations.metrics.staff.index', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
        ]);

        $response = $this->actingAs(Admin::where('email', env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'locations_id',
                        'value' => $location->id,
                    ],
                    [
                        'name'  => 'start',
                        'value' => $start,
                    ],
                    [
                        'name'  => 'end',
                        'value' => $end,
                    ],
                ],
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }
}
