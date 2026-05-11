@extends('datatables.datatable')
@section('search')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Pencarian
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <form class="row align-items-end">
            <div class="col-12 col-md-8 mt-2">
                <label class="form-label">Tanggal Dibuat</label>
                <div class="row">
                    <div class="col-6">
                        <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                    </div>
                    <div class="col-6">
                        <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Reset</a>
                <a href="{{ route('payments.export', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-success">
                    <i class="la la-share"></i> Export to Excel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
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
                <a class="dropdown-item" href="{{ route('payments.check') }}">
                    <i class="la la-list"></i> Check
                </a>
            </div>
        </div>
@endsection
@push('scripts')
<livewire:new-payment />
@endpush