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

});
</script>
@endpush
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Presents')
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
                @lang('Presents')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
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
                    <th title="Field #1">@lang('Jadwal')</th>
                    <th title="Field #4">@lang('Halaqoh')</th>
                    <th title="Field #4">@lang('Status')</th>
                    <th title="Field #4">@lang('Description')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presents as $present)
                    <tr>
                        <td>{{ $present->schedule->scheduled_at->format('d M Y h:i') }}</td>
                        <td>{{ $present->schedule->batch->name }}</td>
                        <td>
                            @lang('app.present.status.'.$present->status) 
                            @if($present->status=='present')
                                {{ $present->attended_at?->format('H:i') }} 
                                {{ $present->leave_at?' - '.$present->leave_at->format('H:i'):'' }}
                            @else
                            {{ $present->description==''?'Tanpa Keterangan':'' }}
                            @endif
                        </td>
                        <td>
                        @if($present->photo)
                        <a href="{{ asset('storage/'.$present->photo) }}" target="_blank">Bukti Foto</a>
                        @endif
                        {{ $present->type=='teacher' && $present->is_badal?'(Badal)':'' }}
                        {{ $present->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!--end: Datatable -->
    </div>
</div>
@endsection