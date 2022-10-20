<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdmissionProcessController;

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

Route::group(['prefix' => 'student-informations', 'namespace' => 'Api\Admissions'], function() {
    Route::get('types', [AdmissionProcessController::class, 'getStudentTypes']);
    Route::get('programs', [AdmissionProcessController::class, 'getDesiredPrograms']);
    Route::get('{status}/status', [AdmissionProcessController::class, 'getInformations'])->middleware('auth:api');
    Route::get('{status}/download', [AdmissionProcessController::class, 'download']);
    Route::get('{slug}', [AdmissionProcessController::class, 'viewInformation']);
    Route::post('/', [AdmissionProcessController::class, 'storeInformation']);
    Route::post('upload', [AdmissionProcessController::class, 'uploadRequirements']);
    Route::delete('files/{id}', [AdmissionProcessController::class, 'deleteFile']);
    Route::post('requirements', [AdmissionProcessController::class, 'saveRequirements']);
    Route::post('{id}', [AdmissionProcessController::class, 'updateInformation'])->middleware('auth:api');
    Route::post('{id}/update-status', [AdmissionProcessController::class, 'updateInformationStatus'])->middleware('auth:api');
    Route::post('{id}/update-remarks', [AdmissionProcessController::class, 'updateInformationRemarks'])->middleware('auth:api');
    Route::post('{id}/upload-attachments', [AdmissionProcessController::class, 'uploadAttachments'])->middleware('auth:api');
    Route::post('{id}/send-acceptance-mail', [AdmissionProcessController::class, 'sendAcceptanceMail'])->middleware('auth:api');
    Route::delete('attachments/{id}', [AdmissionProcessController::class, 'deleteAttachment'])->middleware('auth:api');
});

Route::get('student-informations-admissions/{slug}', [AdmissionProcessController::class,  'viewInformationForAdmission'])->middleware('auth:api');
