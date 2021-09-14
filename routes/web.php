<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::group(['middleware' => ['TwilioRequestValidator', 'RequiresUserHashId']], function () {

    Route::post('webhooks/twilio/messaging/inbound/{userHashId}', [App\Http\Controllers\Services\Twilio\MessagingController::class, 'store'])
        ->name('webhooks.twilio.messaging');

    Route::post('webhooks/twilio/messaging/status/{userHashId}', [App\Http\Controllers\Services\Twilio\MessagingController::class, 'update'])
        ->name('webhooks.twilio.messaging.status');

    Route::post('webhooks/twilio/voicemail/connect/{numberHashId}/{userHashId}', [App\Http\Controllers\Services\Twilio\VoicemailController::class, 'connect'])
        ->name('webhooks.twilio.voice');

    Route::post('webhooks/twilio/voicemail/greeting/{userHashId}', [App\Http\Controllers\Services\Twilio\VoicemailController::class, 'greeting'])
        ->name('webhooks.twilio.voice.greeting');

    Route::post('webhooks/twilio/voicemail/store/{userHashId}', [App\Http\Controllers\Services\Twilio\VoicemailController::class, 'store'])
        ->name('webhooks.twilio.voice.store');
});
