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

    $(document).on("click", ".delete", function() { 
        if(confirm("@lang('Are you sure?')")) {
            var id= $(this).data('id');
            var url = "{{ route('salaries.details.index', $salary->id) }}";
            var dltUrl = url+"/"+id;
            $.ajax({
                url: dltUrl,
                type: "DELETE",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}'
                },
                success: function(dataResult){
                    if(dataResult.statusCode==200){
                        alert('@lang('Deleted Successfully')');
                        location.reload(true);
                    }
                }
            });
        }
	});
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
                {{ $salary->name }}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('salaries.index') }}" class="btn btn-warning btn-icon-sm">
                        <i class="la la-arrow-left"></i>
                        @lang('Back')
                    </a>
                    <a href="{{ route('salaries.calculate', $salary->id) }}" class="btn btn-primary btn-icon-sm">
                        <i class="la la-refresh"></i>
                        @lang('Calculate')
                    </a>
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
                    <th title="Field #1">@lang('Created at')</th>
                    <th title="Field #2">@lang('Teacher')</th>
                    <th title="Field #2">@lang('Jadwal')</th>
                    <th title="Field #2">@lang('Badal')</th>
                    <th title="Field #2">@lang('Hadir')</th>
                    <th title="Field #2">@lang('Telat')</th>
                    <th title="Field #2">@lang('Absen')</th>
                    <th title="Field #2">@lang('Izin/Sakit')</th>
                    <th title="Field #2">@lang('Nominal')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                    <tr>
                        <td>{{ $detail->created_at->format('d/m/y h:i') }}</td>
                        <td>{{ $detail->teacher->name }}</td>
                        <td>{{ $detail->summary['own'] }}</td>
                        <td>{{ $detail->summary['switch'] }}</td>
                        <td>{{ $detail->summary['present'] }}</td>
                        <td>{{ $detail->summary['late'] }}</td>
                        <td>{{ $detail->summary['absent'] }}</td>
                        <td>{{ $detail->summary['permit'] }}</td>
                        <td>{{ $detail->amount }}</td>
                        <td>
                            <a href="{{ route('salaries.details.edit', [$salary->id,$detail->id]) }}" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            <a href="javascript:;" class="text-danger delete" data-id="{{ $detail->id }}">
                                <i class="la la-trash"></i> @lang('Delete')
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!--end: Datatable -->
    </div>
</div>
@endsection