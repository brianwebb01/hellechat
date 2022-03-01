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
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user', [App\Http\Controllers\Api\UserController::class, 'show'])
        ->name('user');

    Route::apiResource('service-accounts', App\Http\Controllers\Api\ServiceAccountController::class);

    Route::apiResource('threads', App\Http\Controllers\Api\ThreadController::class)->only('index');

    Route::get('threads/{phoneNumber}', [App\Http\Controllers\Api\ThreadController::class, 'show'])
        ->name('threads.show')
        ->where('phoneNumber', '\+?[1-9]\d{1,14}');

    Route::delete('threads/{phoneNumber}', [App\Http\Controllers\Api\ThreadController::class, 'destroy'])
        ->name('threads.destroy')
        ->where('phoneNumber', '\+?[1-9]\d{1,14}');

    Route::apiResource('numbers', App\Http\Controllers\Api\NumberController::class);

    Route::post('contacts/search', [App\Http\Controllers\Api\ContactSearchController::class, 'search'])
        ->name('contacts.search');

    Route::apiResource('contacts', App\Http\Controllers\Api\ContactController::class);

    Route::apiResource('contacts-import', App\Http\Controllers\Api\ContactImportController::class)->only('store');

    Route::apiResource('voicemails', App\Http\Controllers\Api\VoicemailController::class)->except('store', 'update');

    Route::apiResource('messages', App\Http\Controllers\Api\MessageController::class)->only('store', 'update');
});
