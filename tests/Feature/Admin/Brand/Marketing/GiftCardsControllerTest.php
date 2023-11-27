<?php

namespace Tests\Feature;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GiftCardsControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostIndex()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $route = route('admin.company.brand.marketing.gift-card.index', [
            'company' => $company,
            'brand' => $brand,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'companies_id',
                        'value' => $company->id,
                    ],
                    [
                        'name'  => 'brands_id',
                        'value' => $brand->id,
                    ],
                ],
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    public function testPostIndexWithSearch()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $route = route('admin.company.brand.marketing.gift-card.index', [
            'company' => $company,
            'brand' => $brand,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'companies_id',
                        'value' => $company->id,
                    ],
                    [
                        'name'  => 'brands_id',
                        'value' => $brand->id,
                    ],
                ],
                'search' => [
                    'value' => env('SEARCH_STRING', 'sdfsdfsfsf'),
                ],
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $totalInfo = (new Collection($response->json('data')))->count();

        $response->assertStatus(200);
        $this->assertTrue($totalInfo === 0);
    }
}
