<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\LoanDetailsController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\SchedulesController;
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

Route::post('/register',[ApiAuthController::class, 'index']);
Route::post('/login',[ApiAuthController::class, 'login']);
Route::post('/role',[RolesController::class, 'create']);


Route::group(['middleware' => ['auth:api']], function ()  {
    Route::post('/applyloan',[LoanDetailsController::class, 'create']);
    Route::get('/viewloan',[LoanDetailsController::class, 'index']);
    Route::post('/accepted',[LoansController::class, 'Approved']);
    Route::post('/declined',[LoanDetailsController::class, 'declined']);
    Route::get('/loandetails/{id?}',[LoanDetailsController::class, 'show']);
    Route::get('/viewemi/{id?}',[SchedulesController::class, 'show']);
    Route::post('/payemai',[SchedulesController::class, 'update']);
    Route::post('/all',[LoanDetailsController::class, 'listoftheloan']);
    Route::post('/close',[LoanDetailsController::class, 'close']);

});
