<?php

namespace Tests\Feature\Admin\Company;

use App\Admin;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogControlPanelTest extends TestCase
{
    public function testPostIndex()
    {
        $company = Company::find(env('COMPANY'));
        $catalog_id = env('CATALOG_ID');
        $brand_id = env('BRAND');
        $section = env('section');
        $route = route('admin.company.special-text.control-panel.index', [
            'company' => $company,
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
                        'value' => $brand_id,
                    ],
                    [
                        'name'  => 'catalogs_id',
                        'value' => $catalog_id,
                    ],
                    [
                        'name'  => 'section',
                        'value' => $section,
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
        $catalog_id = env('CATALOG_ID');
        $brand_id = env('BRAND');
        $section = env('section');
        $route = route('admin.company.special-text.control-panel.index', [
            'company' => $company,
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
                        'value' => $brand_id,
                    ],
                    [
                        'name'  => 'catalogs_id',
                        'value' => $catalog_id,
                    ],
                    [
                        'name'  => 'section',
                        'value' => $section,
                    ],
                ],
                'search'  => [
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
