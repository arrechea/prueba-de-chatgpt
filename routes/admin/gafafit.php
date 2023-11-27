<?php
/**
 * Rutas GafaFit Usuario logueado
 * Name: admin.
 */

Route::group(
    [
        'prefix' => 'users',
        'as'     => 'users.',
    ],
    function () {
        Route::get('/', 'GafaFit\UserController@index')->name('index');
        Route::get('/edit/{user}', 'GafaFit\UserController@edit')->name('edit');
        Route::get('/create', 'GafaFit\UserController@create')->name('create');
        Route::get('/delete/{user}', 'GafaFit\UserController@delete')->name('delete');
        Route::post('/delete/{user}', 'GafaFit\UserController@deletePost')->name('delete.post');

        Route::post('/edit/{user}', 'GafaFit\UserController@saveEdit')->name('save');
        Route::post('/create', 'GafaFit\UserController@saveNew')->name('save.new');
    }
);

Route::group(
    [
        'prefix' => 'company',
        'as'     => 'companyEdit.',
    ],
    function () {
        Route::get('/', 'GafaFit\CompanyController@index')->name('index');
        Route::get('/edit/{companyToEdit}', 'GafaFit\CompanyController@edit')->name('edit');
        Route::get('/create', 'GafaFit\CompanyController@create')->name('create');
        Route::get('/delete/{companyToEdit}', 'GafaFit\CompanyController@delete')->name('delete');
        Route::get('/admins', 'GafaFit\CompanyController@admins')->name('admins');
        Route::post('/create', 'GafaFit\CompanyController@saveNew')->name('save.new');
        Route::post('/edit/{companyToEdit}', 'GafaFit\CompanyController@saveEdit')->name('save');
        Route::post('/delete/{companyToEdit}', 'GafaFit\CompanyController@deletePost')->name('delete.post');

    }
);

Route::group(
    [
        'prefix' => 'credits',
        'as'     => 'credits.',
    ],
    function () {
        Route::get('/', 'GafaFit\CreditsGafaFitController@index')->name('index');
        Route::get('/create', 'GafaFit\CreditsGafaFitController@create')->name('create');
        Route::get('/edit/{gafacredit}', 'GafaFit\CreditsGafaFitController@edit')->name('edit');
        Route::get('/delete/{gafacredit}', 'GafaFit\CreditsGafaFitController@delete')->name('delete');
        Route::post('/create', 'GafaFit\CreditsGafaFitController@saveNew')->name('save.new');
        Route::post('/edit/{gafacredit}', 'GafaFit\CreditsGafaFitController@save')->name('save');
        Route::post('/delete/{gafacredit}', 'GafaFit\CreditsGafaFitController@deletePost')->name('delete.post');
        Route::get('/services/{gafacredit}', 'GafaFit\CreditsGafaFitController@services')->name('services.gafa');
        Route::post('/services/{gafacredit}', 'GafaFit\CreditsGafaFitController@saveServices')->name('services.save');
    }
);

Route::group(
    [
        'prefix' => 'administrator',
        'as'     => 'administrator.',
    ],
    function () {
        Route::get('/', 'GafaFit\AdministratorController@index')->name('index');
        Route::get('/edit/{administrator}', 'GafaFit\AdministratorController@edit')->name('edit');
        Route::get('/create', 'GafaFit\AdministratorController@create')->name('create');
        Route::get('/countries', 'GafaFit\AdministratorController@countries')->name('countries');

        Route::delete('/delete/{administrator}', 'GafaFit\AdministratorController@delete')->name('delete');

        Route::post('/edit/{administrator}', 'GafaFit\AdministratorController@saveEdit')->name('save');
        Route::post('/create', 'GafaFit\AdministratorController@saveNew')->name('save.new');

        Route::get('/delete/{administrator}', 'GafaFit\AdministratorController@delete')->name('delete');
        Route::post('/delete/{administrator}', 'GafaFit\AdministratorController@deletePost')->name('delete.post');


        Route::get('/edit/{administrator}/assignmentRoles', 'GafaFit\AdministratorController@assignmentRoles')->name(
            'assignmentRoles'
        );
        Route::post(
            '/edit/{administrator}/assignmentRoles',
            'GafaFit\AdministratorController@assignmentRolesSave'
        )->name('assignmentRoles.save');
        Route::get('/edit/{administrator}/get-roles', 'GafaFit\AdministratorController@getAdminRoles')->name(
            'assignmentRoles.getRoles'
        );
        Route::get('/get-company-rol/{companyToGet}', 'GafaFit\AdministratorController@getCompanyRoles')->name(
            'assignmentRoles.getCompanyRoles'
        );
        Route::get('/get-brand-rol/{brandToGet}', 'GafaFit\AdministratorController@getBrandRoles')->name(
            'assignmentRoles.getBrandRoles'
        );
        Route::get('/get-location-rol/{locationToGet}', 'GafaFit\AdministratorController@getLocationRoles')->name(
            'assignmentRoles.getLocationRoles'
        );
        Route::get(
            '/get-company-roles-brands/{companyToGet}',
            'GafaFit\AdministratorController@getCompanyRolesAndBrands'
        )->name('assignmentRoles.getCompanyRolesAndBrands');
    }
);

Route::group(
    [
        'prefix' => 'settings',
        'as'     => 'settings.',
    ],
    function () {
        Route::get('/', 'GafaFit\SettingsController@index')->name('index');
        Route::post('/edit', 'GafaFit\SettingsController@saveEdit')->name('save');
    }
);

Route::group(
    [
        'prefix' => 'roles',
        'as'     => 'roles.',
    ],
    function () {
        Route::get('/', 'GafaFit\RolesController@index')->name('index');
        Route::get('/create', 'GafaFit\RolesController@create')->name('create');
        Route::post('/create', 'GafaFit\RolesController@createSave')->name('create.save');

        Route::get('/edit/{role}', 'GafaFit\RolesController@edit')->name('edit');
        Route::post('/edit/{role}', 'GafaFit\RolesController@editSave')->name('edit.save');

        Route::get('/companies', 'GafaFit\RolesController@companies')->name('companies');
        Route::get('/brands-locations', 'GafaFit\RolesController@brandsLocations')->name('brandsLocations');
        Route::get('/brands', 'GafaFit\RolesController@brands')->name('brands');
        Route::get('/locations', 'GafaFit\RolesController@locations')->name('locations');

        Route::get('/copy/{role}', 'GafaFit\RolesController@copy')->name('copy');
        Route::get('/delete/{role}', 'GafaFit\RolesController@delete')->name('delete');
        Route::post('/delete/{role}', 'GafaFit\RolesController@deletePost')->name('delete.post');
    }
);

Route::group(
    [
        'prefix' => 'payment-types',
        'as'     => 'paymentTypes.',
    ],
    function () {
        Route::get('/', 'GafaFit\PaymentTypesController@index')->name('index');
        Route::get('/edit/{payment_type}', 'GafaFit\PaymentTypesController@edit')->name('edit');
        Route::post('/save', 'GafaFit\PaymentTypesController@save')->name('save');
    }
);

Route::group(
    [
        'prefix' => 'system-log',
        'as'     => 'systemLog.',
    ],
    function () {
        Route::get('/', 'GafaFit\SystemLogController@index')->name('index');
        Route::get('/{log}/parameters', 'GafaFit\SystemLogController@parameters')->name('parameters');
        Route::get('/admins', 'GafaFit\SystemLogController@admins')->name('admins');
    }
);
