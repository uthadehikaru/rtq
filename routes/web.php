<?php

use App\Http\Controllers\Action\CheckUserRole;
use App\Http\Controllers\Actions;
use App\Http\Controllers\Actions\ShowNotification;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\CleanOldNotifications;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProgramsController;
use App\Http\Controllers\Admin\ResetPassword;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchMemberController;
use App\Http\Controllers\BiodataMemberController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Member\IqobController;
use App\Http\Controllers\Member\PaymentController as MemberPaymentController;
use App\Http\Controllers\Member\Pictures;
use App\Http\Controllers\Member\PresentController as MemberPresentController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Payment\Rekapitulasi;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentDetailController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PresentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Salary\ConfigSalary;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalaryDetailController;
use App\Http\Controllers\Schedule;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Teacher\CloseSchedule;
use App\Http\Controllers\Teacher\Schedule as TeacherSchedule;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\FailedJobsController;
use App\Http\Livewire\MemberCards;
use App\Http\Livewire\PaymentCheck;
use App\Http\Livewire\Profile;
use App\Http\Livewire\UpdatePassword;
use App\Http\Livewire\WhatsappConfig;
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
Route::post('/upload', Actions\UploadImage::class)->name('upload');
Route::post('/dropzone', Actions\Dropzone::class)->name('dropzone');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/update-password', UpdatePassword::class)->name('update-password');
    Route::get('/notification/{id}', ShowNotification::class)->name('notification');
    Route::group(['middleware' => ['role:administrator']], function () {
        Route::get('activities', ActivityController::class)->name('activities');
        Route::prefix('educations')->group(function () {
            Route::get('members/pictures', Pictures::class)->name('members.pictures');
            Route::get('members/cards', MemberCards::class)->name('members.card-list');
            Route::get('members/cards/{member_no?}', [MemberController::class, 'cards'])->name('members.cards');
            Route::get('members/{id}/change', [MemberController::class, 'change'])->name('members.change');
            Route::post('members/{id}/change', Actions\ChangeBatch::class);
            Route::get('members/{id}/switch', [MemberController::class, 'switch'])->name('members.switch');
            Route::post('members/{id}/switch', Actions\SwitchBatch::class);
            Route::get('members/{id}/leave', [MemberController::class, 'leave'])->name('members.leave');
            Route::post('members/{id}/leave', Actions\LeaveBatch::class);
            Route::resource('biodata', BiodataMemberController::class);
            Route::resource('members', MemberController::class);
            Route::resource('courses', CourseController::class);
            Route::get('courses/{course_id}/batches/export', Actions\ExportBatch::class)->name('courses.batches.export');
            Route::resource('courses.batches', BatchController::class);
            Route::resource('courses.batches.batchmembers', BatchMemberController::class);
            Route::resource('teachers', TeacherController::class);
            Route::get('schedules/reports', Schedule\Report::class)->name('schedules.report');
            Route::get('schedules/export', Schedule\Export::class)->name('schedules.export');
            Route::get('schedules/{schedule}/presents/{present}/change/{status}', [PresentController::class, 'change'])->name('schedules.presents.change');
            Route::resource('schedules', ScheduleController::class)->except(['show']);
            Route::resource('schedules.presents', PresentController::class);
            Route::resource('registrations', RegistrationController::class);
        });
        Route::prefix('finances')->group(function () {
            Route::get('salaries/config', [ConfigSalary::class, 'index'])->name('salaries.config');
            Route::post('salaries/config', [ConfigSalary::class, 'save']);
            Route::get('salaries/{id}/report/{user_id}', Actions\ReportSalary::class)->name('salaries.report');
            Route::get('salaries/{id}/approve', Actions\ApproveSalary::class)->name('salaries.approve');
            Route::get('salaries/{id}/cancel', Actions\CancelSalary::class)->name('salaries.cancel');
            Route::get('salaries/{id}/calculate', Actions\CalculateSalary::class)->name('salaries.calculate');
            Route::resource('salaries', SalaryController::class);
            Route::resource('salaries.details', SalaryDetailController::class);
            Route::resource('violations', ViolationController::class);
            Route::get('periods/export/{id?}', [PeriodController::class, 'export'])->name('periods.export');
            Route::resource('periods', PeriodController::class);
            Route::get('payments/check', PaymentCheck::class)->name('payments.check');
            Route::get('payments/summary', Rekapitulasi::class)->name('payments.summary');
            Route::get('payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
            Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
            Route::resource('payments', PaymentController::class);
            Route::resource('paymentdetails', PaymentDetailController::class);
            Route::resource('transactions', TransactionController::class);
            Route::resource('programs', ProgramsController::class);
        });
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('users/{id}/reset', ResetPassword::class)->name('users.reset');
            Route::get('users/check-roles', CheckUserRole::class)->name('users.check-roles');
            Route::resource('users', AdminUserController::class);
            Route::get('notifications', NotificationController::class)->name('notifications.index');
            Route::get('notifications/clean', CleanOldNotifications::class)->name('notifications.clean');
            Route::get('/login-as/{user:id}', Actions\LoginAsUser::class)->name('login.as');
            Route::get('whatsapp', WhatsappConfig::class)->name('whatsapp.config');
        });
        Route::prefix('failed-jobs')->name('failed-jobs.')->group(function () {
            Route::get('/', [FailedJobsController::class, 'index'])->name('index');
            Route::get('retry-all', [FailedJobsController::class, 'retryAll'])->name('retry-all');
            Route::get('flush-all', [FailedJobsController::class, 'flushAll'])->name('flush-all');
            Route::get('{id}/retry', [FailedJobsController::class, 'retry'])->name('retry');
            Route::delete('{id}/forget', [FailedJobsController::class, 'forget'])->name('forget');
        });
        Route::resource('settings', SettingController::class);
    });
    Route::name('teacher.')->prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::post('upload', Actions\Upload::class)->name('upload');
        Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/create', TeacherSchedule::class)->name('schedules.create');
        Route::post('schedules/create', Actions\CreateSchedule::class);
        Route::get('schedule/{schedule}', Actions\ScheduleDetail::class)->name('schedules.detail');
        Route::get('schedule/{schedule}/close', CloseSchedule::class)->name('schedules.close');
        Route::post('schedule/{schedule}/close', Actions\CloseSchedule::class);
        Route::post('schedule/{schedule}/presents/add', Actions\AddMemberToSchedule::class)->name('schedules.presents.add');
        Route::get('schedule/{schedule}/presents/remove/{present}', Actions\RemoveMemberFromSchedule::class)->name('schedules.presents.remove');
        Route::post('schedule/{schedule}', Actions\UpdateSchedule::class)->name('schedules.update');
        Route::get('presents', Teacher\PresentList::class)->name('presents.index');
        Route::get('salaries', [Teacher\Salary::class, 'index'])->name('salaries.index');
        Route::get('salaries/{detail_id}', [Teacher\Salary::class, 'report'])->name('salaries.report');
    });

    Route::name('member.')->prefix('member')->middleware('role:member')->group(function () {
        Route::get('presents', MemberPresentController::class)->name('presents.index');
        Route::get('payments', MemberPaymentController::class)->name('payments.index');
        Route::resource('profile', ProfileController::class)->only('update');
    });
    Route::get('member/iqob', function () {
        return redirect()->route('iqob.index');
    });

    Route::get('iqob', IqobController::class)->name('iqob.index');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
Route::get('/login/{username?}', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register/{type}', [RegisterController::class, 'index'])->name('register');
Route::post('/register/{type}', [RegisterController::class, 'submit']);

Route::get('/payment', [PaymentController::class, 'form'])->name('payment');
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.confirm');

Route::get('/member/biodata', [BiodataMemberController::class, 'add'])->name('member.biodata');
Route::post('/member/biodata', [BiodataMemberController::class, 'store']);

if (env('APP_ENV') == 'local') {
    Route::get('/login-as/{user:email}', Actions\LoginAsUser::class)->name('login.as');
}
