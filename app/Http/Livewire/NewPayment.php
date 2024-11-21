<?php

namespace App\Http\Livewire;

use App\Models\Member;
use App\Models\Period;
use App\Repositories\PaymentRepository;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewPayment extends Component
{
    use WithFileUploads;

    public $is_member = false;

    public $periods;

    public $period_ids = [];

    public $total = 0;

    public $attachment;

    public $members = [];

    public $paid_at = '';

    public $description = '';

    public $payment_method = 'transfer';

    protected $rules = [
        'period_ids' => 'required',
        'members' => 'required',
        'total' => 'required|numeric|min:0',
        'is_member' => 'required|boolean',
        'attachment' => 'required_if:is_member,true|nullable|image',
        'description' => 'nullable|max:225',
        'paid_at' => 'required|date',
        'payment_method' => 'required|in:transfer,amplop',
    ];

    public function mount($is_member = false)
    {
        $this->is_member = $is_member;
        $this->periods = Period::orderBy('start_date', 'desc')->get();
        $this->paid_at = Carbon::now()->toDateString();
    }

    public function updated($property)
    {
        $this->resetValidation();

        $this->validateOnly($property);

        if (in_array($property, ['members', 'period_ids'])) {
            $this->validateMemberPeriod();
        }

        if (! $this->description) {
            $this->total = $this->calculateTotal();
        } else {
            $this->total = 0;
        }
    }

    private function validateMemberPeriod()
    {
        foreach ($this->period_ids as $period_id) {
            foreach ($this->members as $member_id) {
                $paymentDetail = (new PaymentRepository())->check($member_id, $period_id);
                if ($paymentDetail) {
                    $this->addError('members', 'Konfirmasi pembayaran sudah pernah dibuat. '.$paymentDetail->member->full_name.' periode '.$paymentDetail->period->name);

                    return false;
                }
            }
        }

        return true;
    }

    private function calculateTotal()
    {
        $memberFee = (new SettingService())->value('course_fee');
        $total = 0;
        foreach ($this->members as $member_id) {
            $member = Member::with('batches')->find($member_id);
            $type = $member->batches->first()->course->type;
            if ($member->is_acceleration) {
                $total += (new SettingService())->value('acceleration_'.Str::snake($type).'_fee', $memberFee);
            } else {
                $total += $memberFee;
            }
        }

        return $total * count($this->period_ids);
    }

    public function savePayment()
    {
        $this->validate();

        $valid = $this->validateMemberPeriod();
        if (! $valid) {
            return;
        }

        $total = $this->calculateTotal();
        if (! $this->description && $this->total < $total) {
            return $this->addError('total', 'Minimal nominal pembayaran '.$total);
        }

        DB::beginTransaction();

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->storePublicly('attachments', 'public');
        }

        $payment = [
            'amount' => $this->total,
            'attachment' => $path,
            'payment_method' => $this->payment_method,
        ];

        if (! $this->is_member) {
            $payment['description'] = $this->description;
            $payment['paid_at'] = $this->paid_at;
            $payment['status'] = 'paid';
        }

        foreach ($this->period_ids as $period_id) {
            foreach ($this->members as $member_id) {
                $payment['details'][] = [
                    'member_id' => $member_id,
                    'period_id' => $period_id,
                ];
            }
        }

        (new PaymentRepository)->createPayment($payment);

        DB::commit();

        if ($this->is_member) {
            return redirect()->route('home')->with('message', 'Pembayaran telah kami terima dan akan kami cek terlebih dahulu. Terima kasih');
        }

        return $this->emit('paymentCreated');
    }

    public function render()
    {
        return view('livewire.new-payment');
    }
}
