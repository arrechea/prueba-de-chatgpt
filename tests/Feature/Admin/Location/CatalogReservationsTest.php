<?php

namespace Tests\Feature\Admin\Location;

use App\Admin;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogReservationsTest extends TestCase
{
    /**
     * Testeo de las reservaciones de un usuario utilizando un término de búsqueda.
     * Da ok si regresa un código 200.
     */
    public function testUserReservationsControllerWithSearch()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));
        $profile = UserProfile::where([
            'email'        => env('USER_EMAIL'),
            'companies_id' => env('COMPANY'),
        ])->first();
        $search = env('SEARCH_STRING');

        $route = route('admin.company.brand.locations.reservations.users.reservations.url', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'profile'  => $profile,
        ]);

        $response = $this->actingAs(Admin::where('email',env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route, [
                'search' => [
                    'value' => $search,
                ],
            ]);

        $response->assertStatus(200);
    }

    /**
     * Testeo de las reservaciones de un usuario sin utilizar términos de búsqueda.
     * Da ok si regresa un código 200.
     */
    public function testUserReservationsControllerWithoutSearch()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));
        $profile = UserProfile::where([
            'email'        => env('USER_EMAIL'),
            'companies_id' => env('COMPANY'),
        ])->first();

        $route = route('admin.company.brand.locations.reservations.users.reservations.url', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'profile'  => $profile,
        ]);


        $response = $this->actingAs(Admin::where('email',env('USER_EMAIL'))->first(), 'admin')
            ->json('get', $route);

        $response->assertStatus(200);
    }
}
