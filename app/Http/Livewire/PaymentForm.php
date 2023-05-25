<?php

namespace App\Http\Livewire;

use App\Models\PaymentDetail;
use Livewire\Component;

class PaymentForm extends Component
{
    public $payment;
    public $periods;
    public $period_id;
    public $member_id;

    public $rules = [
        'payment.amount' => 'required|numeric',
        'payment.payment_method' => 'required|in:transfer,amplop',
        'payment.paid_at' => 'nullable|date',
        'payment.details.*.period_id' => 'required',
    ];

    protected $appends = ['payment.amount'];

    public function mount($payment, $periods)
    {
        $this->payment = $payment;
        $this->periods = $periods;
    }

    public function save()
    {
        $this->payment->save();
        $this->payment->details->each->save();
        return $this->redirect(route('payments.index'));
    }

    public function deleteLine($id)
    {
        PaymentDetail::findOrFail($id)->delete();
        $this->payment->refresh();
        $this->emit('line-deleted');
    }
    
    public function add()
    {
        $this->resetErrorBag();

        if(!$this->period_id)
            return $this->addError('period_id','Periode wajib dipilih');
            
        if(!$this->member_id)
            return $this->addError('member_id','Peserta wajib dipilih');

        PaymentDetail::create([
            'payment_id' => $this->payment->id,
            'period_id' => $this->period_id,
            'member_id' => $this->member_id,
        ]);
        $this->emit('line-added');
    }

    public function render()
    {
        return view('livewire.payment-form');
    }
}
