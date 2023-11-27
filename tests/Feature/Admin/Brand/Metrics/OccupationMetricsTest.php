<?php

namespace Tests\Feature\Admin\Brand\Metrics;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OccupationMetricsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOccupationPercentageMetricsChart()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location_ids = explode(',', env('LOCATIONS_FILTER'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.metrics.reservations.profitability.ajax', [
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

    public function testOccupationPercentageComparisonMetricsChart()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $location_ids = explode(',', env('LOCATIONS_FILTER'));

        $route = route('admin.company.brand.metrics.reservations.profitability.compare.ajax', [
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

    public function testOccupationTotalsMetricsChart()
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

    public function testOccupationTotalsComparisonMetricsChart()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $start = env('START_MEETING');
        $end = env('END_MEETING');
        $location_ids = explode(',', env('LOCATIONS_FILTER'));

        $route = route('admin.company.brand.metrics.reservations.compare.ajax', [
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
