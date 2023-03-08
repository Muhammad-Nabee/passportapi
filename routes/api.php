<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(UserController::class)->group(function () {
Route::post('/register','register')->name('register');
Route::post('/login','login')->name('login');
Route::middleware('auth:api')->group(function(){
    Route::get('/users','getUsers')->name('getUsers');
    Route::get('/user/{id}','getUser')->name('getUser');
   

});
Route::put('userupdatebyid/{id}','userUpdate')->name('userUpdate');
Route::delete('/userdelete/{id}','deleteUser')->name('deleteUser');
   
});

