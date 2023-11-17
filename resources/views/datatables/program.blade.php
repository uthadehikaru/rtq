@extends('datatables.datatable')
@section('buttons')
        <a class="btn btn-success" href="{{ route('programs.create') }}">
            <i class="la la-plus"></i> Buat Baru
        </a>
@endsection