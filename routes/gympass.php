<?php

use Illuminate\Http\Request;

Route::group([
    'prefix' => 'webhooks',
    'as'     => 'webhooks.',
    'middleware' => [
        'gympass_webhooks',
    ],
], function () {
    Route::post('requested', 'GympassWebhookController@requested')->name('requested');
});

