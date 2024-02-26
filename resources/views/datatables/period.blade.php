@extends('datatables.datatable')
@section('breadcrumbs')
<a href="{{ route('payments.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Pembayaran") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Periode Pembayaran
</span>
@endsection
@section('buttons')
        <a class="btn btn-success" href="{{ route('periods.create') }}">
            <i class="la la-plus"></i> Buat Baru
        </a>
@endsection