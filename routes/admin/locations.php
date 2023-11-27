<?php
/**
 * Ruteo de locations
 * Parametros: {company}, {brand}, {location}
 * Name: admin.company.brand.locations.
 */

Route::get('/', 'Location\LocationController@dashboard')->name('dashboard');


Route::group([
    'prefix' => 'metrics',
    'as'     => 'metrics.',
], function () {
    Route::get('/sales', 'Location\Metrics\MetricsController@sales')->name('sales');
    Route::get('/dashboard', 'Location\Metrics\SalesController@dashboard')->name('dashboard');

    Route::group([
        'prefix' => 'reservations',
        'as'     => 'reservations.',
    ], function () {
        Route::get('', 'Location\Metrics\ReservationsMetricsController@index')->name('index');
        Route::get('/location', 'Location\Metrics\ReservationsMetricsController@location')->name('location');
        Route::get('/location/data', 'Location\Metrics\ReservationsMetricsController@locationData')->name('location.ajax');
        Route::get('/location/compare/data', 'Location\Metrics\ReservationsMetricsController@compareLocationData')->name('location.compare.ajax');
        Route::get('/get', 'Location\Metrics\ReservationsMetricsController@reservations')->name('ajax');
        Route::get('/get/compare', 'Location\Metrics\ReservationsMetricsController@compareReservations')->name('compare.ajax');
        Route::get('/get/rooms/reservations', 'Location\Metrics\ReservationsMetricsController@reservationsByRoom')->name('rooms');
    });
    Route::group([
        'prefix' => 'users',
        'as'     => 'users.',
    ], function () {
        Route::get('', 'Location\Metrics\UsersMetricsController@index')->name('index');
        Route::get('/get/new', 'Location\Metrics\UsersMetricsController@returnUsers')->name('new');
        Route::get('/get/top', 'Location\Metrics\UsersMetricsController@topUsers')->name('top');
    });

    Route::group([
        'prefix' => 'sales',
        'as'     => 'sales.',
    ], function () {

        Route::get('/sales', 'Location\Metrics\MetricsController@sales')->name('ajax');
        Route::get('/index', 'Location\Metrics\SalesController@index')->name('index');
        Route::get('/sales/payment', 'Location\Metrics\SalesController@purchaseByPaymentTypes')->name('payments.ajax');
    });

    Route::group([
        'prefix' => 'staff',
        'as'     => 'staff.',
    ], function () {
        Route::get('/staff-metrics', 'Location\Metrics\StaffController@index')->name('index');
    });
});

Route::group([
    'prefix' => 'users',
    'as'     => 'users.',
], function () {
    Route::get('/search', 'Location\UserController@searchUser')->name('search');
    Route::get('/meetings', 'Location\UserController@calendar')->name('meetings');
});


Route::group([
    'prefix' => 'rooms',
    'as'     => 'rooms.',
], function () {
    Route::get('/', 'Location\RoomsController@index')->name('index');
    Route::get('/create', 'Location\RoomsController@create')->name('create');
    Route::get('/edit/{room}', 'Location\RoomsController@edit')->name('edit');
    Route::get('/delete/{room}', 'Location\RoomsController@delete')->name('delete');
    Route::post('/create', 'Location\RoomsController@saveNew')->name('save.new');
    Route::post('/edit/{room}', 'Location\RoomsController@saveEdit')->name('save');
    Route::post('/delete/{room}', 'Location\RoomsController@deletePost')->name('delete.post');
    Route::get('/ajax/{room}', 'Location\RoomsController@meetingsAjax')->name('ajax');
    Route::get('/ajax-meetings/{room}', 'Location\RoomsController@reservationsAjax')->name('ajax.meetings');


    Route::group([
        'prefix' => '{room}/meetings',
        'as'     => 'meetings.',
    ], function () {
        Route::get('/', 'Location\MeetingController@events')->name('events');
        Route::get('/staff', 'Location\MeetingController@staff')->name('staff');
        Route::get('/services/{service?}', 'Location\MeetingController@childServices')->name('services');
        Route::get('/create', 'Location\MeetingController@create')->name('create');
        Route::post('/create', 'Location\MeetingController@saveNew')->name('save.new');
        Route::get('/create/{meeting}', 'Location\MeetingController@edit')->name('edit');
        Route::post('/create/{meeting}', 'Location\MeetingController@save')->name('save');
        Route::post('/create/{meeting}/delete', 'Location\MeetingController@delete')->name('delete');
        Route::get('/calendar', 'Location\Reservations\RoomReservationsController@calendar')->name('calendar');
        Route::post('/repeat-week', 'Location\MeetingController@repeatWeek')->name('repeat-week');
        Route::get('/refresh/{meeting}', 'Location\RoomsController@individualMeeting')->name('refresh.individual');
        Route::post('/refresh/{meeting}', 'Location\RoomsController@refreshIndividualMeeting')->name('save.refresh');
        Route::get('/delete/{meeting}', 'Location\RoomsController@deleteMeeting')->name('delete.meeting');
        Route::post('/delete/{meeting}', 'Location\RoomsController@deleteIndividualMeeting')->name('delete.individual');
        Route::get('/delete-with-reservations/{meeting}', 'Location\RoomsController@deleteMeetingWithReservations')->name('delete.reservations');
        Route::post('/delete-with-reservations/{meeting}', 'Location\RoomsController@deleteIndividualMeetingWithReservations')->name('delete.mreservations');
        Route::get('/update-meetings', 'Location\RoomsController@updateMeetingsWithoutReservations')->name('update.boton');
        Route::post('/update-meetings', 'Location\RoomsController@updateMeetingsWithoutReservationsDiferentConfigurations')->name('update.meetings');
        Route::get('/cancel-meetings', 'Location\RoomsController@cancelMeetingsWithoutReservations')->name('cancel.boton');
        Route::post('/cancel-meetings', 'Location\RoomsController@cancelMeetingsWithoutReservationsDifferentConfigurations')->name('cancel.meetings');
        Route::get('/cancel-with-reservations', 'Location\RoomsController@cancelMeetingsWithReservations')->name('cancel');
        Route::post('/cancel-with-reservations', 'Location\RoomsController@cancelMeetingsWithReservationsDifferentConfigurations')->name('cancel.reservations');
    });
});

Route::group([
    'prefix' => 'reservations',
    'as'     => 'reservations.',
], function () {
    Route::get('/', 'Location\ReservationController@index')->name('index');
    Route::get('/create', 'Location\ReservationController@create')->name('create');
    Route::get('/userReservation', 'Location\ReservationController@user')->name('user');

    Route::get('/meetings/{meeting}', 'Location\ReservationController@seeMeeting')->name('seeMeeting');
    Route::get('/meetingsprint/{meeting}', 'Location\ReservationController@seeMeetingPrint')->name('seeMeeting.print');
    Route::get('/delete/{meeting}/{reservation}', 'Location\ReservationController@delete')->name('delete');
    Route::post('/delete/{meeting}/{reservation}', 'Location\ReservationController@deletePost')->name('delete.post');

    Route::post('meetings/{meeting}/attendance-list', 'Location\ReservationController@attendanceList')->name('attendance-list');

    Route::post('/{reservation}/position', 'Location\ReservationController@positionChange')->name('position');
    Route::get('/{reservation}/print-entry', 'Location\ReservationController@printNewReservation')->name('print-entry');

    //Reservacion
    \App\Librerias\Reservation\LibReservationForm::routes('Location\ReservationController');

    Route::group([
        'prefix' => 'users',
        'as'     => 'users.',
    ], function () {
        Route::get('/', 'Location\Reservations\UsersReservationsController@Index')->name('index');
        Route::get('/reservation/{profile}', 'Location\Reservations\UsersReservationsController@seeReservations')->name('reservation');
        Route::get('/reservation-url/{profile}', 'Location\Reservations\UsersReservationsController@reservationUrl')->name('reservations.url');
        Route::get(
            '/waitlist-url/{profile}',
            'Location\Reservations\UsersReservationsController@waitlistUrl'
        )->name('waitlist.url');
    });

    Route::group([
        'prefix' => 'staff',
        'as'     => 'staff.',
    ], function () {
        Route::get('/', 'Location\Reservations\StaffReservationsController@Index')->name('index');
        Route::get('/{staff}/meetings', 'Location\Reservations\StaffReservationsController@meetings')->name('meetings');
        Route::get('/{staff}/calendar', 'Location\Reservations\StaffReservationsController@calendar')->name('calendar');
    });

    Route::group([
        'prefix' => 'room',
        'as'     => 'room.',
    ], function () {
        Route::get('/', 'Location\Reservations\RoomReservationsController@Index')->name('index');
        Route::get('/{room}/meetings', 'Location\Reservations\RoomReservationsController@meetings')->name('meetings');
        Route::get('/{room}/calendar', 'Location\Reservations\RoomReservationsController@calendar')->name('calendar');
    });

});

Route::group([
    'prefix' => 'settings',
    'as'     => 'settings.',
], function () {
    Route::get('/', 'Location\SettingsController@index')->name('index');
    Route::post('/location', 'Location\SettingsController@saveLocation')->name('save.location');
    Route::get('/delete/{LocationToEdit}', 'Location\SettingsController@delete')->name('delete');
    Route::post('/delete/{LocationToEdit}', 'Location\SettingsController@deletePost')->name('delete.post');
});

Route::group([
    'prefix' => 'calendar',
    'as'     => 'calendar.',
], function () {
    Route::get('/{room?}', 'Location\CalendarController@index')->name('index');
});

Route::group([
    'prefix' => 'users',
    'as'     => 'users.',
], function () {
    Route::get('/', 'Location\UserLocationController@index')->name('index');
    Route::get('/info/{profile}', 'Location\UserLocationController@info')->name('info');
    Route::get('/purchase/{profile}', 'Location\UserLocationController@purchaseInfo')->name('purchase');
    Route::get('/purchase-info/{purchase}', 'Location\UserLocationController@purchaseItems')->name('purchase.info');
    Route::get('/credits/{profile}', 'Location\UserLocationController@credits')->name('credits');
    Route::get('/ajax-credits/{profile}', 'Location\UserLocationController@activeAjax')->name('ajax');
    Route::get('/ajax-creditspast/{profile}', 'Location\UserLocationController@pastCredits')->name('ajax.past');
    Route::get('/ajax-creditsused/{profile}', 'Location\UserLocationController@usedCredits')->name('ajax.used');
    Route::get('/ajax-creditsdelete/{profile}', 'Location\UserLocationController@deleteCredits')->name('ajax.delete');
    Route::get('/ajax-purchase/{profile}', 'Location\UserLocationController@userPurchases')->name('ajax.purchase');
    Route::get('/edit/{user?}', 'Location\UserLocationController@edit')->name('edit');
    Route::post('/save/{user?}', 'Location\UserLocationController@save')->name('save');
    Route::post('/new/save', 'Location\UserLocationController@saveNew')->name('save.new');
    Route::get('/memberships/active/{profile}', 'Location\UserLocationController@activeMemberships')->name('memberships.active');
    Route::get('/memberships/expired/{profile}', 'Location\UserLocationController@expiredMemberships')->name('memberships.expired');
    Route::post('/memberships/{profile}/delete/{membership}', 'Location\UserLocationController@deleteMembership')->name('memberships.delete');
    Route::post('/memberships/{profile}/edit/{membership}', 'Location\UserLocationController@saveMembership')->name('memberships.save');
    Route::post('/credits/{profile}', 'Location\UserLocationController@saveCredit')->name('credits.save');
    Route::post('/credits/{profile}/delete', 'Location\UserLocationController@deleteCredit')->name('credits.delete');
    Route::get('/unblock-confirm/{profile}', 'Location\UserLocationController@unblockConfirm')->name('unblock.confirm');
    Route::post('/unblock-user/{profile}', 'Location\UserLocationController@unblockUser')->name('unblock.user');
});

Route::group([
    'prefix' => 'purchases',
    'as'     => 'purchases.',
], function () {
    Route::get('/create', 'Location\PurchaseController@create')->name('create');
});

Route::group([
    'prefix' => 'room-maps',
    'as'     => 'room-maps.',
], function () {
    Route::get('/index', 'Location\Rooms\RoomsMapsController@index')->name('index');
    Route::get('/create', 'Location\Rooms\RoomsMapsController@create')->name('create');
    Route::post('/create', 'Location\Rooms\RoomsMapsController@saveNew')->name('save.new');
    Route::get('/edit/{maps}', 'Location\Rooms\RoomsMapsController@edit')->name('edit');
    Route::post('/edit/{maps}', 'Location\Rooms\RoomsMapsController@saveEdit')->name('save.edit');
    Route::get('/clone/{maps}', 'Location\Rooms\RoomsMapsController@cloneMaps')->name('clone');
    Route::get('/cloning/{maps}', 'Location\Rooms\RoomsMapsController@clonePost')->name('clone.map');
    Route::post('/edit-map/{maps}', 'Location\Rooms\RoomsMapsController@saveMap')->name('edit.map.save');
});


Route::group([
    'prefix' => 'maps-position',
    'as'     => 'maps-position.',
], function () {
    Route::get('/index', 'Location\Rooms\RoomsPositionsController@index')->name('index');
    Route::get('/create', 'Location\Rooms\RoomsPositionsController@create')->name('create');
    Route::get('/edit/{position}', 'Location\Rooms\RoomsPositionsController@edit')->name('edit');
    Route::post('/create', 'Location\Rooms\RoomsPositionsController@saveNew')->name('save.new');
    Route::post('/edit/{position}', 'Location\Rooms\RoomsPositionsController@saveEdit')->name('save');


});

Route::group([
    'prefix' => 'giftcards',
    'as'     => 'giftcards.',
], function () {
    Route::get('/assign', 'Location\GiftCardController@assign')->name('assign');
    Route::get('/search', 'Location\GiftCardController@searchUser')->name('search');
    Route::post('/save', 'Location\GiftCardController@save')->name('save');
});

Route::group([
    'prefix' => 'waitlist',
    'as'     => 'waitlist.',
], function () {
    Route::post('/create/{meeting}', 'Location\WaitlistController@create')->name('create');
    Route::post('/delete/{waitlist?}', 'Location\WaitlistController@delete')->name('delete');
    Route::post('/make-overbooking/{waitlist}', 'Location\WaitlistController@makeOverbooking')->name('make.overbooking');
});

Route::group([
    'prefix' => 'overbooking',
    'as'     => 'overbooking.',
], function () {
    Route::post('/create/{meeting}', 'Location\OverbookingController@create')->name('create');
    Route::post('/delete/{waitlist?}', 'Location\OverbookingController@delete')->name('delete');
});

Route::group([
    'prefix' => 'subscriptions',
    'as'     => 'subscriptions.',
], function () {
    Route::post('{purchase}/cancel', 'Location\UserLocationController@cancelSubscription')->name('cancel');
});

Route::group([
    'prefix' => 'gympass',
    'as'     => 'gympass.',
], function () {
    Route::group([
        'prefix' => 'checkin',
        'as'     => 'checkin.',
    ], function () {
        Route::get('/', 'Location\GympassAdminCheckinController@index')->name('index');
        Route::post('/{checkin}', 'Location\GympassAdminCheckinController@processCheckin')->name('process-checkin');
        Route::post('/reject/{checkin}', 'Location\GympassAdminCheckinController@rejectCheckin')->name('reject-checkin');
    });
});
