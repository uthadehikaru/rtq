<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaymentForm extends Component
{
    public $payment;
    public $periods;

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

    public function render()
    {
        return view('livewire.payment-form');
    }
}
