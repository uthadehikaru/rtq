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
            columns: [
                {
					field: 'Name',
					title: 'Name',
                    autohide:false,
                }
            ]
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
            var url = "{{ route('teachers.index') }}";
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
    @lang('Teachers')
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
                {{ $total }} @lang('Teachers')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-icon-sm">
                        <i class="la la-plus"></i>
                        @lang('New Teacher')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body kt-portlet__body--fit">

        <!--begin: Datatable -->
        <table class="kt-datatable" id="html_table" width="100%">
            <thead>
                <tr>
                    <th title="Field #1">@lang('Created at')</th>
                    <th title="Field #2">@lang('Name')</th>
                    <th title="Field #2">@lang('Email')</th>
                    <th title="Field #2">@lang('Batches')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->created_at->format('d/m/y h:i') }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->user?->email }}</td>
                        <td>{{ $teacher->batches_count }} @lang('Batches')</td>
                        <td>
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            <a href="javascript:;" class="text-danger delete" data-id="{{ $teacher->id }}">
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