<?php

namespace Tests\Feature\Admin\Location\Purchases;

use App\Admin;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Tests\TestCase;

class UserPurchasesList extends TestCase
{
    /**
     * Testeo de los créditos de un usuario utilizando un término de búsqueda.
     * Da ok si regresa un código 200.
     */
    public function testListWithSearch()
    {
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));
        $profile = UserProfile::where([
            'email'        => env('USER_EMAIL'),
            'companies_id' => env('COMPANY'),
        ])->first();
        $search = env('SEARCH_STRING');

        $route = route('admin.company.brand.locations.users.ajax.purchase', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'profile'  => $profile->id,
        ]);

        $response = $this->actingAs(Admin::find(11), 'admin')
            ->json('get', $route, [
                'search' => [
                    'value' => $search,
                ],
            ]);

        $response->assertStatus(200);
    }

    public function testListWithoutSearch(){
        $company = Company::find(env('COMPANY'));
        $brand = Brand::find(env('BRAND'));
        $location = Location::find(env('LOCATION'));
        $profile = UserProfile::where([
            'email'        => env('USER_EMAIL'),
            'companies_id' => env('COMPANY'),
        ])->first();

        $route = route('admin.company.brand.locations.users.ajax', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'profile'  => $profile->id,
        ]);

        $response = $this->actingAs(Admin::find(11), 'admin')
            ->json('get', $route);

        $response->assertStatus(200);
    }
}
