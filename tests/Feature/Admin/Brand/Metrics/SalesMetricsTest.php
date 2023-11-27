<?php

namespace Tests\Feature\Admin\Brand\Metrics;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Tests\TestCase;

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
        $location_ids = explode(',', env('LOCATIONS_FILTER'));

        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.metrics.sales.ajax', [
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

    /**
     *
     */
    public function testSalesByItemMetricsTable()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location_ids = explode(',', env('LOCATIONS_FILTER'));
        $currency=env('CURRENCY');

        $start = env('START_MEETING');
        $end = env('END_MEETING');

        $route = route('admin.company.brand.metrics.sales.index', [
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
            [
                'name'  => 'currency',
                'value' => $currency,
            ],
        ];

        foreach ($location_ids as $id){
            array_push($filters,[
                'name'=>'locations[]',
                'value'=>$id
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
