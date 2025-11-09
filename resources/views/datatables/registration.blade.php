@extends('datatables.datatable')
@section('buttons')
        <a href="{{ route('registrations.index') }}" class="btn btn-warning btn-icon-sm">
            <i class="la la-arrow-left"></i>
            @lang('Back')
        </a>
        <div class="btn-group" role="group">
            <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="action">
                @if ($action != 'all')
                <a href="{{ route('registrations.index', ['action' => 'all']) }}" class="dropdown-item text-primary">
                    <i class="la la-list"></i>
                    @lang('Semua')
                </a>
                @else
                <a href="{{ route('registrations.index') }}" class="dropdown-item text-primary">
                    <i class="la la-user"></i>
                    @lang('Belum Terdaftar')
                </a>
                @endif
            </div>
        </div>
@endsection