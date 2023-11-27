<?php

namespace Tests\Feature\Admin\GafaFit;

use App\Admin;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdmin;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdministratorControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @see CatalogAdmin
     */
    public function testPostIndex()
    {
        $route = route('admin.administrator.index');
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     * @see CatalogAdmin::ColumnToSearch
     */
    public function testPostIndexWithSearch()
    {
        $route = route('admin.administrator.index');
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
