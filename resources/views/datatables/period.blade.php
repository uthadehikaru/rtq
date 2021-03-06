@extends('layouts.app')
@push('scripts')
		<script type="text/javascript">
            var KTDatatable = function() {
	// Private functions

	// table initializer
	var table = function() {

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
		// Public functions
		init: function() {
			// init dmeo
			table();
		},
	};
}();

jQuery(document).ready(function() {
	KTDatatable.init();

    $(document).on("click", ".delete", function() { 
        if(confirm("@lang('Are you sure?')")) {
            var id= $(this).data('id');
            var url = "{{ route('periods.index') }}";
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
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Periods')
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
                {{ $total }} @lang('Periods')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('payments.index') }}" class="btn btn-warning btn-icon-sm">
                        <i class="la la-arrow-left"></i>
                        @lang('Back')
                    </a>
                    <a href="{{ route('periods.create') }}" class="btn btn-primary btn-icon-sm">
                        <i class="la la-plus"></i>
                        @lang('New Period')
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
                    <th title="Field #2">@lang('Name')</th>
                    <th title="Field #4">@lang('Start Date')</th>
                    <th title="Field #3">@lang('End Date')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($periods as $period)
                    <tr>
                        <td>{{ $period->created_at->format('d/m/y h:i') }}</td>
                        <td>{{ $period->name }}</td>
                        <td>{{ $period->start_date->format('d M Y') }}</td>
                        <td>{{ $period->end_date->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('periods.edit', $period->id) }}" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            <a href="javascript:;" class="text-danger delete" data-id="{{ $period->id }}">
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