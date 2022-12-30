<?php

namespace App\Http\Controllers;

use App\Repositories\BatchRepository;
use App\Repositories\CourseRepository;
use App\Repositories\MemberRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\PresentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(
        PaymentRepository $paymentRepository,
        BatchRepository $batchRepository,
        MemberRepository $memberRepository,
        TeacherRepository $teacherRepository,
        ScheduleRepository $scheduleRepository,
        PresentRepository $presentRepository)
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

    public function admin(PaymentRepository $paymentRepository,
    BatchRepository $batchRepository,
    MemberRepository $memberRepository)
    {
        $data['payments'] = $paymentRepository->count();
        $data['batches'] = $batchRepository->count();
        $data['members'] = $memberRepository->countActiveMembers();
        $data['periods'] = (new PeriodRepository)->PaymentPerPeriod();
        $data['types'] = ['success'=>'Tahsin Anak', 'danger'=>'Tahsin Dewasa', 'primary'=>'Tahsin Balita'];
        $data['courses'] = (new CourseRepository)->membersPerType($data['types']);
        //dd($data);

        return $data;
    }

    public function teacher(
        BatchRepository $batchRepository,
        TeacherRepository $teacherRepository,
        ScheduleRepository $scheduleRepository,
        PresentRepository $presentRepository)
    {
        $teacher = Auth::user()->teacher;
        $data['batches'] = $batchRepository->all();
        $data['schedules'] = $scheduleRepository->getByTeacher($teacher->user_id);
        $data['presents'] = $presentRepository->getByTeacher($teacher->user_id)->groupBy('status');

        return $data;
    }
}
