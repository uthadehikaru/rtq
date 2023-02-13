@extends('layouts.app')
@section('content')
<x-validation />
<div class="row">
    <div class="col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @lang('Kartu Anggota')
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <img src="{{ asset('storage/idcards/'.$member->member_no.'.jpg') }}" alt="{{ $member->member_no }}" />
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @lang('Data Infaq')
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>Tgl Bayar</th>
                        <th>Periode</th>
                        <th>Status</th>
                    </tr>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('d/M/Y') }}</td>
                            <td>{{ $payment->period->name }}</td>
                            <td>{{ __('app.payment.status.'.$payment->payment->status) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection