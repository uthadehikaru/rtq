<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Component;

class PaymentCheck extends Component
{
    public $payments = null;

    public function mount()
    {
        $this->payments = Payment::where('status','new')->get();
    }

    public function confirm($id)
    {
        $payment = Payment::find($id);
        $payment->status = "paid";
        $payment->paid_at = Carbon::now();
        $payment->save();
        foreach($this->payments as $key=>$pay){
            if($pay->id==$id){
                $this->payments->forget($key);
            }
        }
    }

    public function render()
    {
        return view('livewire.payment-check')
        ->extends('layouts.app');
    }
}
