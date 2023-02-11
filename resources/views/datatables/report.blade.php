@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('schedules.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Schedule")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ isset($title)?$title:"Data" }}
</span>
@endsection
@section('content')
<x-message />
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
            <div class="col-2">
                <select name="type" class="form-control">
                    <option value="">Semua</option>
                    <option value="teacher" @selected($type && $type=='teacher')>Pengajar</option>
                    <option value="member" @selected($type && $type=='member')>Anggota</option>
                </select>
            </div>
            <div class="col-2">
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
            </div>
            <div class="col-2">
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>
</div>
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-users"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Data Laporan {{ Carbon\Carbon::parse($start_date)?->format('d M Y') }} - {{ Carbon\Carbon::parse($end_date)?->format('d M Y') }}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('schedules.export', ['type'=>$type,'start_date'=>$start_date,'end_date'=>$end_date]) }}" class="btn btn-success">
                        <i class="la la-download"></i>
                        Export (.xls)
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
		<div class="table-responsive">
        {{ $dataTable->table() }}
		</div>
    </div>
</div>
@endsection
@push('styles')
		<link href="{{ asset('assets/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/buttons.dataTables.min.css') }}" />
@endpush
@push('scripts')
	<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.dataTables.min.js') }}"></script>
	<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}" type="text/javascript"></script>
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
	<script>
	jQuery(document).ready(function() {

    $(document).on("click", ".delete", function() { 
        if(confirm("@lang('Apakah kamu yakin?')")) {
            var id= $(this).data('id');
            var url = "{{ url()->current() }}";
            var dltUrl = url+"/"+id;
            $.ajax({
                url: dltUrl,
                type: "DELETE",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}'
                },
                success: function(dataResult){
                    if(dataResult.statusCode==200){
                        alert('@lang('Berhasil dihapus')');
                        location.reload(true);
                    }
                }
            });
        }
	});
});
</script>
@endpush