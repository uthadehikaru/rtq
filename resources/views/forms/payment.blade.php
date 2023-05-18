@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('payments.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Pembayaran") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $payment?'Ubah':'Tambah' }}
</span>
@endsection
@section('content')
<div class="row">
    <div class="col">
        <livewire:payment-form :payment="$payment" :periods="$periods" />
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
jQuery(document).ready(function () {    
    $('.kt-select2').select2({
        placeholder: "@lang('Pilih Anggota')"
    });

    $(document).on("click", ".delete", function() { 
        if(confirm("@lang('Are you sure?')")) {
            var id= $(this).data('id');
            var url = "{{ route('payments.details.index', $payment->id) }}";
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
                        alert('@lang('Deleted Successfully')');
                        location.reload(true);
                    }
                }
            });
        }
	});
});
</script>
@endpush