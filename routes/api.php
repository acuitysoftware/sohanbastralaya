<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
	LoginController,
	UserProfileController,
};
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
Route::get('test', function () {
   dd('okk');
});
Route::post("login", [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function() {
    Route::post("get-user-profile", [UserProfileController::class, "user"]);
    Route::post("update-address", [UserProfileController::class, "updateAddress"]);
    Route::post("shift-checking", [LoginController::class, 'shiftChecking']);
    Route::post("shift-in-out-checking", [LoginController::class, 'shiftInOutChecking']);
    Route::post("shift-in-out", [LoginController::class, 'shiftInOut']);

});