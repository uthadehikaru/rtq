@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('transactions.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Uang Kas") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $transaction?'Ubah':'Tambah' }}
</span>
@endsection
@section('content')
<div class="row">
    <div class="col">

        <x-validation/>

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Uang Kas
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('transactions.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($transaction)
            <form class="kt-form" method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('transactions.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Tanggal')</label>
                            <input type="date" name="transaction_date" class="form-control" placeholder="Tanggal Transaksi"
                            value="{{ old('transaction_date', $transaction?$transaction->transaction_date->format('Y-m-d'):date('Y-m-d')) }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Deskripsi')</label>
                            <input type="text" name="description" class="form-control" placeholder="Deskripsi Transaksi"
                            value="{{ old('description', $transaction?$transaction->description:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Jenis Transaksi')</label>
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="type" value="debit" @checked($transaction && $transaction->debit>0)>
                                    <span></span>
                                    Masuk
                                </label>
                                <label class="radio">
                                    <input type="radio" name="type" value="credit" @checked($transaction && $transaction->credit>0)>
                                    <span></span>
                                    Keluar
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nominal')</label>
                            <input type="number" name="nominal" class="form-control" placeholder=""
                            value="{{ old('nominal', $transaction?$transaction->nominal():0) }}"
                            required>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
                    </div>
                </div>
            </form>

            <!--end::Form-->
        </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection