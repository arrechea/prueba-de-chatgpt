<?php
/**
 * Ruta company
 * Parametros: {company}
 * Name: admin.company.
 */

Route::get('/dashboard', 'Company\CompanyController@dashboard')->name('dashboard');

Route::get('/website', 'Company\CompanyController@website')->name('website');

Route::get('/perfil', 'Company\CompanyController@ucraftProfile')->name('perfil');

Route::group([
    'prefix' => 'companies',
    'as'     => 'companies.',
], function () {
    Route::get('/', 'Company\CompanyController@index')->name('index');

});
Route::group([
    'prefix' => 'users',
    'as'     => 'users.',
], function () {
    Route::get('/', 'Company\UserController@index')->name('index');
    Route::get('/edit/{user}', 'Company\UserController@edit')->name('edit');
    Route::get('/delete/{user}', 'Company\UserController@delete')->name('delete');
    Route::get('/countries/', 'Company\UserController@countries')->name('countries');
    Route::get('/create', 'Company\UserController@create')->name('create');
    Route::post('/create', 'Company\UserController@saveNew')->name('save.new');
    Route::post('/delete/{user}', 'Company\UserController@deletePost')->name('delete.post');
    Route::post('/edit/{user}', 'Company\UserController@saveEdit')->name('save');
});

Route::group(
    [
        'prefix' => 'user-categories',
        'as'     => 'user_categories.',
    ],
    static function () {
        Route::get('/', 'Company\UserCategoryController@index')->name('index');
        Route::get('/edit/{category}', 'Company\UserCategoryController@edit')->name('edit');
        Route::get('/create', 'Company\UserCategoryController@create')->name('create');
        Route::get('/delete/{category}', 'Company\UserCategoryController@delete')->name('delete');
        Route::post('/delete/{category}', 'Company\UserCategoryController@deletePost')->name('delete.post');
        Route::post('/edit/{category}', 'Company\UserCategoryController@saveEdit')->name('save');
        Route::post('/create', 'Company\UserCategoryController@saveNew')->name('save.new');
    }
);

Route::group([
    'prefix' => 'brands',
    'as'     => 'brands.',
], function () {
    Route::get('/', 'Company\BrandController@index')->name('index');

    Route::get('/create', 'Company\BrandController@create')->name('create');
    Route::post('/create', 'Company\BrandController@saveNew')->name('save.new');
    Route::get('/edit/{brand}', 'Company\BrandController@edit')->name('edit');
    Route::get('/saas-edit/{brand}', 'Company\BrandController@saasEdit')->name('saas-edit');
    Route::post('/edit/{brand}', 'Company\BrandController@saveEdit')->name('save');
    Route::post('/delete/{brand}', 'Company\BrandController@deletePost')->name('delete.post');
    Route::get('/delete/{brand}', 'Company\BrandController@delete')->name('delete');

});


Route::group([
    'prefix' => 'credits',
    'as'     => 'credits.',

], function () {
    Route::get('/', 'Company\CompanyCreditsController@index')->name('index');
    Route::get('/create', 'Company\CompanyCreditsController@create')->name('create');
    Route::post('/create', 'Company\CompanyCreditsController@saveNew')->name('save.new');
    Route::get('/edit/{credit}', 'Company\CompanyCreditsController@edit')->name('edit');
    Route::post('/edit/{credit}', 'Company\CompanyCreditsController@saveCredit')->name('save.credit');
    Route::get('/delete/{credit}', 'Company\CompanyCreditsController@delete')->name('delete');
    Route::post('/delete/{credit}', 'Company\CompanyCreditsController@deletePost')->name('delete.post');
    Route::get('/services/{credit}', 'Company\CompanyCreditsController@services')->name('services.company');
    Route::post('/services/{credit}', 'Company\CompanyCreditsController@saveServices')->name('services.save');
});


Route::group([
    'prefix' => 'special-text',
    'as'     => 'special-text.',
], function () {
    Route::get('/', 'Company\SpecialText\SpecialTextController@index')->name('index');

    Route::group([
        'prefix' => 'group',
        'as'     => 'group.',
    ], function () {
        Route::get('/', 'Company\SpecialText\CatalogGroupsController@index')->name('index');
        Route::get('/create', 'Company\SpecialText\CatalogGroupsController@new')->name('create');
        Route::post('/create', 'Company\SpecialText\CatalogGroupsController@saveNew')->name('save.new');
        Route::get('/{group}/edit', 'Company\SpecialText\CatalogGroupsController@edit')->name('edit');
//        Route::get('/{group}/', 'Company\SpecialText\CatalogGroupsController@edit')->name('edit');
        Route::post('/{group}/edit', 'Company\SpecialText\CatalogGroupsController@save')->name('save');
        Route::get('/{group}/fields', 'Company\SpecialText\CatalogFieldController@index')->name('fields');
    });

    Route::group([
        'prefix' => 'field',
        'as'     => 'field.',
    ], function () {

        Route::get('{field}/edit', 'Company\SpecialText\CatalogFieldController@edit')->name('edit');
        Route::post('{field}/edit', 'Company\SpecialText\CatalogFieldController@save')->name('save');
        Route::get('{group}/create', 'Company\SpecialText\CatalogFieldController@create')->name('create');
        Route::post('{group}/create', 'Company\SpecialText\CatalogFieldController@saveNew')->name('save.new');
        Route::post('{field}/activate', 'Company\SpecialText\CatalogFieldController@activate')->name('activate');
    });

    Route::group([
        'prefix' => 'control-panel',
        'as'     => 'control-panel.',
    ], function () {
        Route::get('/', 'Company\SpecialText\CatalogControlController@index')->name('index');
        Route::post('/{group}/create', 'Company\SpecialText\CatalogControlController@create')->name('create');
    });
});


Route::group([
    'prefix' => 'marketing',
    'as'     => 'marketing.',

], function () {
    Route::get('/', 'Company\MarketingController@index')->name('index');
});

Route::group([
    'prefix' => 'services',
    'as'     => 'services.',

], function () {
    Route::get('/', 'Company\ServiceController@index')->name('index');
});
Route::group([
    'prefix' => 'settings',
    'as'     => 'settings.',
], function () {
    Route::get('/', 'Company\SettingsController@index')->name('index');
    Route::get('/brand', 'Company\SettingsController@brand')->name('brand');
    Route::get('/categories', 'Company\SettingsController@categories')->name('categories');
    Route::get('/countries', 'Company\SettingsController@countries')->name('countries');
    Route::get('/delete', 'Company\SettingsController@delete')->name('delete');
    Route::post('/delete', 'Company\SettingsController@deletePost')->name('delete.post');
    Route::post('/company', 'Company\SettingsController@saveCompany')->name('save.company');
    Route::get('/secret/generate', 'Company\SettingsController@generateSecret')->name('secret.generate');

    Route::group([
        'prefix' => 'colors',
        'as'     => 'colors.',
    ], function () {
        Route::get('/create', 'Company\CompanyColorsController@create')->name('create');
        Route::post('/create', 'Company\CompanyColorsController@save')->name('save');
    });
});

Route::group([
    'prefix' => 'administrator',
    'as'     => 'administrator.',
], function () {
    Route::get('/', 'Company\AdministratorController@index')->name('index');
    Route::get('/edit/{administrator}', 'Company\AdministratorController@edit')->name('edit');
    Route::post('/edit/{administrator}', 'Company\AdministratorController@saveEdit')->name('save');

    Route::get('/edit/{administrator}/{profile}/asignmentRoles', 'Company\AdministratorController@assignmentRoles')->name('assignmentRoles');
    Route::post('/edit/{administrator}/{profile}/asignmentRoles', 'Company\AdministratorController@assignmentRolesSave')->name('assignmentRoles.save');
    Route::get('/edit/{administrator}/{profile}/get-roles', 'Company\AdministratorController@getAdminRoles')->name('assignmentRoles.getRoles');
    Route::get('/get-company-rol', 'Company\AdministratorController@getCompanyRoles')->name('assignmentRoles.getCompanyRoles');
    Route::get('/get-brand-rol/{brandToGet}', 'Company\AdministratorController@getBrandRoles')->name('assignmentRoles.getBrandRoles');
    Route::get('/get-location-rol/{locationToGet}', 'Company\AdministratorController@getLocationRoles')->name('assignmentRoles.getLocationRoles');
    Route::get('/get-company-roles-brands', 'Company\AdministratorController@getCompanyRolesAndBrands')->name('assignmentRoles.getCompanyRolesAndBrands');


    Route::get('/create', 'Company\AdministratorController@create')->name('create');
    Route::post('/create', 'Company\AdministratorController@saveNew')->name('save.new');

    Route::get('/delete/{administrator}', 'Company\AdministratorController@delete')->name('delete');
    Route::post('/deletePost/{administrator}', 'Company\AdministratorController@deletePost')->name('delete.post');

    Route::get('/countries', 'Company\AdministratorController@countries')->name('countries');
});

Route::group([
    'prefix' => 'mails',
    'as'     => 'mails.',
], function () {
    Route::get('/create', 'Brand\Mails\MailsController@create')->name('create');

    Route::group([
        'prefix' => 'welcome',
        'as'     => 'welcome.',
    ], function () {
        Route::get('/create/', 'Company\Mails\WelcomeController@create')->name('create');
        Route::post('/create/', 'Company\Mails\WelcomeController@save')->name('save');
//        Route::get('/delete/{welcomeEdit}','Brand\Mails\WelcomeController@delete')->name('delete');
//        Route::post('/delete/{welcomeEdit}','Brand\Mails\WelcomeController@deletePost')->name('delete.post');
    });

    Route::group([
        'prefix' => 'reset_password',
        'as'     => 'reset-password.',
    ], function () {
        Route::get('/create/', 'Company\Mails\ForgetPasswordController@create')->name('create');
        Route::post('/create/', 'Company\Mails\ForgetPasswordController@save')->name('save');
//        Route::get('/delete/{welcomeEdit}','Brand\Mails\WelcomeController@delete')->name('delete');
//        Route::post('/delete/{welcomeEdit}','Brand\Mails\WelcomeController@deletePost')->name('delete.post');
    });

    Route::group([
        'prefix' => 'import_user',
        'as'     => 'import-user.',
    ], function () {
        Route::get('/create/', 'Company\Mails\MailsImportUserController@create')->name('create');
        Route::post('/create/', 'Company\Mails\MailsImportUserController@save')->name('save');
    });
});

Route::group([
    'prefix' => 'roles',
    'as'     => 'roles.',
], function () {
    Route::get('/', 'Company\RolesController@index')->name('index');
    Route::get('/create', 'Company\RolesController@create')->name('create');
    Route::post('/create', 'Company\RolesController@createSave')->name('create.save');

    Route::get('/edit/{role}', 'Company\RolesController@edit')->name('edit');
    Route::post('/edit/{role}', 'Company\RolesController@editSave')->name('edit.save');

    Route::get('/companies', 'Company\RolesController@companies')->name('companies');
    Route::get('/brands-locations', 'Company\RolesController@brandsLocations')->name('brandsLocations');
    Route::get('/brands', 'Company\RolesController@brands')->name('brands');
    Route::get('/locations', 'Company\RolesController@locations')->name('locations');

    Route::get('/copy/{role}', 'Company\RolesController@copy')->name('copy');
    Route::get('/delete/{role}', 'Company\RolesController@delete')->name('delete');
    Route::post('/delete/{role}', 'Company\RolesController@deletePost')->name('delete.post');
});

Route::get('fields/{catalog}/{brand?}', 'CatalogController@fields')->name('fields');
Route::get('values/{catalog}/{model}/{brand?}', 'CatalogController@values')->name('values');

Route::group([
    'prefix' => 'import',
    'as'     => 'import.',
], function () {
    Route::group([
        'prefix' => 'users',
        'as'     => 'users.',
    ], function () {
        Route::get('/', 'Company\ImportUsersController@index')->name('index');
        Route::post('/', 'Company\ImportUsersController@import')->name('import');
    });
});

