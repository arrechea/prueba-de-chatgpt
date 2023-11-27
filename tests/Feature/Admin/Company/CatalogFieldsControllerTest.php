<?php

namespace Tests\Feature\Admin\Company;

use App\Admin;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogFieldsControllerTest extends TestCase
{
    public function testPostIndex()
    {
        $company = Company::find(env('COMPANY'));
        $group_id = env('GROUP_ID');
        $route = route('admin.company.special-text.group.fields', [
            'company' => $company,
            'group'   => $group_id,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'catalogs_groups_id',
                        'value' => $group_id,
                    ],
                    [
                        'name'  => 'model_status',
                        'value' => 'all',
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
        $group_id = env('GROUP_ID');
        $route = route('admin.company.special-text.group.fields', [
            'company' => $company,
            'group'   => $group_id,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'catalogs_groups_id',
                        'value' => $group_id,
                    ],
                    [
                        'name'  => 'model_status',
                        'value' => 'all',
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
