<?php

namespace Tests\Feature\Admin\GafaFit;

use App\Admin;
use App\Http\Controllers\Admin\HomeController;
use App\Librerias\Catalog\Tables\GafaFit\CatalogCompany;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @see CatalogCompany
     */
    public function testPostIndex()
    {
        $route = route('admin.home');
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     *
     *  @see CatalogCompany::columToSearch
     *
     */
    public function testPostIndexWithSearch()
    {
        $route = route('admin.home');
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
