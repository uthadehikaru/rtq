@extends('metronic')
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
});
</script>
@endpush
@section('breadcrumbs')
<a href="{{ route('course') }}" class="kt-subheader__breadcrumbs-link">
@lang("Course") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Batch')
</span>
@endsection
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-users"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{ $total }} @lang('Batches')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="#" class="btn btn-primary btn-icon-sm">
                        <i class="la la-plus"></i>
                        @lang('New')
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
                    <th title="Field #2">@lang('Description')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batches as $batch)
                    <tr>
                        <td>{{ $batch->created_at->format('d/m/y h:i') }}</td>
                        <td>{{ $batch->name }}</td>
                        <td>{{ $batch->description }}</td>
                        <td>
                            <a href="{{ route('course.batch.member', [$batch->course_id, $batch->id]) }}" class="text-primary">
                                <i class="la la-list"></i> @lang('Detail')
                            </a>
                            <a href="javascript:;" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            <a href="javascript:;" class="text-danger">
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