<?php

namespace Tests\Feature\Admin\Company;

use App\Admin;
use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Models\Company\Company;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @see CatalogBrand
     */
    public function testPostDashboard()
    {
        $company = Company::find(env('COMPANY'));
        $route = route('admin.company.dashboard', [
            'company' => $company,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     * @see CatalogBrand::ColumnToSearch
     */
    public function testPostDashboardWithSearch()
    {
        $company = Company::find(env('COMPANY'));
        $route = route('admin.company.dashboard', [
            'company' => $company,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
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
