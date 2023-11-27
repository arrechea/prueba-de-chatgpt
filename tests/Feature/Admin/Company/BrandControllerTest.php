<?php

namespace Tests\Feature\Admin\Company;

use App\Admin;
use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @see CatalogBrand
     */
    public function testPostIndex()
    {
        $company = Company::find(env('COMPANY'));
        $route = route('admin.company.brands.index', [
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
                ],
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     * @see CatalogBrand::columnToSearch
     */
    public function testPostIndexWithSearch()
    {
        $company = Company::find(env('COMPANY'));
        $route = route('admin.company.brands.index', [
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
