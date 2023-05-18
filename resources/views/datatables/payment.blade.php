@extends('datatables.datatable')
@section('buttons')
        <a class="btn btn-success" href="#" data-toggle="modal" data-target="#paymentModal">
            <i class="la la-plus"></i> Buat Baru
        </a>
        <div class="btn-group" role="group">
            <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="action">
                <a class="dropdown-item" href="{{ route('payments.summary') }}">
                    <i class="la la-file"></i> Rekapitulasi
                </a>
                <a class="dropdown-item" href="{{ route('periods.index') }}">
                    <i class="la la-list"></i> Periode
                </a>
                <a class="dropdown-item" href="{{ route('payments.export') }}">
                    <i class="la la-share"></i> Export to Excel
                </a>
                <a class="dropdown-item" href="{{ route('periods.export') }}">
                    <i class="la la-download"></i> Export per Period
                </a>
            </div>
        </div>
@endsection
@push('scripts')
<livewire:new-payment />
@endpush