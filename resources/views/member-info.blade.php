@extends('layouts.guest')
@push('scripts')
<!-- <script src="{{ asset('assets/js/pages/crud/forms/widgets/tagify.js') }}" type="text/javascript"></script> -->
<script type="text/javascript">

var KTSelect2 = function() {
	
    // Private functions
    var demos = function() {
		
		$('.kt-select-member').select2({
			placeholder: "@lang('Pilih Peserta')",
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
					<div class="kt-portlet__head kt-portlet__head--lg" style="">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">UPDATE BIODATA <small>khusus peserta tahsin RTQ</small></h3>
						</div>
						<div class="kt-portlet__head-toolbar">
							<a href="{{ route('home') }}" class="btn btn-clean kt-margin-r-10">
								<i class="la la-arrow-left"></i>
								<span class="kt-hidden-mobile">@lang('Back')</span>
							</a>
							<button class="btn btn-brand">
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
												<label class="col-3 col-form-label">Peserta</label>
												<div class="col-9">
													<select class="form-control kt-select-member" name="member_id" required>
														<option></option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-3 col-form-label">NIK</label>
												<div class="col-9">
													<input class="form-control" type="text" name="nik" value="{{ old('nik', '') }}" required>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-3 col-form-label">Tgl Lahir</label>
												<div class="col-9">
													<input class="form-control" type="date" name="birth_date" value="{{ old('birth_date', '') }}" required>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-3 col-form-label">Profile Picture</label>
												<div class="col-9">
													<input class="form-control" type="file" name="profile_picture" accept="image/jpeg,image/png" required>
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