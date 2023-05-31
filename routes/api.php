<?php

use App\Http\Controllers\Api\GetMembers;
use App\Http\Controllers\BatchMemberController;
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

Route::name('api.')->group(function () {
    Route::get('/batchmembers', [BatchMemberController::class, 'json'])->name('batchmembers');
    Route::get('/members', GetMembers::class)->name('members');
});
