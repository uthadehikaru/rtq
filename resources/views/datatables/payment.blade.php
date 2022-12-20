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
					field: 'Confirmedat',
					title: 'Confirmedat',
                    autohide:true,
                },
                {
					field: 'Status',
					title: 'Status',
					// callback function support for column rendering
					template: function(row) {
						var status = {
							'new': {'title': '@lang('New')', 'class': 'kt-badge--info'},
							'paid': {'title': '@lang('Paid')', 'class': ' kt-badge--success'},
						};
						return '<span class="kt-badge ' + status[row.Status].class + ' kt-badge--inline kt-badge--pill">' + status[row.Status].title + '</span>';
					},
				}
            ]
		});
        
        $('#kt_form_status').on('change', function() {
        datatable.search($(this).val().toLowerCase(), 'Status');
        });
        
        $('#kt_form_status').selectpicker();

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
            var url = "{{ route('payments.index') }}";
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
    @lang('Payments')
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
                {{ $total }} @lang('Payments')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <div class="btn-group" role="group">
                        <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu" aria-labelledby="action" style="">
                            <a class="dropdown-item" href="{{ route('periods.index') }}">
                                <i class="la la-list"></i> @lang('Periods')
                            </a>
                            <a class="dropdown-item" href="{{ route('payment') }}">
                                <i class="la la-plus"></i> @lang('New Payment')
                            </a>
                            <a class="dropdown-item" href="{{ route('payments.export') }}">
                                <i class="la la-share"></i> @lang('Export to Excel')
                            </a>
                        </div>
                    </div>
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
                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                            <div class="kt-form__group kt-form__group--inline">
                                <div class="kt-form__label">
                                    <label>@lang('Status')</label>
                                </div>
                                <div class="kt-form__control">
                                    <select class="form-control bootstrap-select" id="kt_form_status">
                                        <option value="">@lang('All')</option>
                                        <option value="new">@lang('New')</option>
                                        <option value="paid">@lang('Paid')</option>
                                    </select>
                                </div>
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
                    <th title="Field #4">@lang('Member')</th>
                    <th title="Field #2">@lang('Amount')</th>
                    <th title="Field #4">@lang('Status')</th>
                    <th title="Field #3">@lang('Confirmed at')</th>
                    <th title="Field #3">@lang('Attachment')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->created_at->format('d/m/y h:i') }}</td>
                        <td>
                            @foreach($payment->details as $detail)
                            <p>{{ $detail->member->full_name }} periode {{ $detail->period->name }}</p>
                            @endforeach
                        </td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->status }}</td>
                        <td>{{ $payment->paid_at }}</td>
                        <td>
                            @if($payment->attachment)
                            <a href="{{ asset('storage/'.$payment->attachment) }}" target="_blank">@lang('Attachment')</a>
                            @endif
                        </td>
                        <td>
                            @if($payment->status=='new')
                            <a href="{{ route('payments.confirm', $payment->id) }}" onClick="return confirm('Are you sure?')" class="text-info">
                                <i class="la la-check"></i> @lang('Confirm')
                            </a>
                            @endif
                            <a href="javascript:;" class="text-danger delete" data-id="{{ $payment->id }}">
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