<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchMemberController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PaymentController;

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

Route::get('/', [HomeController::class,'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });
    Route::resource('users', UserController::class);
    Route::resource('members', MemberController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('courses.batches', BatchController::class);
    Route::resource('courses.batches.batchmembers', BatchMemberController::class);
    Route::resource('periods', PeriodController::class);
    Route::get('payments/{payment}/confirm', [PaymentController::class,'confirm'])->name('payments.confirm');
    Route::get('payments/export', [PaymentController::class,'export'])->name('payments.export');
    Route::resource('payments', PaymentController::class);
    
    Route::get('/logout', [LoginController::class,'logout'])->name('logout');
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
});
Route::get('/login', [LoginController::class,'index'])->name('login');
Route::post('/login', [LoginController::class,'authenticate'])->name('login.authenticate');

Route::get('/payment', [PaymentController::class,'form'])->name('payment');
Route::post('/payment', [PaymentController::class,'store'])->name('payment.confirm');
