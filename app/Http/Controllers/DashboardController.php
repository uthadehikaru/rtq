<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PaymentDetail;
use App\Models\Registration;
use App\Models\Setting;
use App\Models\Violation;
use App\Repositories\BatchRepository;
use App\Repositories\CourseRepository;
use App\Repositories\MemberRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\PresentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\TransactionRepository;
use Carbon\CarbonImmutable;
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
        $data['unconfirmed_payments'] = $paymentRepository->countUnconfirmed();
        $data['active_batches'] = $batchRepository->count(true);
        $data['members'] = $memberRepository->countActiveMembers();

        $data['biodata_count'] = Setting::where('group', 'biodata')->count();
        $data['biodata_verified'] = Setting::where('group', 'biodata')->where('payload->verified', true)->count();
        $data['waitinglist'] = Registration::whereDoesntHave('user')->count();
        $data['violation_count'] = Violation::whereNull('paid_at')->count();
        $data['cash'] = (new TransactionRepository)->getBalance();

        $data['periods'] = (new PeriodRepository)->PaymentPerPeriod();
        $data['types'] = ['success' => 'Tahsin Anak', 'danger' => 'Tahsin Dewasa', 'primary' => 'Tahsin Balita'];
        $data['courses'] = (new CourseRepository)->membersPerType($data['types']);
        $data['schedules'] = (new ScheduleRepository)->getLatest(10);

        //dd($data);

        return $data;
    }

    public function teacher()
    {
        $teacher = Auth::user()->teacher;
        $start_date = CarbonImmutable::now()->startOfMonth();
        $end_date = CarbonImmutable::now()->endOfMonth();
        $data['presents'] = (new PresentRepository)->teacherPresents($teacher->user_id, $start_date, $end_date);
        $data['schedules'] = (new ScheduleRepository)->currentMonth($teacher->user_id);
        $data['violations'] = Violation::where('user_id', Auth::id())->latest()->whereNull('paid_at')->get();

        return $data;
    }
}
