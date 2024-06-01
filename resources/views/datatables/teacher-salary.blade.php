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
});
</script>
@endpush
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Salaries')
</span>
@endsection
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <table class="kt-datatable" id="html_table" width="100%">
            <thead>
                <tr>
                    <th title="Field #1">@lang('Created at')</th>
                    <th title="Field #2">@lang('Period')</th>
                    <th title="Field #2">@lang('Amount')</th>
                    <th title="Field #2">@lang('app.present.status.present')</th>
                    <th title="Field #2">@lang('app.present.status.late')</th>
                    <th title="Field #2">@lang('app.present.status.absent')</th>
                    <th title="Field #2">@lang('app.present.status.permit')</th>
                    <th title="Field #2">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                    <tr>
                        <td>{{ $detail->created_at->format('d M Y') }}</td>
                        <td>{{ $detail->salary->name }}</td>
                        <td><x-money :amount="$detail->amount" /></td>
                        <td>{{ $detail->summary['present'] }}</td>
                        <td>{{ $detail->summary['late'] }}</td>
                        <td>{{ $detail->summary['absent'] }}</td>
                        <td>{{ $detail->summary['permit'] }}</td>
                        <td>
                            <a href="{{ route('teacher.salaries.report', $detail->id) }}" 
                            target="_blank"
                            class="text-warning">
                                <i class="la la-print"></i> @lang('Detail')
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection