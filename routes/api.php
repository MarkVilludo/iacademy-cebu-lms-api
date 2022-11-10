<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AdmissionProcessController, ModePaymentController};
use App\Http\Controllers\Api\{PaymentGatewayController, PaynamicsWebhookController};
use App\Http\Controllers\Api\{InterviewScheduleController};


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'admissions/student-info'], function() {
        Route::get('types', [AdmissionProcessController::class, 'getStudentTypes']);
        Route::get('programs', [AdmissionProcessController::class, 'getDesiredPrograms']);
        Route::get('{status}/status', [AdmissionProcessController::class, 'getInformations']);
        Route::get('{status}/download', [AdmissionProcessController::class, 'download']);
        Route::get('{slug}', [AdmissionProcessController::class, 'viewInformation']);
        Route::post('/', [AdmissionProcessController::class, 'storeInformation']);
        Route::post('upload', [AdmissionProcessController::class, 'uploadRequirements']);
        Route::delete('files/{id}', [AdmissionProcessController::class, 'deleteFile']);
        Route::post('requirements', [AdmissionProcessController::class, 'saveRequirements']);
        Route::post('{id}', [AdmissionProcessController::class, 'updateInformation']);
        Route::post('{slug}/update-status', [AdmissionProcessController::class, 'updateInformationStatus']);
        Route::post('{id}/update-remarks', [AdmissionProcessController::class, 'updateInformationRemarks']);
        Route::post('{id}/upload-attachments', [AdmissionProcessController::class, 'uploadAttachments']);
        Route::post('{id}/send-acceptance-mail', [AdmissionProcessController::class, 'sendAcceptanceMail']);
    });

    Route::group(['prefix' => 'admissions/applications'], function() {
        Route::get('/', [AdmissionProcessController::class, 'index']);
    });

    Route::group(['prefix' => 'interview-schedules'], function() {
        Route::get('/{date}', [InterviewScheduleController::class, 'index']);
        Route::post('/', [InterviewScheduleController::class, 'store']);
    });
    
    Route::group(['prefix' => 'payments'], function() {
        Route::post('/', [PaymentGatewayController::class, 'pay']);
        Route::get('modes', [ModePaymentController::class, 'index']);
        Route::post('webhook', [PaynamicsWebhookController::class, 'webhook'])->name('webhook');
    });
    Route::post('cancel-payment-transactions', [PaymentGatewayController::class, 'cancelTransaction'])->name('cancel-payment');
    Route::post('payments-webhook', [PaynamicsWebhookController::class, 'webhook'])->name('payments-webhook');
    
});

Route::get('student-informations-admissions/{slug}', [AdmissionProcessController::class,  'viewInformationForAdmission'])->middleware('auth:api');
