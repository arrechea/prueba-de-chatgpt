<?php

Route::group([
    'middleware' => [
        'admin',
        'auth:admin',
    ],
], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/test', 'TestController@test')->name('test');

    /*****************************************
     * COMPANY Routes
     ****************************************/
    // Make sure www isn't seen as a entityName

    Route::group([
        'domain'     => '{company}.' . parse_url(config('app.url'))['host'],
        'as'         => 'company.',
        'middleware' => [
            'company',
        ],
    ], function () {

        require "admin/company.php";

        /*****************************************
         * END------ COMPANY Routes!!!!!!
         ****************************************/
        /****************************************
         * BRAND Routes
         ***************************************/
        Route::group([
            'as'         => 'brand.',
            'prefix'     => 'brands/{brand}/dashboard/',
            'middleware' => 'brand',
        ], function () {
            require "admin/brand.php";

            /****************************************
             * LOCATIONS Routes
             ***************************************/
            Route::group([
                'as'         => 'locations.',
                'prefix'     => 'locations/{location}/dashboard/',
                'middleware' => 'location',
            ], function () {
                require "admin/locations.php";
            });
            /****************************************
             * END------LOCATIONS Routes!!!!!!
             ***************************************/


        });
        /****************************************
         * END------BRAND Routes!!!!!!
         ***************************************/
    });

    /*****************************************************
     * Routes ADMIN GafaFit LOGED USERS
     ****************************************************/
    Route::group([
        'domain'     => parse_url(config('app.url'))['host'],
        'middleware' => [
            'gafafit',
        ],
    ], function () {
        require "admin/gafafit.php";
    });


    /*****************************************************
     * END------ Routes ADMIN GafaFit LOGED USERS!!!!!!
     ****************************************************/
});
/*****************************************************
 * Routes ADMIN GafaFit WITHOUT LOGIN
 ****************************************************/
/**
 * Autentificacion
 */
Route::group([
    'domain' => '{company}.' . parse_url(config('app.url'))['host'],
    'as'     => 'companyLogin.',
], function () {
    Route::get('/', 'AdminAuth\LoginCompanyController@showLoginForm')->name('init');
    Route::post('/login', 'AdminAuth\LoginCompanyController@login')->name('login');
    Route::post('/logout', 'AdminAuth\LoginCompanyController@logout')->name('logout');
    Route::post('/language-change/{lang}', 'LanguageController@ChangeLanguage')->name('change_language');

    Route::get('/password/reset', 'AdminAuth\ForgotPasswordCompanyController@showLinkRequestForm')->name('password.reset');
    Route::post('/password/email', 'AdminAuth\ForgotPasswordCompanyController@sendResetLinkEmail')->name('password.sendResetLink');

    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordCompanyController@showResetForm')->name('password.resetNow');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordCompanyController@reset')->name('password.tryReset');
});

Route::get('/', 'AdminAuth\LoginController@showLoginForm')->name('init');
Route::post('/login', 'AdminAuth\LoginController@login')->name('login');
Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.sendResetLink');

Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm')->name('password.resetNow');
Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.tryReset');

/*****************************************************
 * END ----- Routes ADMIN GafaFit WITHOUT LOGIN
 ****************************************************/
