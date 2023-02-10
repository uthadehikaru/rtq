@extends('layouts.app')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ isset($title)?$title:"Data" }}
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
                Data {{ isset($title)?$title:"Data" }}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    @if(isset($buttons))
                    {!! $buttons !!}
                    @endif
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