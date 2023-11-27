<?php
/**
 * Ruteo Brand
 * Parametros: {company}, {brand}
 * Name: admin.company.brand.
 */

Route::get('/', 'Brand\BrandController@dashboard')->name('dashboard');


Route::group([
    'prefix' => 'locations',
    'as'     => 'locations.',
], function () {
    Route::get('/', 'Brand\LocationController@index')->name('index');
    Route::get('/edit/{LocationToEdit}', 'Brand\LocationController@edit')->name('edit');
    Route::post('/edit/{LocationToEdit}', 'Brand\LocationController@saveEdit')->name('save');
    Route::get('/create', 'Brand\LocationController@create')->name('create');
    Route::post('/create', 'Brand\LocationController@saveNew')->name('save.new');
    Route::get('/delete/{LocationToEdit}', 'Brand\LocationController@delete')->name('delete');
    Route::post('/delete/{LocationToEdit}', 'Brand\LocationController@deletePost')->name('delete.post');

});

Route::group([
    'prefix' => 'mails',
    'as'     => 'mails.',
], function () {
    Route::get('/create', 'Brand\Mails\MailsController@create')->name('create');

//    Route::group([
//        'prefix' => 'welcome',
//        'as'     => 'welcome.',
//    ], function (){
//        Route::get('/create/','Brand\Mails\WelcomeController@create')->name('create');
//        Route::post('/create/','Brand\Mails\WelcomeController@saveNew')->name('save.new');
////        Route::get('/delete/{welcomeEdit}','Brand\Mails\WelcomeController@delete')->name('delete');
////        Route::post('/delete/{welcomeEdit}','Brand\Mails\WelcomeController@deletePost')->name('delete.post');
//    });

    Route::group([
        'prefix' => 'reservation-cancel',
        'as'     => 'reservation-cancel.',

    ], function () {
        Route::get('/create', 'Brand\Mails\ReservationCancelController@create')->name('create');
        Route::post('/create', 'Brand\Mails\ReservationCancelController@saveNew')->name('save.new');
//        Route::get('/delete/{reservationCancel}','Brand\Mails\ReservationCancelController@delete')->name('delete');
//        Route::post('/delete/{reservationCancel}','Brand\Mails\ReservationCancelController@deletePost')->name('delete.post');

    });

    Route::group([
        'prefix' => 'reservation-confirm',
        'as'     => 'reservation-confirm.',

    ], function () {
        Route::get('/create', 'Brand\Mails\ReservationConfirmController@create')->name('create');
        Route::post('/create', 'Brand\Mails\ReservationConfirmController@saveNew')->name('save.new');
        //Route::get('/delete/{reservationConfirm}', 'Brand\Mails\ReservationConfirmController@delete')->name('delete');
        //Route::post('/delete/{reservationConfirm}', 'Brand\Mails\ReservationConfirmController@deletePost')->name('delete.post');
    });

//    Route::group([
//        'prefix' => 'forget-password',
//        'as' => 'forget-password.',
//    ],function () {
//        Route::get('/create', 'Brand\Mails\ForgetPasswordController@create')->name('create');
//        Route::post('/create', 'Brand\Mails\ForgetPasswordController@saveNew')->name('save.new');
//       // Route::get('/delete/{password}', 'Brand\Mails\ForgetPasswordController@delete')->name('delete');
//        //Route::post('/delete/{password}', 'Brand\Mails\ForgetPasswordController@deletePost')->name('delete.post');
//    });

    Route::group([
        'prefix' => 'mail-purchase',
        'as'     => 'mail-purchase.',
    ], function () {
        Route::get('/create', 'Brand\Mails\MailsPurchasesController@create')->name('create');
        Route::post('/create', 'Brand\Mails\MailsPurchasesController@saveNew')->name('save.new');
    });

    Route::group([
        'prefix' => 'waitlist-confirm',
        'as'     => 'waitlist-confirm.',

    ], function () {
        Route::get('/create', 'Brand\Mails\WaitlistConfirmController@create')->name('create');
        Route::post('/create', 'Brand\Mails\WaitlistConfirmController@saveNew')->name('save.new');
    });

    Route::group([
        'prefix' => 'waitlist-cancel',
        'as'     => 'waitlist-cancel.',

    ], function () {
        Route::get('/create', 'Brand\Mails\WaitlistCancelController@create')->name('create');
        Route::post('/create', 'Brand\Mails\WaitlistCancelController@saveNew')->name('save.new');
    });

    Route::group([
        'prefix' => 'invitation-confirm',
        'as'     => 'invitation-confirm.',

    ], function () {
        Route::get('/edit', 'Brand\Mails\InvitationConfirmController@edit')->name('edit');
        Route::post('/edit', 'Brand\Mails\InvitationConfirmController@save')->name('save');
    });

    Route::group([
        'prefix' => 'subscription-confirm',
        'as'     => 'subscription-confirm.',

    ], function () {
        Route::get('/edit', 'Brand\Mails\SubscriptionConfirmController@edit')->name('edit');
        Route::post('/edit', 'Brand\Mails\SubscriptionConfirmController@save')->name('save');
    });

    Route::group([
        'prefix' => 'subscription-error',
        'as'     => 'subscription-error.',

    ], function () {
        Route::get('/edit', 'Brand\Mails\SubscriptionErrorController@edit')->name('edit');
        Route::post('/edit', 'Brand\Mails\SubscriptionErrorController@save')->name('save');
    });
});

Route::group([
    'prefix' => 'calendar',
    'as'     => 'calendar.',
], function () {
    Route::get('/', 'Brand\CalendarController@index')->name('index');

});
Route::group([
    'prefix' => 'store',
    'as'     => 'store.',
], function () {
    Route::get('/', 'Brand\StoreController@index')->name('index');

});

Route::group([
    'prefix' => 'staff',
    'as'     => 'staff.',
], function () {
    Route::get('/', 'Brand\StaffController@index')->name('index');
    Route::get('/create', 'Brand\StaffController@create')->name('create');
    Route::get('/edit/{staff}', 'Brand\StaffController@edit')->name('edit');
    Route::get('/delete/{staff}', 'Brand\StaffController@delete')->name('delete');
    Route::post('/create', 'Brand\StaffController@saveNew')->name('save.new');
    Route::post('/edit/{staff}', 'Brand\StaffController@saveEdit')->name('save');
    Route::post('/delete/{staff}', 'Brand\StaffController@deletePost')->name('delete.post');

    Route::group([
        'prefix' => '{staff}/special_text',
        'as'     => 'special_text.',
    ], function () {
        Route::get('/', 'Brand\StaffSpecialTextController@create')->name('create');
        Route::get('/{text}', 'Brand\StaffSpecialTextController@edit')->name('edit');
        Route::post('/', 'Brand\StaffSpecialTextController@saveNew')->name('save.new');
        Route::post('/{text}', 'Brand\StaffSpecialTextController@save')->name('save');
        Route::post('/{text}/delete', 'Brand\StaffSpecialTextController@delete')->name('delete');
    });
});

Route::group([
    'prefix' => 'metrics',
    'as'     => 'metrics.',

], function () {
    Route::get('/', 'Brand\MetricController@index')->name('index');
});


Route::group([
    'prefix' => 'marketing',
    'as'     => 'marketing.',

], function () {
    Route::get('/', 'Brand\MarketingController@index')->name('index');
    Route::post('/create', 'Brand\MarketingController@create')->name('create');
    Route::get('/create/modal', 'Brand\MarketingController@createModal')->name('create.modal');

    Route::group([
        'prefix' => 'offers',
        'as'     => 'offers.',
    ], function () {
        Route::get('/', 'Brand\Marketing\OffersController@index')->name('index');
        Route::get('/edit/{offer}', 'Brand\Marketing\OffersController@edit')->name('edit');
        Route::get('/create', 'Brand\Marketing\OffersController@create')->name('create');
        Route::get('/delete/{offer}', 'Brand\Marketing\OffersController@delete')->name('delete');
        Route::post('/edit/{offer}', 'Brand\Marketing\OffersController@saveEdit')->name('save');
        Route::post('/create', 'Brand\Marketing\OffersController@saveNew')->name('save.new');
        Route::post('/delete/{offer}', 'Brand\Marketing\OffersController@deletePost')->name('delete.post');
    });

    Route::group([
        'prefix' => 'combos',
        'as'     => 'combos.',
    ], function () {
        Route::get('/', 'Brand\CombosController@index')->name('index');
        Route::get('/create', 'Brand\CombosController@create')->name('create');
        Route::post('/create', 'Brand\CombosController@saveNew')->name('save.new');
        Route::get('/edit/{combos}', 'Brand\CombosController@edit')->name('edit');
        Route::post('/edit/{combos}', 'Brand\CombosController@saveEdit')->name('save');
        Route::get('/delete/{combos}', 'Brand\CombosController@delete')->name('delete');
        Route::post('/delete/{combos}', 'Brand\CombosController@deletePost')->name('delete.post');

    });

    Route::group([
        'prefix' => 'membership',
        'as'     => 'membership.',
    ], function () {
        Route::get('/', 'Brand\Marketing\MembershipController@index')->name('index');
        Route::get('/create', 'Brand\Marketing\MembershipController@create')->name('create');
        Route::post('/create', 'Brand\Marketing\MembershipController@saveNew')->name('save.new');
        Route::get('/edit/{membership}', 'Brand\Marketing\MembershipController@edit')->name('edit');
        Route::post('/edit/{membership}', 'Brand\Marketing\MembershipController@saveEdit')->name('save.edit');
        Route::get('/delete/{membership}', 'Brand\Marketing\MembershipController@delete')->name('delete');
        Route::post('/delete/{membership}', 'Brand\Marketing\MembershipController@deletePost')->name('delete.post');
        Route::post('/sync/{membership}', 'Brand\Marketing\MembershipController@sync')->name('sync');
    });

    Route::group([
        'prefix' => 'gift-card',
        'as'     => 'gift-card.',
    ], function () {
        Route::get('/', 'Brand\Marketing\GiftCardsController@index')->name('index');
        Route::get('/list', 'Brand\Marketing\GiftCardsController@indexSaas')->name('index-saas');
    });

    Route::group([
        'prefix' => 'purchases',
        'as'     => 'purchases.',
    ], function () {
        Route::get('/', 'Brand\Marketing\PurchasesController@index')->name('index');
        Route::get('/info/{purchase}', 'Brand\Marketing\PurchasesController@info')->name('info');
    });
});

Route::group([
    'prefix' => 'credits',
    'as'     => 'credits.',

], function () {
    Route::get('/', 'Brand\CreditsController@index')->name('index');
    Route::get('/create', 'Brand\CreditsController@create')->name('create');
    Route::post('/create', 'Brand\CreditsController@saveNew')->name('save.new');
    Route::get('/edit/{credit}', 'Brand\CreditsController@edit')->name('edit');
    Route::post('/edit/{credit}', 'Brand\CreditsController@saveCredit')->name('save.credit');
    Route::get('/delete/{credit}', 'Brand\CreditsController@delete')->name('delete');
    Route::post('/delete/{credit}', 'Brand\CreditsController@deletePost')->name('delete.post');
    Route::get('/services/{credit}', 'Brand\CreditsController@services')->name('services');
    Route::post('/services/{credit}', 'Brand\CreditsController@saveServices')->name('services.save');
});


Route::group([
    'prefix' => 'products',
    'as'     => 'products.',
], function () {
    Route::get('/', 'Brand\ProductController@index')->name('index');

});

Route::group([
    'prefix' => 'products-saas',
    'as'     => 'products-saas.',
], function () {
    Route::get('/', 'Brand\ProductController@indexSaas')->name('index');

});

Route::group([
    'prefix' => 'combos-saas',
    'as'     => 'combos-saas.',
], function () {
    Route::get('/', 'Brand\CombosController@indexSaas')->name('index');

});

Route::group([
    'prefix' => 'memberships-saas',
    'as'     => 'memberships-saas.',
], function () {
    Route::get('/', 'Brand\Marketing\MembershipController@indexSaas')->name('index');

});

Route::group([
    'prefix' => 'users',
    'as'     => 'users.',
], function () {
    Route::get('/', 'Brand\UserController@index')->name('index');
});

Route::group([
    'prefix' => 'administration',
    'as'     => 'administration.',
], function () {
    Route::get('/', 'Brand\AdministrationController@index')->name('index');
});

Route::group([
    'prefix' => 'services',
    'as'     => 'services.',
], function () {
    Route::get('/', 'Brand\ServicesController@index')->name('index');
    Route::get('/edit/{service}', 'Brand\ServicesController@edit')->name('edit');
    Route::get('/create', 'Brand\ServicesController@create')->name('create');
    Route::get('/create/{parent}', 'Brand\ServicesController@createChild')->name('create.child');
    Route::get('/delete/{service}', 'Brand\ServicesController@delete')->name('delete');
    Route::post('/create', 'Brand\ServicesController@saveNew')->name('save.new');
    Route::post('/create/child', 'Brand\ServicesController@saveChild')->name('save.child');
    Route::post('/edit/{service}', 'Brand\ServicesController@saveEdit')->name('save');
    Route::post('/delete/{service}', 'Brand\ServicesController@deletePost')->name('delete.post');

    Route::group([
        'prefix' => '{service}/special_text',
        'as'     => 'special_text.',
    ], function () {
        Route::get('/', 'Brand\ServiceSpecialTextController@create')->name('create');
        Route::get('/{text}', 'Brand\ServiceSpecialTextController@edit')->name('edit');
        Route::post('/{text}', 'Brand\ServiceSpecialTextController@save')->name('save');
        Route::post('', 'Brand\ServiceSpecialTextController@saveNew')->name('save.new');
        Route::post('/{text}/delete', 'Brand\ServiceSpecialTextController@delete')->name('delete');
    });
});
Route::group([
    'prefix' => 'settings',
    'as'     => 'settings.',
], function () {
    Route::get('/', 'Brand\SettingsBController@index')->name('index');
    Route::get('/delete/{brandToEdit}', 'Brand\SettingsBController@delete')->name('delete');
    Route::post('/delete/{brandToEdit}', 'Brand\SettingsBController@deletePost')->name('delete.post');
    Route::post('/brand', 'Brand\SettingsBController@saveBrand')->name('save.brand');
    Route::get('/countries', 'Brand\SettingsBController@countries')->name('countries');
});

Route::group([
    'prefix' => 'discount-code',
    'as'     => 'discount-code.',
], function () {
    Route::get('/', 'Brand\DiscountCodeController@index')->name('index');
    Route::get('/list', 'Brand\DiscountCodeController@indexSaas')->name('index-saas');
    Route::get('/create', 'Brand\DiscountCodeController@create')->name('create');
    Route::post('/create', 'Brand\DiscountCodeController@save')->name('save');
    Route::get('/edit/{discountCode}', 'Brand\DiscountCodeController@edit')->name('edit');
    Route::post('/edit/{discountCode}', 'Brand\DiscountCodeController@saveEdit')->name('save.edit');
    Route::get('/delete/{discountCode}', 'Brand\DiscountCodeController@delete')->name('delete');
    Route::post('/delete/{discountCode}', 'Brand\DiscountCodeController@deletePost')->name('delete.post');
    Route::get('/credits/{discountCode}', 'Brand\DiscountCodeController@credits')->name('credits');
    Route::post('/credits/{discountCode}', 'Brand\DiscountCodeController@creditsSave')->name('credits.save');
});

Route::group([
    'prefix' => 'reservations',
    'as'     => 'reservations.',
], function () {
    Route::get('/', 'Brand\ReservationsController@index')->name('index');
    Route::get('/info/{reservation}', 'Brand\ReservationsController@details')->name('info');
});

Route::group([
    'prefix' => 'graphics',
    'as'     => 'graphics.',
], function () {
    Route::get('/{graph}', 'Brand\GraphicsController@index')->name('index');
});
Route::group([
    'prefix' => 'metrics',
    'as'     => 'metrics.',
], function () {
    Route::group([
        'prefix' => 'reservations',
        'as'     => 'reservations.',
    ], function () {
        Route::get('', 'Brand\Metrics\ReservationsMetricsController@index')->name('index');
        Route::get('/profitability', 'Brand\Metrics\ReservationsMetricsController@profitability')->name('profitability');
        Route::get('/profitability/data', 'Brand\Metrics\ReservationsMetricsController@profitabilityData')->name('profitability.ajax');
        Route::get('/profitability/compare/data', 'Brand\Metrics\ReservationsMetricsController@compareData')->name('profitability.compare.ajax');
        Route::get('/get', 'Brand\Metrics\ReservationsMetricsController@reservations')->name('ajax');
        Route::get('/get/compare', 'Brand\Metrics\ReservationsMetricsController@compareReservations')->name('compare.ajax');
        Route::get('/get/totals', 'Brand\Metrics\ReservationsMetricsController@reservationsTotals')->name('totals');
        Route::get('/get/rooms', 'Brand\Metrics\ReservationsMetricsController@reservationsByRoom')->name('rooms');
    });
    Route::group([
        'prefix' => 'users',
        'as'     => 'users.',
    ], function () {
        Route::get('', 'Brand\Metrics\UsersMetricsController@index')->name('index');
        Route::get('/get/new', 'Brand\Metrics\UsersMetricsController@returnUsers')->name('new');
        Route::get('/get/top', 'Brand\Metrics\UsersMetricsController@topUsers')->name('top');
    });

    Route::group([
        'prefix' => 'sales',
        'as'     => 'sales.',
    ], function () {
        Route::get('/sales', 'Brand\Metrics\SalesController@sales')->name('ajax');
        Route::get('/', 'Brand\Metrics\SalesController@index')->name('index');
        Route::get('/sales/payment', 'Brand\Metrics\SalesController@purchaseByPaymentTypes')->name('payments.ajax');
    });

    Route::group([
        'prefix' => 'staff',
        'as'     => 'staff.',
    ], function () {
        Route::get('/', 'Brand\Metrics\StaffController@index')->name('index');
    });

    Route::group([
        'prefix' => 'export',
        'as'     => 'export.',
    ], function () {
        Route::get('/', 'Brand\Metrics\ExportMetricsController@index')->name('index');
        Route::get('/combos', 'Brand\Metrics\ExportMetricsController@Combosexport')->name('combos');
        Route::get('/membership', 'Brand\Metrics\ExportMetricsController@MembershipExport')->name('membership');
        Route::get('/monthly', 'Brand\Metrics\ExportMetricsController@usersByMonth')->name('monthly');
        Route::get('/export/{start}/{end}', 'Brand\Metrics\ExportMetricsController@export')->name('export');
        Route::get('/exportCombo/{start}/{end}', 'Brand\Metrics\ExportMetricsController@exportCombos')->name('export.combo');
        Route::get('/exportMembership/{start}/{end}', 'Brand\Metrics\ExportMetricsController@exportMembership')->name('export.membership');
        Route::get('/exportbyMonth/{start}/{end}', 'Brand\Metrics\ExportMetricsController@exportMonthly')->name('export.monthly');

    });
});

Route::group([
    'prefix' => 'subscriptions',
    'as'     => 'subscriptions.',
], function () {
    Route::get('/', 'Brand\SubscriptionsController@index')->name('index');
    Route::get('/{subscription}/details', 'Brand\SubscriptionsController@details')->name('details');
    Route::get('/{subscription}/delete', 'Brand\SubscriptionsController@delete')->name('delete');
    Route::post('/{subscription}/delete', 'Brand\SubscriptionsController@subscriptionCancel')->name('delete.post');
    Route::get('/users', 'Brand\SubscriptionsController@users')->name('users');
});

Route::group([
    'prefix' => 'special-text',
    'as'     => 'special-text.',
], function () {
    Route::get('/', 'Brand\SpecialText\SpecialTextController@index')->name('index');

    Route::group([
        'prefix' => 'group',
        'as'     => 'group.',
    ], function () {
        Route::get('/', 'Brand\SpecialText\CatalogGroupsController@index')->name('index');
        Route::get('/create', 'Brand\SpecialText\CatalogGroupsController@new')->name('create');
        Route::post('/create', 'Brand\SpecialText\CatalogGroupsController@saveNew')->name('save.new');
        Route::get('/{group}/edit', 'Brand\SpecialText\CatalogGroupsController@edit')->name('edit');
//        Route::get('/{group}/', 'Brand\SpecialText\CatalogGroupsController@edit')->name('edit');
        Route::post('/{group}/edit', 'Brand\SpecialText\CatalogGroupsController@save')->name('save');
        Route::get('/{group}/fields', 'Brand\SpecialText\CatalogFieldController@index')->name('fields');
    });

    Route::group([
        'prefix' => 'field',
        'as'     => 'field.',
    ], function () {

        Route::get('{field}/edit', 'Brand\SpecialText\CatalogFieldController@edit')->name('edit');
        Route::post('{field}/edit', 'Brand\SpecialText\CatalogFieldController@save')->name('save');
        Route::get('{group}/create', 'Brand\SpecialText\CatalogFieldController@create')->name('create');
        Route::post('{group}/create', 'Brand\SpecialText\CatalogFieldController@saveNew')->name('save.new');
        Route::post('{field}/activate', 'Brand\SpecialText\CatalogFieldController@activate')->name('activate');
    });

    Route::group([
        'prefix' => 'control-panel',
        'as'     => 'control-panel.',
    ], function () {
        Route::get('/', 'Brand\SpecialText\CatalogControlController@index')->name('index');
        Route::post('/{group}/create', 'Brand\SpecialText\CatalogControlController@create')->name('create');
    });
});

Route::group([
    'prefix' => 'products',
    'as'     => 'products.',
], function () {
    Route::get('/', 'Brand\ProductController@index')->name('index');
    Route::get('/list', 'Brand\ProductController@list')->name('list');
    Route::post('/{product}/save', 'Brand\ProductController@save')->name('save');
    Route::post('/new', 'Brand\ProductController@saveNew')->name('save.new');
    Route::post('/{product}/delete', 'Brand\ProductController@delete')->name('delete');

    Route::group([
        'prefix' => 'category',
        'as'     => 'category.',
    ], function () {
        Route::post('/new', 'Brand\ProductController@saveNewCategory')->name('save.new');
        Route::post('/{category}/save/', 'Brand\ProductController@saveCategory')->name('save');
        Route::post('/{category}/delete/', 'Brand\ProductController@deleteCategory')->name('delete');
    });
});
