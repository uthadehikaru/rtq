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
            var url = "{{ route('members.index') }}";
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
    @lang('Members')
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
                {{ $total }} @lang('Members')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('members.create') }}" class="btn btn-primary btn-icon-sm">
                        <i class="la la-plus"></i>
                        @lang('New Member')
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
                    <th title="Field #2">@lang('Full Name')</th>
                    <th title="Field #4">@lang('Gender')</th>
                    <th title="Field #4">@lang('School')</th>
                    <th title="Field #4">@lang('Level')</th>
                    <th title="Field #4">@lang('Batch')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->created_at->format('d/m/y h:i') }}</td>
                        <td>{{ $member->full_name }}</td>
                        <td>@lang(Str::title($member->gender))</td>
                        <td>{{ $member->school }} {{ $member->class }}</td>
                        <td>{{ $member->level }}</td>
                        <td>{{ $member->batch()?$member->batch()->name:'Inaktif' }}</td>
                        <td>
                            @if($member->batch())
                            <a href="{{ route('members.change', $member->id) }}" class="text-info">
                                <i class="la la-refresh"></i> @lang('Change :name',['name'=>__('Batch')])
                            </a>
                            <a href="{{ route('members.switch', $member->id) }}" class="text-info">
                                <i class="la la-user"></i> @lang('Switch Member')
                            </a>
                            <a href="{{ route('members.leave', $member->id) }}" class="text-info" onclick="return confirm('Anda yakin?')">
                                <i class="la la-share"></i> @lang('Leave')
                            </a>
                            @endif
                            <a href="{{ route('members.edit', $member->id) }}" class="text-warning">
                                <i class="la la-edit"></i> @lang('Edit')
                            </a>
                            <a href="javascript:;" class="text-danger delete" data-id="{{ $member->id }}">
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