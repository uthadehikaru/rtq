@extends('layouts.guest')
@section('title')
Konfirmasi Pembayaran
@endsection

@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<livewire:new-payment :is_member="true" />
</div>
@endsection
@push('scripts')
@livewireScripts
<script>
		$('#paymentModal').modal('show');

		Livewire.on('paymentCreated', function(event){
			alert(event);
		})
</script>
@endpush