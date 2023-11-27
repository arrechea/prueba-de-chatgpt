<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/09/2018
 * Time: 01:13 PM
 */
Route::post('/token', 'AccessTokenController@issueToken')->name('token');
//Route::get('/prueba','TestController@test');
