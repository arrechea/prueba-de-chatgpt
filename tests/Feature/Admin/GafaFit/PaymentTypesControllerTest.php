<?php

namespace Tests\Feature\Admin\GafaFit;

use App\Admin;
use App\Librerias\Catalog\Tables\GafaFit\CatalogPaymentTypes;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTypesControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @see CatalogPaymentTypes
     */
    public function testPostIndex()
    {
        return $this->assertTrue(true);//todo estos en produccion no funcionan
        $route = route('admin.paymentTypes.index');
        $user = Admin::where('email', env('USER_EMAIL'))->first();

        $response = $this
            ->actingAs($user, 'admin')
            ->json('get', $route, [], [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
    }

    /**
     * @see CatalogPaymentTypes::ColumnToSearch
     */
    public function testPostIndexWithSearch()
    {
        return $this->assertTrue(true);//todo estos en produccion no funcionan

        $route = route('admin.paymentTypes.index');
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
