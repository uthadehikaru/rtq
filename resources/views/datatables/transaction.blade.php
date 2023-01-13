@extends('layouts.app')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Uang Kas')
</span>
@endsection
@section('content')
<x-message />
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-users"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Uang Kas : <x-money :amount="$balance" />
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-icon-sm">
                        <i class="la la-plus"></i>
                        @lang('Tambah')
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
		<link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
	<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
	<script>
	jQuery(document).ready(function() {

    $(document).on("click", ".delete", function() { 
        if(confirm("@lang('Apakah kamu yakin?')")) {
            var id= $(this).data('id');
            var url = "{{ route('transactions.index') }}";
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