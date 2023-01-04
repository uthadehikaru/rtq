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
        if(confirm("@lang('Apakah anda yakin ingin menghapus?')")) {
            var id= $(this).data('id');
            var url = "{{ route('violations.index') }}";
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
                        alert('@lang('Berhasil dihapus')');
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
    @lang('Pelanggaran')
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
                {{ $violations->count() }} @lang('Pelanggaran')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('violations.create') }}" class="btn btn-primary btn-icon-sm">
                        <i class="la la-plus"></i>
                        @lang('Tambah Data')
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
                    <th title="Field #1">@lang('Tanggal')</th>
                    <th title="Field #2">@lang('Nama')</th>
                    <th title="Field #3">@lang('Pelanggaran')</th>
                    <th title="Field #4">@lang('Iqob')</th>
                    <th title="Field #5">@lang('Diselesaikan')</th>
                    <th title="Action">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($violations as $violation)
                    <tr>
                        <td>{{ $violation->violated_date->format('d M Y') }}</td>
                        <td>{{ $violation->user->name }}</td>
                        <td>{{ $violation->description }}</td>
                        <td>{{ $violation->amount }}</td>
                        <td>{{ $violation->paid_at?->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('violations.edit', $violation->id) }}" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            <a href="javascript:;" class="text-danger delete" data-id="{{ $violation->id }}">
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