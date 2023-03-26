@extends('datatables.datatable')
@section('buttons')
<a href="{{ route('violations.create', ['type'=>'member']) }}" class="btn btn-primary btn-icon-sm mt-2">
    <i class="la la-plus"></i>
    @lang('Anggota')
</a>
<a href="{{ route('violations.create', ['type'=>'teacher']) }}" class="btn btn-primary btn-icon-sm mt-2">
    <i class="la la-plus"></i>
    @lang('Pengajar')
</a>
@endsection
