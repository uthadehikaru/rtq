@extends('datatables.datatable')
@section('breadcrumbs')
<a href="{{ route('payments.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Pembayaran") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<a href="{{ route('periods.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Periode") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $period->name ?? 'All' }}
</span>
@endsection
@section('buttons')
        <a class="btn btn-success" href="{{ $period ? route('periods.export', $period->id) : route('periods.export') }}">
            <i class="la la-share"></i> Export to Excel
        </a>
@endsection