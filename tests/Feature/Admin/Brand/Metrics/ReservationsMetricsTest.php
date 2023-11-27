<?php

namespace Tests\Feature\Admin\Brand\Metrics;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationsMetricsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testReservationsByRoomTable()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $location_ids = explode(',', env('LOCATIONS_FILTER'));

        $route = route('admin.company.brand.metrics.reservations.rooms', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $filters = [
            [
                'name'  => 'brands_id',
                'value' => $brand->id,
            ],
            [
                'name'  => 'start',
                'value' => $start,
            ],
            [
                'name'  => 'end',
                'value' => $end,
            ],
            [
                'name'  => 'start_submit',
                'value' => $start,
            ],
            [
                'name'  => 'end_submit',
                'value' => $end,
            ],
            [
                'name'  => 'grouped',
                'value' => 'day',
            ],
        ];

        foreach ($location_ids as $id) {
            array_push($filters, [
                'name'  => 'locations[]',
                'value' => $id,
            ]);
        }

        $response = $this->actingAs(Admin::where('email', env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'filters' => $filters,
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    public function testReservationsTotalsTable()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $location_ids = explode(',', env('LOCATIONS_FILTER'));

        $route = route('admin.company.brand.metrics.reservations.totals', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $filters = [
            [
                'name'  => 'brands_id',
                'value' => $brand->id,
            ],
            [
                'name'  => 'start',
                'value' => $start,
            ],
            [
                'name'  => 'end',
                'value' => $end,
            ],
            [
                'name'  => 'start_submit',
                'value' => $start,
            ],
            [
                'name'  => 'end_submit',
                'value' => $end,
            ],
            [
                'name'  => 'grouped',
                'value' => 'day',
            ],
        ];

        foreach ($location_ids as $id) {
            array_push($filters, [
                'name'  => 'locations[]',
                'value' => $id,
            ]);
        }

        $response = $this->actingAs(Admin::where('email', env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'filters' => $filters,
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    public function testReservationsMetricsChart()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $location_ids = explode(',', env('LOCATIONS_FILTER'));

        $route = route('admin.company.brand.metrics.reservations.ajax', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $response = $this->actingAs(Admin::where('email', env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'brands_id'    => $brand->id,
                'start'        => $start,
                'end'          => $end,
                'start_submit' => $start,
                'end_submit'   => $end,
                'grouped'      => 'day',
                'locations'    => $location_ids,
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }
}
