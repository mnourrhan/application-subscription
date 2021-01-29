<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1',
    ], function () {
    Route::group(['middleware' => 'api'], function () {
        Route::post('register', 'API\V1\RegisterController@register')->name('app.register');
        Route::get('verify/google/purchase', 'API\V1\MockingGooglePurchaseRequestController')
            ->middleware('google.purchase.valid.headers')->name('verify.google.app');
        Route::get('verify/apple/purchase', 'API\V1\MockingApplePurchaseRequestController')
            ->middleware('apple.purchase.valid.headers')->name('verify.apple.app');
        Route::group(['middleware' => 'auth:api'], function() {
            Route::post('/purchase', 'API\V1\PurchaseController')->name('app.purchase');
        });
    });
});
