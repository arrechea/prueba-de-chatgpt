<?php

namespace Tests\Feature\Admin\Brand\Metrics;

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
        $location_ids = explode(',', env('LOCATIONS_FILTER'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.metrics.staff.index', [
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
}
