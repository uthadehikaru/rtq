<?php

namespace App\Livewire;

use App\Models\Period;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewPayment extends Component
{
    use WithFileUploads;

    public $periods;

    public $period_ids = [];

    public $total = 0;

    public $attachment;

    public $members = [];

    public $paid_at = '';

    public $payment_method = 'transfer';

    protected $rules = [
        'period_ids' => 'required',
        'members' => 'required',
        'total' => 'required|numeric|min:120000',
        'attachment' => 'nullable|image',
        'paid_at' => 'required|date',
        'payment_method' => 'required|in:transfer,amplop',
    ];

    public function mount()
    {
        $this->periods = Period::all();
        $this->paid_at = Carbon::now()->toDateString();
    }

    public function updated($property)
    {
        $this->resetValidation();

        $this->validateOnly($property);

        if (in_array($property, ['members', 'period_ids'])) {
            $this->validateMemberPeriod();
        }

        $this->total = $this->calculateTotal();
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
        return 120000 * count($this->members) * count($this->period_ids);
    }

    public function savePayment()
    {
        $this->validate();

        $valid = $this->validateMemberPeriod();
        if (! $valid) {
            return;
        }

        $total = $this->calculateTotal();
        if ($this->total < $total) {
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
            'paid_at' => $this->paid_at,
            'status' => 'paid',
            'payment_method' => $this->payment_method,
        ];

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

        return $this->dispatch('paymentCreated', 'Konfirmasi pembayaran telah kami terima, kami akan cek terlebih dahulu. terima kasih');
    }

    public function render()
    {
        return view('livewire.new-payment');
    }
}
