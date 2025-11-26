@extends('layouts.app')
@push('scripts')
<script type="text/javascript">

var KTDatatableJsonRemoteDemo = function () {
	// Private functions

	// basic demo
	var demo = function () {

        var datatable = $('.kt-datatable').KTDatatable({
            data: {
                saveState: {cookie: false},
            },
            search: {
                input: $('#generalSearch'),
            },
            columns: [
                { field: 'created_at', class: 'text-left' },
                { field: 'user_name', class: 'text-left' },
                { field: 'own', class: 'text-right' },
                { field: 'switch', class: 'text-right' },
                { field: 'present', class: 'text-right' },
                { field: 'late', class: 'text-right' },
                { field: 'absent', class: 'text-right' },
                { field: 'permit', class: 'text-right' },
                { field: 'amount', class: 'text-right' },
                { field: 'action', class: 'text-right' }
            ]
        });

    };

	return {
		// public functions
		init: function () {
			demo();
		}
	};
}();

jQuery(document).ready(function () {    
	KTDatatableJsonRemoteDemo.init();
});
</script>
@endpush
@section('breadcrumbs')
<a href="{{ route('salaries.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Salaries")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Details')
</span>
@endsection
@section('content')
@if(session()->has('message'))
<x-alert type="success">{{ session()->get('message') }}</x-alert>
@endif
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-users"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{ $salary->name }}. Total: Rp. {{ number_format($total, 0, ',', '.') }}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('salaries.index') }}" class="btn btn-warning btn-icon-sm">
                        <i class="la la-arrow-left"></i>
                        @lang('Back')
                    </a>
                    @if(!$salary->approved_at)
                    <a href="{{ route('salaries.calculate', $salary->id) }}" class="btn btn-primary btn-icon-sm" onclick="return confirm('Anda yakin ingin menghitung ulang?')">
                        <i class="la la-refresh"></i>
                        @lang('Calculate')
                    </a>
                    <a href="{{ route('salaries.approve', $salary->id) }}" class="btn btn-info btn-icon-sm"
                    onclick="return confirm('Dengan menyetujui, data ini akan dikunci dan pengajar mendapatkan laporan masing2. Lanjutkan?')">
                        <i class="la la-check"></i>
                        @lang('Setujui')
                    </a>
                    @else
                    <a href="{{ route('salaries.cancel', $salary->id) }}" class="btn btn-danger btn-icon-sm"
                    onclick="return confirm('batalkan?')">
                        <i class="la la-times"></i>
                        @lang('Batal')
                    </a>
                    @endif
                    <a href="{{ route('salaries.report', $salary->id) }}" class="btn btn-success btn-icon-sm" target="_BLANK">
                        <i class="la la-file"></i>
                        @lang('Report')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Search Form -->
        <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
            <div class="row align-items-center">
                <div class="col-xl-8 order-2 order-xl-1">
                    <div class="row align-items-center">
                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="@lang('Search')..." id="generalSearch">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--end: Search Form -->
    </div>
    <div class="kt-portlet__body kt-portlet__body--fit">

        <!--begin: Datatable -->
        <table class="kt-datatable" id="html_table" width="100%">
            <thead>
                <tr>
                    <th title="created_at">@lang('Created at')</th>
                    <th title="user_name">@lang('Teacher')</th>
                    <th title="own">@lang('Jadwal')</th>
                    <th title="switch">@lang('Badal')</th>
                    <th title="present">@lang('Hadir')</th>
                    <th title="late">@lang('Telat')</th>
                    <th title="absent">@lang('Alfa')</th>
                    <th title="permit">@lang('Izin/Sakit')</th>
                    <th title="amount">@lang('Nominal')</th>
                    <th title="action">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                    <tr>
                        <td>{{ $detail->created_at->format('d/m/y h:i') }}</td>
                        <td>{{ $detail->user->name }}</td>
                        <td>{{ $detail->summary['own'] }}</td>
                        <td>{{ $detail->summary['switch'] }}</td>
                        <td>{{ $detail->summary['present'] }} ({{ round($detail->summary['present'] / $detail->summary['own'] * 100, 0) }}%)</td>
                        <td>{{ $detail->summary['late'] }}</td>
                        <td>{{ $detail->summary['absent'] }}</td>
                        <td>{{ $detail->summary['permit'] }}</td>
                        <td class="text-right">{{ number_format($detail->amount, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('salaries.report', [$salary->id,$detail->id]) }}" class="text-info" target="_blank">
                                <i class="la la-file"></i> @lang('Report')
                            </a>
                            @if(!$salary->approved_at)
                            <a href="{{ route('salaries.details.edit', [$salary->id,$detail->id]) }}" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!--end: Datatable -->
    </div>
</div>
@endsection