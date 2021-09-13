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
Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/user', function(Request $request){
        $user = $request-> user();

        return [
            'id' => $user->id,
            'hash_id' => $user->getHashId(),
            'email' => $user->email,
            'twilio_messaging_endpoint' => route('webhooks.twilio.messaging', ['userHashId' => $user->getHashId()]),
            'twilio_voice_endpoint' => null,
            'created_at' => $user->created_at
        ];
    });

    Route::apiResource('service-account', App\Http\Controllers\Api\ServiceAccountController::class);

    Route::apiResource('thread', App\Http\Controllers\Api\ThreadController::class)->only('index');

    Route::get('thread/{phoneNumber}', [App\Http\Controllers\Api\ThreadController::class, 'show'])
        ->name('thread.show')
        ->where('phoneNumber', '\+[1-9]\d{1,14}');

    Route::delete('thread/{phoneNumber}', [App\Http\Controllers\Api\ThreadController::class, 'destroy'])
        ->name('thread.destroy')
        ->where('phoneNumber', '\+[1-9]\d{1,14}');

    Route::apiResource('number', App\Http\Controllers\Api\NumberController::class);

    Route::apiResource('contact', App\Http\Controllers\Api\ContactController::class);

    Route::apiResource('contact-import', App\Http\Controllers\Api\ContactImportController::class)->only('store');

    Route::apiResource('voicemail', App\Http\Controllers\Api\VoicemailController::class)->except('store', 'update');
});