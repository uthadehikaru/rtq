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
                    <option value="">Semua</option>
                    <option value="teacher" @selected($type && $type=='teacher')>Pengajar</option>
                    <option value="member" @selected($type && $type=='member')>Anggota</option>
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