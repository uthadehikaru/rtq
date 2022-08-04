<?php

use App\Http\Controllers\Actions;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchMemberController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PresentController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalaryDetailController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::group(['middleware' => ['role:administrator']], function () {
        Route::resource('users', UserController::class);
        Route::get('members/{id}/change', [MemberController::class, 'change'])->name('members.change');
        Route::post('members/{id}/change', Actions\ChangeBatch::class);
        Route::get('members/{id}/switch', [MemberController::class, 'switch'])->name('members.switch');
        Route::post('members/{id}/switch', Actions\SwitchBatch::class);
        Route::get('members/{id}/leave', Actions\LeaveBatch::class)->name('members.leave');
        Route::resource('members', MemberController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('courses.batches', BatchController::class);
        Route::resource('courses.batches.batchmembers', BatchMemberController::class);
        Route::resource('periods', PeriodController::class);
        Route::get('payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
        Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
        Route::resource('payments', PaymentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::get('schedules/{schedule}/presents/{present}/change/{status}', [PresentController::class, 'change'])->name('schedules.presents.change');
        Route::resource('schedules.presents', PresentController::class);
        Route::get('salaries/{id}/report', Actions\ReportSalary::class)->name('salaries.report');
        Route::get('salaries/{id}/calculate', Actions\CalculateSalary::class)->name('salaries.calculate');
        Route::resource('salaries', SalaryController::class);
        Route::resource('salaries.details', SalaryDetailController::class);
        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
    });
    Route::name('teacher.')->prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::post('schedules/create', Actions\CreateSchedule::class)->name('schedules.create');
        Route::get('schedule/{schedule}', Actions\ScheduleDetail::class)->name('schedules.detail');
        Route::post('schedule/{schedule}', Actions\UpdateSchedule::class)->name('schedules.update');
        Route::get('presents', Teacher\PresentList::class)->name('presents.index');
    });

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/payment', [PaymentController::class, 'form'])->name('payment');
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.confirm');
