<?php

namespace Tests\Feature;

use App\Admin;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @see CatalogUserProfile
     */
    public function testPostIndex()
    {
        $company = Company::find(env('COMPANY'));
        $route = route('admin.company.users.index', [
            'company' => $company,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'comp_id',
                        'value' => $company->id,
                    ],
                ],
            ], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     * @see CatalogUserProfile::columnToSearch
     */
    public function testPostIndexWithSearch()
    {
        $company = Company::find(env('COMPANY'));
        $route = route('admin.company.users.index', [
            'company' => $company,
        ]);
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [
                'filters' => [
                    [
                        'name'  => 'comp_id',
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
