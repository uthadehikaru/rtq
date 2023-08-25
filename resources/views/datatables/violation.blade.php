@extends('datatables.datatable')
@section('buttons')
<a href="{{ route('violations.create') }}" class="btn btn-primary btn-sm btn-icon-sm mt-2">
    <i class="la la-plus"></i>
    @lang('Tambah')
</a>
<div class="btn-group" role="group">
    <button id="action" type="button" class="btn btn-success btn-sm dropdown-toggle mt-2"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Filter
    </button>
    <div class="dropdown-menu" aria-labelledby="action">
        <a href="{{ route('violations.index', ['status'=>'unpaid']) }}" class="dropdown-item">
            Belum selesai
        </a>
        <a href="{{ route('violations.index', ['status'=>'paid']) }}" class="dropdown-item">
            Selesai
        </a>
        <a href="{{ route('violations.index', ['type'=>'member']) }}" class="dropdown-item">
            Anggota
        </a>
        <a href="{{ route('violations.index', ['type'=>'teacher']) }}" class="dropdown-item">
            Pengajar
        </a>
    </div>
</div>
@endsection
