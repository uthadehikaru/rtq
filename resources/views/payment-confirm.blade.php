@extends('layouts.guest')
@section('title')
Konfirmasi Pembayaran
@endsection
@push('scripts')
<script type="text/javascript">

var KTSelect2 = function() {
	
    // Private functions
    var demos = function() {
		
		$('.kt-select-member').select2({
			placeholder: "@lang('Ketik nama peserta untuk mencari')",
			minimumInputLength: 3,
			ajax: {
				url: '{{ route('api.batchmembers') }}',
				dataType: 'json',
				processResults: function (data) {
					return {
						results: data.items
					};
				}
			}
		});

		$('.kt-select-period').select2({
			placeholder: "@lang('Pilih Periode Pembayaran')"
		});

		$('select').on('select2:close', function (evt) {
			var members = $('.kt-select-member').select2('data').length
			var periods = $('.kt-select-period').select2('data').length
			$('#total').val(members*periods*120000)
		});

		function formatRepo(repo) {
			if (repo.loading) return repo.text;
			var markup = repo.full_name+" Halaqoh "+repo.name;
			return markup;
		}

		function formatRepoSelection(repo) {
			return repo.full_name || repo.text;
		}

	}
	// Public functions
	return {
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function () {
	KTSelect2.init();

	$('#kt_form').submit(function(){
		$('#btn-submit').prop('disabled', true);
		$('#btn-submit').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
	});

});
</script>
@endpush
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Content -->

		<div class="row">
			<div class="col">
				@if(session()->has('message'))
				<x-alert type="success">{{ session()->get('message') }}</x-alert>
				@endif
				<x-validation />

				<!--begin::Portlet-->
				<form class="kt-form" id="kt_form" method="POST" action="" enctype="multipart/form-data">
				@csrf
				<div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile" id="kt_page_portlet">
					<div class="kt-portlet__head kt-portlet__head--lg">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">Konfirmasi Pembayaran <small>khusus peserta tahsin RTQ</small></h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<a href="{{ $period_id?route('periods.payments.index', $period_id):url('') }}" class="btn btn-clean kt-margin-r-10">
								<i class="la la-arrow-left"></i>
								<span class="kt-hidden-mobile">@lang('Back')</span>
							</a>
							<button class="btn btn-brand" id="btn-submit">
								<i class="la la-check"></i>
								<span class="kt-hidden-mobile">@lang('Save')</span>
							</button>
						</div>
					</div>
					<div class="kt-portlet__body">
							<div class="row">
								<div class="col-xl-2"></div>
								<div class="col-xl-8">
									<div class="kt-section kt-section--first">
										<div class="kt-section__body">
											<h3 class="kt-section__title kt-section__title-lg">Data Peserta:</h3>
											<div class="form-group row">
												<label class="col-3 col-form-label">@lang('Period')</label>
												<div class="col-9">
													<select class="form-control kt-select-period" name="period_ids[]" multiple>
														<option></option>
														@foreach($periods as $period)
														<option value="{{ $period->id }}" {{ $period->id==$period_id?'selected':'' }}>{{ $period->name }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-3 col-form-label">Peserta</label>
												<div class="col-9">
													<select class="form-control kt-select-member" name="members[]" multiple>
														<option></option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-3 col-form-label">Total Transfer</label>
												<div class="col-9">
													<input class="form-control" id="total" type="number" name="total" value="{{ old('total', 0) }}">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-3 col-form-label">Bukti Transfer</label>
												<div class="col-9">
													<input class="form-control" type="file" name="attachment" accept="image/*" required>
												</div>
											</div>
										</div>
									</div>
							</div>
					</div>
				</div>
				</form>
				<!--end::Portlet-->
			</div>
		</div>
@endsection
@push('styles')
<link href="{{ asset('assets/css/pages/support-center/home-1.css') }}" rel="stylesheet" type="text/css">
@endpush