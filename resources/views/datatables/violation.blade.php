@extends('datatables.datatable')
@section('buttons')
<a href="{{ route('violations.create') }}" class="btn btn-primary btn-icon-sm mt-2">
    <i class="la la-plus"></i>
    @lang('Tambah')
</a>
@endsection
