<?php

namespace App\Http\Controllers;

use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\MemberRepositoryInterface;
use App\Interfaces\PaymentRepositoryInterface;
use App\Interfaces\ScheduleRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use App\Interfaces\PresentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(
        PaymentRepositoryInterface $paymentRepository,
        BatchRepositoryInterface $batchRepository,
        MemberRepositoryInterface $memberRepository,
        TeacherRepositoryInterface $teacherRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        PresentRepositoryInterface $presentRepository)
    {
        $data['title'] = __('Dashboard');

        $user = Auth::user();
        $view = null;
        if ($user->hasRole('administrator')) {
            $data = array_merge($data, $this->admin($paymentRepository, $batchRepository, $memberRepository));
            $view = 'dashboard-admin';
        } elseif ($user->hasRole('teacher')) {
            $data = array_merge($data, $this->teacher($batchRepository, 
            $teacherRepository, 
            $scheduleRepository,
            $presentRepository));
            $view = 'dashboard-teacher';
        }

        if ($view) {
            return view($view, $data);
        }

        return to_route('home');
    }

    public function admin(PaymentRepositoryInterface $paymentRepository,
    BatchRepositoryInterface $batchRepository,
    MemberRepositoryInterface $memberRepository)
    {
        $data['payments'] = $paymentRepository->count();
        $data['batches'] = $batchRepository->count();
        $data['members'] = $memberRepository->count();

        return $data;
    }

    public function teacher(
        BatchRepositoryInterface $batchRepository,
        TeacherRepositoryInterface $teacherRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        PresentRepositoryInterface $presentRepository)
    {
        $teacher = Auth::user()->teacher;
        $data['batches'] = $batchRepository->all();
        $data['schedules'] = $scheduleRepository->getByTeacher($teacher->user_id);
        $data['presents'] = $presentRepository->getByTeacher($teacher->user_id)->groupBy('status');

        return $data;
    }
}
