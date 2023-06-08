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
		<form class="row">
            <div class="col-md-2 mt-2">
                <select name="type" class="form-control">
                    <option value="">Semua Tipe</option>
                    <option value="teacher" @selected($type && $type=='teacher')>Pengajar</option>
                    <option value="member" @selected($type && $type=='member')>Anggota</option>
                </select>
            </div>
            <div class="col-md-2 mt-2">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $option)
                        <option value="{{ $option }}" @selected($status && $status==$option)>@lang('app.present.status.'.$option)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2 mt-2">
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
            </div>
            <div class="col-6 col-md-2 mt-2">
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
            </div>
            <div class="col-md-2 mt-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('buttons')
<a href="{{ route('schedules.export', ['status'=>$status,'type'=>$type,'start_date'=>$start_date,'end_date'=>$end_date]) }}" class="btn btn-success btn-sm">
    <i class="la la-download"></i>
    Export (.xls)
</a>
@endsection