<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*********************************************
 * Auth
 ********************************************/
Route::post('password/email', 'AuthApi\ForgotPasswordController@getResetToken')->name('password.request');
Route::post('password/reset', 'AuthApi\ResetPasswordController@reset');
Route::post('register', 'User\UserApiController@create')->name('register');
Route::get('register/verify', 'User\UserApiController@verifyUser')->name('user.verify');
Route::get('resend', 'User\UserApiController@resendVerificationEmail')->name('resend');
Route::post('login', 'AuthApi\ApiLoginController@login');

/*********************************************
 * Places
 ********************************************/
Route::get('places/countries', 'PlacesApiController@countries')->name('countries');
Route::get('places/countries/{country}/cities', 'PlacesApiController@cities')->name('countries.cities');
Route::get('places/countries/{country}/states', 'PlacesApiController@states')->name('countries.states');
Route::get('places/countries/{country}/states/{state}', 'PlacesApiController@citiesByState')->name('countries.states.cities');


/*********************************************
 * Brands
 ********************************************/
Route::get('brand', 'Brand\BrandApiController@index')->name('brands');
Route::group([
    'prefix'     => 'brand/{brand}',
    'as'         => 'brand.',
    'middleware' => ['api.brand'],
], function () {
    Route::get('/', 'Brand\BrandApiController@get')->name('brands');
    Route::get('/widget', 'Reservation\WidgetController@getWidget')->name('widget');
    /*
     * Staff
     */
    Route::group([
        'prefix' => 'staff',
        'as'     => 'staff.',
    ], function () {
        Route::get('/', 'Staff\StaffController@index')->name('index');
        Route::post('/', 'Staff\StaffController@create')->name('create')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::post('/{staff}/update', 'Staff\StaffController@update')->name('update')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::delete('/{staff}', 'Staff\StaffController@delete')->name('delete')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::get('/{id}/restore', 'Staff\StaffController@restore')->name('restore')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::get('/{staff}', 'Staff\StaffController@get')->name('get');
        Route::get('/{staff}/next-meetings', 'Staff\StaffController@meetings')->name('get.meetings');
        Route::get('/{staff}/special-text', 'Staff\StaffController@specialTexts')->name('get.special-texts');
    });
    /*
     * Services
     */
    Route::group([
        'prefix' => 'service',
        'as'     => 'service.',
    ], function () {
        Route::get('/', 'Service\ServiceController@index')->name('get');
        Route::get('/{serviceToSee}', 'Service\ServiceController@get')->name('see');
        Route::get('/{serviceToSee}/special-text', 'Service\ServiceController@specialTexts')->name('see.special-texts');
        Route::get('/{serviceToSee}/meetings', 'Service\ServiceController@meetings')->name('meetings');
    });

    /*
     * Rooms
     */
    Route::group([
        'prefix' => 'room',
        'as'     => 'room.',
    ], function () {
        Route::get('/', 'Room\RoomController@getRoomsInBrand')->name('brand.list');
        Route::get('/location/{location}', 'Room\RoomController@getRoomsInLocation')->name('location.list');
    });


    /*
     * Location
     */
    Route::group([
        'prefix' => 'location',
        'as'     => 'location.',
    ], function () {
        Route::get('/', 'Location\LocationController@index')->name('get');
        Route::group([
            'middleware' => 'api.location',
        ], function () {
            Route::get('/{locationToSee}', 'Location\LocationController@get')->name('see');

            Route::get('/{locationToSee}/meetings', 'Meeting\MeetingController@index')->name('meetings');
            Route::get('/{locationToSee}/rooms/{room}/meetings', 'Meeting\MeetingController@roomMeetings')->name('room.meetings');
        });

        /*
         * Reservation
         */
        Route::group([
            'prefix'     => '/{locationToSee}/reservation',
            'as'         => 'reservation.',
            'middleware' => [
                'widget',
//                'auth:api',
//                'apiUser',
            ],
        ], function () {
            \App\Librerias\Reservation\LibReservationForm::routes('Reservation\ApiReservationController');
        });
    });
    /*
     * Combos
     */
    Route::group([
        'prefix' => 'combos',
        'as'     => 'combos.',
    ], function () {
        Route::get('/', 'Combo\CombosApiController@index')->name('get');

        Route::group([
            'middleware' => [
                'auth:api',
                'apiUser',
            ],
        ], function () {
            Route::get('/userPosibilities', 'Combo\CombosApiController@userPosibilities')->name('get.userPosibilities');
        });
    });
    /*
     * Memberships
     */
    Route::group([
        'prefix' => 'membership',
        'as'     => 'membership.',
    ], function () {
        Route::get('/', 'Membership\MembershipApiController@index')->name('get');

        Route::group([
            'middleware' => [
                'auth:api',
                'apiUser',
            ],
        ], function () {
            Route::get('/userPosibilities', 'Membership\MembershipApiController@userPosibilities')->name('get.userPosibilities');
        });
    });
});

/*********************************************
 * Endpoints con autentificacion
 ********************************************/
Route::group([
    'middleware' => [
        'auth:api',
        'apiUser',
        'authCompany',
    ],
], function () {

    /*********************************************
     * Users
     ********************************************/
    Route::group([
        'prefix' => 'users',
        'as'     => 'users.',
    ], function () {
        Route::get('/', 'User\UserApiController@index')->name('index');
        Route::post('/', 'User\UserApiController@create')->name('create')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::post('/{user}/update', 'User\UserApiController@update')->name('update')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::delete('/{id}', 'User\UserApiController@delete')->name('delete')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
        Route::get('/{id}/restore', 'User\UserApiController@restore')->name('restore')
            ->middleware(['auth:api', 'api.admin', 'scopes:admin']);
    });

    Route::group([
        'prefix' => 'me',
        'as'     => 'me.',
    ], function () {
        Route::get('', 'User\UserApiController@getMe')->name('get');
        Route::post('', 'User\UserApiController@putMe')->name('update');

        //Purchases
        Route::get('location/{locationToSee}/purchases', 'User\UserApiController@userPurchaseInLocation')->name('location.purchases')->middleware('api.location');
        Route::group([
            'middleware' => 'api.brand',
        ], function () {
            Route::get('brand/{brand}/purchases', 'User\UserApiController@userPurchaseInBrand')->name('brand.purchases');
            Route::get('brand/{brand}/purchases/{purchase}', 'User\UserApiController@userPurchase')->name('purchase');


            //Reservations
            Route::get('brand/{brand}/reservation-past', 'User\UserApiController@reservationsPastInBrand')->name('brand.reservations.past');
            Route::get('brand/{brand}/reservation-future', 'User\UserApiController@reservationsFutureInBrand')->name('brand.reservations.futures');
            Route::post('brand/{brand}/reservation-future/{reservation}/cancel', 'User\UserApiController@reservationCancel')->name('brand.reservations.cancel');
            Route::get('brand/{brand}/reservations/{reservation}', 'User\UserApiController@getReservation')->name('brand.reservations.get');

            //Credits
            Route::get('brand/{brand}/credits', 'User\UserApiController@credits')->name('brand.credits');

            //Memberships
            Route::get('brand/{brand}/memberships', 'User\UserApiController@memberships')->name('location.memberships');

            //Payments
            Route::group([
                'prefix' => 'payments',
                'as'     => 'payments.',
            ], function () {
                Route::get('brand/{brand}/payment-methods', 'User\PaymentsApiController@getPaymentMethodsInBrand')->name('methods');
                Route::post('brand/{brand}/method/{paymentType}', 'User\PaymentsApiController@addPaymentMethod')->name('methods.add');
                Route::post('brand/{brand}/method/{paymentType}/remove', 'User\PaymentsApiController@removePaymentMethod')->name('methods.remove');

            });

            Route::group([
                'prefix' => 'brand/{brand}',
                'as'     => 'brand.',
            ], function () {
                //Waitlist
                Route::group([
                    'prefix' => 'waitlist',
                    'as'     => 'waitlist.',
                ], function () {
                    Route::get('future', 'User\UserApiController@waitlistFutureInBrand')->name('futures');
                    Route::get('past', 'User\UserApiController@waitlistPastInBrand')->name('past');
                    Route::post('remove/{waitlist}', 'User\WaitlistApiController@cancel')->name('cancel');
                });

                Route::group([
                    'prefix' => 'subscriptions',
                    'as'     => 'subscriptions.',
                ], function () {
                    Route::get('/', 'User\UserApiController@subscriptionsList')->name('index');
                    Route::post('/{subscription}/cancel', 'User\UserApiController@subscriptionCancel')->name('cancel');
                });
            });
        });
        //Gift Cards
        Route::group([
            'prefix' => 'giftcards',
            'as'     => 'giftcards.',
        ], function () {
            Route::post('brand/{brand}/redeem', 'GiftCards\GiftCardsApiController@redeem')->name('redeem')->middleware('api.brand');
        });
    });

    Route::post('logout', 'User\UserApiController@logout');
});


//Special Texts
Route::group([
    'prefix' => 'special-text',
    'as'     => 'special-text.',
], function () {
    Route::get('form/{catalog}/{brand?}', 'SpecialText\SpecialTextApiController@form')->name('form');
    Route::get('values/{catalog}/{model}/{brand?}', 'SpecialText\SpecialTextApiController@values')->name('values');
});

/*********************************************
 * onboarding
 ********************************************/
Route::group([
    'prefix' => 'onboarding',
    'as'     => 'onboarding',
], function () {
    Route::post('initial', 'OnBoarding\LeadsApiController@create')->name('create');
    Route::post('company', 'OnBoarding\LeadsApiController@company')->name('company');
    Route::post('locations', 'OnBoarding\LeadsApiController@locations')->name('locations')->middleware('auth:leads');
    Route::post('memberships-and-combos', 'OnBoarding\LeadsApiController@memberships')->name('memberships')->middleware('auth:leads');
    Route::post('types-of-payment', 'OnBoarding\LeadsApiController@payments')->name('payments')->middleware('auth:leads');
    Route::post('update-step', 'OnBoarding\LeadsApiController@updateStep')->name('update-step');//->middleware('auth:leads');
    Route::post('finish-wizard', 'OnBoarding\LeadsApiController@finishWizard')->name('finish')->middleware('auth:leads');
    Route::get('all-countries', 'OnBoarding\LeadsApiController@countries')->name('countries');//->middleware('auth:leads');
    Route::get('all-states', 'OnBoarding\LeadsApiController@states')->name('states');//->middleware('auth:leads');
    Route::get('all-credits', 'OnBoarding\LeadsApiController@credits')->name('credits');//->middleware('auth:leads');
    Route::get('all-types-of-payment', 'OnBoarding\LeadsApiController@typesOfPayments')->name('types-of-payment');//->middleware('auth:leads');
    Route::get('authenticate', 'OnBoarding\LeadsApiController@authenticate')->name('authenticate');
    Route::post('generate-token', 'OnBoarding\LeadsApiController@generateToken')->name('generate-token');

    //    Route::get('values/{catalog}/{model}/{brand?}', 'SpecialText\SpecialTextApiController@values')->name('values');
    Route::get('plans', 'OnBoarding\CheckoutController@plans')->name('plans');
    Route::post('subscribe', 'OnBoarding\CheckoutController@subscription')->middleware('auth:leads')->name('subscribe');
});
