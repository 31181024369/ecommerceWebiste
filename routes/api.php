<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::match(['get','post'],'/admin-login',[App\Http\Controllers\Admin\AdminController::class,'login'])->name('admin-login');
Route::match(['get','post'],'/collaborator-login',[App\Http\Controllers\Admin\CollaboratorController::class,'login'])->name('collaborator-login');
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {

   
    Route::resource('information',App\Http\Controllers\Admin\AdminController::class);
 
    Route::resource('department',App\Http\Controllers\Admin\DepartmentController::class);
    Route::get('/admin-information',[App\Http\Controllers\Admin\AdminController::class,'information']);
    Route::post('/admin-logout',[App\Http\Controllers\Admin\AdminController::class,'logout']);
});
Route::resource('collaborators',App\Http\Controllers\Admin\CollaboratorController::class);
Route::get('/collaborator-information',[App\Http\Controllers\Admin\AdminController::class,'information']);
