<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome')->name('welcome');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');


//Test
Route::match(['get', 'post'], 'test', 'TestController@test')->name('test');

/****************************************
 * I18N Routes
 ***************************************/

Route::post('/language-change/{lang}', 'LanguageController@ChangeLanguage')->name('change_language');

Route::get('/js/lang.js', 'LanguageController@jsFile')->name('assets.lang');

/****************************************
 * END------I18N Routes!!!!!!
 ***************************************/

Route::get('/image-size', 'ImageController@size')->name('image.size');

//RoutesForApps
Route::group([
    'prefix' => 'cosmics/{brand}/{location}/{user}',
    'as'     => 'cosmics.',
], function () {
    Route::get('init', 'AppController@autologin')->name('init');
    Route::get('get-payment-token/{paymentType}', 'AppController@getPaymentToken')->name('get-payment-token');
    \App\Librerias\Reservation\LibReservationForm::routes('AppController');
});

Route::get('/cosmics/recovery-password/{companyOfUser}/', 'AppController@recoveryPassword')->name('cosmics.recovery-password');


/****************************************
 * WEBHOOKS Routes
 ***************************************/
Route::any('/webhook/{extern?}', 'WebhookController@process')->name('webhook');
