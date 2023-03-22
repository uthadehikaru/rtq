<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PaymentDetail;
use App\Models\Violation;
use App\Repositories\BatchRepository;
use App\Repositories\CourseRepository;
use App\Repositories\MemberRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\PresentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            $data = array_merge($data, $this->teacher());
            $view = 'dashboard-teacher';
        } else {
            $member = Member::where('user_id', Auth::id())->first();
            $data['payments'] = PaymentDetail::where('member_id', $member->id)
            ->with(['payment', 'period'])
            ->latest()
            ->limit(5)
            ->get();
            $data['member'] = $member;
            if (! Storage::disk('public')->exists('idcards/'.$member->member_no.'.jpg')) {
                Artisan::call('member:card', ['--no' => $member->member_no]);
            }
            $data['violations'] = Violation::where('user_id', Auth::id())->latest()->limit(5)->get();
            $view = 'dashboard-member';
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
        $data['types'] = ['success' => 'Tahsin Anak', 'danger' => 'Tahsin Dewasa', 'primary' => 'Tahsin Balita'];
        $data['courses'] = (new CourseRepository)->membersPerType($data['types']);
        //dd($data);

        return $data;
    }

    public function teacher()
    {
        $teacher = Auth::user()->teacher;
        $data['batches'] = (new BatchRepository)->teacherBatches(Auth::id());
        $data['schedules'] = (new ScheduleRepository)->currentMonth($teacher->user_id);

        return $data;
    }
}
