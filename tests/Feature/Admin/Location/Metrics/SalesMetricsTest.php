<?php

namespace Tests\Feature\Admin\Location\Metrics;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalesMetricsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSalesMetricsChart()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));

        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.locations.metrics.sales.ajax', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
        ]);

        $response = $this->actingAs(Admin::where('email', env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'locations_id' => $location->id,
                'start'        => $start,
                'end'          => $end,
                'start_submit' => $start,
                'end_submit'   => $end,
                'grouped'      => 'day',
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     *
     */
    public function testSalesByItemMetricsTable()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));

        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.locations.metrics.sales.index', [
            'company'      => $company,
            'brand'        => $brand,
            'location'     => $location,
        ]);

        $response = $this->actingAs(Admin::where('email', env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'filters' => [
                    0 => [
                        'name'  => 'locations_id',
                        'value' => $location->id,
                    ],
                    1 => [
                        'name'  => 'start',
                        'value' => $start,
                    ],
                    2 => [
                        'name'  => 'end',
                        'value' => $end,
                    ],
                    3 => [
                        'name'  => 'start_submit',
                        'value' => $start,
                    ],
                    4 => [
                        'name'  => 'end_submit',
                        'value' => $end,
                    ],
                    5 => [
                        'name'  => 'grouped',
                        'value' => 'day',
                    ],
                ],
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }
}
