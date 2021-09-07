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

Route::prefix('webhooks')->group(function(){
    Route::post('message-ingestion/{token}', [App\Http\Controllers\Api\MessageIngestionController::class, 'store'])
        ->name('message-ingestion');
});

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/user', function(Request $request){
        //return $request-> user();
        return $request->bearerToken();
    });

    Route::apiResource('service-account', App\Http\Controllers\Api\ServiceAccountController::class);

    Route::apiResource('thread', App\Http\Controllers\Api\ThreadController::class)->only('index');

    Route::delete('thread/{phoneNumber}', [App\Http\Controllers\Api\ThreadController::class, 'destroy'])
        ->name('thread.destroy')
        ->where('phoneNumber', '\+[1-9]\d{1,14}');

    Route::apiResource('number', App\Http\Controllers\Api\NumberController::class);

    Route::apiResource('contact', App\Http\Controllers\Api\ContactController::class);

    Route::apiResource('contact-import', App\Http\Controllers\Api\ContactImportController::class)->only('store');

    Route::apiResource('voicemail', App\Http\Controllers\Api\VoicemailController::class)->except('store', 'update');

    Route::apiResource('voicemail-ingestion', App\Http\Controllers\Api\VoicemailIngestionController::class)->only('store');

});