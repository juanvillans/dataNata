<?php

use App\Http\Controllers\CancellationController;
use App\Http\Controllers\ConfigurationProductController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\HierarchyController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::post('login',[UserController::class,'login']);
Route::get('fail-login',[UserController::class,'failLogin'])->name('login');

Route::group(['prefix' => 'dashboard','namespace' => 'App\Http\Controllers', 'middleware' => ['auth:sanctum','ability:origin,branch']], function() {
    


    Route::middleware('ability:1')->apiResource('organizations', 'OrganizationController');


    Route::middleware('ability:2')->apiResource('users', 'UserController');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('change-password', [UserController::class,'changePassword']);
    

    Route::middleware('ability:3')->apiResource('products', 'ProductController');
    Route::get('config-products', [ConfigurationProductController::class,'index'])->middleware('ability:3');
    Route::post('config-products/{type}', [ConfigurationProductController::class,'store'])->middleware('ability:3');
    Route::put('config-products/{type}/{id}', [ConfigurationProductController::class,'update'])->middleware('ability:3');
    Route::delete('config-products/{type}/{id}', [ConfigurationProductController::class,'destroy'])->middleware('ability:3');
    

    Route::middleware('ability:4')->apiResource('entries', 'EntryController');
    

    Route::middleware('ability:5')->apiResource('outputs', 'OutputController');
    Route::get('outputs/generate-order/{guide}',[OutputController::class,'generateOutputOrder'])->middleware('ability:5');

    

    Route::middleware('ability:6')->apiResource('inventories', 'InventoryController');






    // Route::post('cancellation/{type}',[CancellationController::class,'index']);



});