@extends('datatables.datatable')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Notifikasi
</span>
@endsection
@section('buttons')
    <a href="{{ route('admin.notifications.clean') }}" class="btn btn-danger btn-icon-sm" onclick="return confirm('Apakah anda yakin?')">
        <i class="la la-check"></i>
        Hapus Notifikasi
    </a>
@endsection