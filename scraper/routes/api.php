<?php

use App\Http\Controllers\PromocodeController;
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

Route::get('db/bank/{bank}', [PromocodeController::class, "searchDatabaseBank"]);
Route::post('search-full-text', [PromocodeController::class, "searchFullText"]);
// Route::get('bank/{bank}/promocodes/', [PromocodeController::class, "searchDatabaseBank"]);
