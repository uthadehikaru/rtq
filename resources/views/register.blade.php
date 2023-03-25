@extends('layouts.guest')
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<x-header />
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-grid kt-wizard-v1 kt-wizard-v1--white" id="kt_wizard_v1" data-ktwizard-state="first">
                <div class="kt-grid__item">

                    <!--begin: Form Wizard Nav -->
                    <div class="kt-wizard-v1__nav">

                        <!--doc: Remove "kt-wizard-v1__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                        <div class="kt-wizard-v1__nav-items kt-wizard-v1__nav-items--clickable">
                            <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon-bus-stop"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        1. Data Pribadi
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon-list"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        2. Data Pendidikan
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon-responsive"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        3. Seputar Tahsin
                                    </div>
                                </div>
                            </div>
                            <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="pending">
                                <div class="kt-wizard-v1__nav-body">
                                    <div class="kt-wizard-v1__nav-icon">
                                        <i class="flaticon-globe"></i>
                                    </div>
                                    <div class="kt-wizard-v1__nav-label">
                                        4. Syarat dan Ketentuan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end: Form Wizard Nav -->
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">

                    <!--begin: Form Wizard Form-->
                    <form class="kt-form" id="kt_form" method="POST" action="{{ route('register', $type) }}" novalidate="novalidate">
						@csrf
                        <!--begin: Form Wizard Step 1-->
                        <div class="kt-wizard-v1__content" data-ktwizard-type="step-content"
                            data-ktwizard-state="current">
                            <div class="kt-heading kt-heading--md">Selamat datang calon peserta Tahsin {{ Str::title($type) }} {{ config('app.name') }}</div>
							<p>Ini merupakan formulir untuk daftar tunggu calon peserta tahsin. Mohon mengisi data pribadi anda/anak anda untuk keperluan verifikasi data kami.</p>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v1__form">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>NIK</label>
												<input type="text" class="form-control" name="nik" id="nik"
													placeholder="Nomor induk kependudukan"
													aria-describedby="nik-error">
												<span class="form-text text-muted">Mohon masukkan nomor induk kependudukan {{ $type=='dewasa'?'anda':'anak anda'}}.</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Nama Lengkap</label>
												<input type="text" class="form-control" name="full_name"
													placeholder="Nama Lengkap"
													aria-describedby="full_name-error">
												<span class="form-text text-muted">Mohon masukkan nama lengkap {{ $type=='dewasa'?'anda':'anak anda'}}.</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Nama Panggilan</label>
												<input type="text" class="form-control" name="short_name"
													placeholder="Nama Panggilan">
												<span class="form-text text-muted">Mohon masukkan nama panggilan {{ $type=='dewasa'?'anda':'anak anda'}}.</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Jenis kelamin</label>
												<div class="radio-inline">
													<label class="radio">
														<input type="radio" name="gender" value="male" required>
														<span></span>
														Laki-Laki
													</label>
													<label class="radio">
														<input type="radio" name="gender" value="female" required>
														<span></span>
														Perempuan
													</label>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Tempat Lahir</label>
												<input type="text" class="form-control" name="birth_place"
												aria-describedby="birth_place-error">
												<span class="form-text text-muted">Mohon masukkan tempat lahir {{ $type=='dewasa'?'anda':'anak anda'}}.</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Tanggal Lahir</label>
												<input type="date" class="form-control" name="birth_date"
												aria-describedby="birth_date-error">
												<span class="form-text text-muted">Mohon masukkan tanggal lahir {{ $type=='dewasa'?'anda':'anak anda'}}.</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Alamat</label>
												<textarea class="form-control" name="address"></textarea>
												<span class="form-text text-muted">Mohon masukkan alamat domisili {{ $type=='dewasa'?'anda':'anak anda'}}.</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>No Telp (Whatsapp)</label>
												<input type="text" class="form-control" name="phone"
												aria-describedby="phone-error">
												<span class="form-text text-muted">Mohon masukkan nomor whatsapp {{ $type=='dewasa'?'anda':'anda/anak anda'}} yang aktif.</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Email</label>
												<input type="text" class="form-control" name="email"
												aria-describedby="email-error">
												<span class="form-text text-muted">Mohon masukkan email {{ $type=='dewasa'?'anda':'anda/anak anda'}} yang aktif.</span>
											</div>
										</div>
									</div>
									@if(in_array($type,['anak','balita']))
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Nama Ayah</label>
												<input class="form-control" name="father_name">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Nama Ibu</label>
												<input class="form-control" name="mother_name">
											</div>
										</div>
									</div>
									@endif
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 1-->

                        <!--begin: Form Wizard Step 2-->
                        <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Data Pendidikan</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v1__form">
									@if(in_array($type,['anak','dewasa']))
                                    <div class="form-group">
                                        <label>Tingkat Pendidikan {{ $type=='anak'?'Saat ini':'Terakhir'}}</label>
                                        <select name="school_level" class="form-control" aria-describedby="school_level-error">
                                            <option value="">-- Pilih --</option>
											<option value="tidak sekolah">Tidak Sekolah</option>
											<option value="tk">TK / TPA / Sederajat</option>
											<option value="sd">SD / Sederajat</option>
											<option value="smp">SMP / Sederajat</option>
											<option value="sma">SMA / Sederajat</option>
											@if($type=='dewasa')
											<option value="D3">Diploma (D3)</option>
											<option value="S1">Strata 1 (S1)</option>
											<option value="S2">Strata 2 (S2)</option>
											<option value="S3">Strata 3 (S3)</option>
											@endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Instansi/Sekolah</label>
                                        <input type="text" class="form-control" name="school_name"
                                            placeholder="Masukkan nama instansi/sekolah" aria-describedby="school_name-error">
                                        <span class="form-text text-muted">Masukkan nama instansi/Sekolah</span>
                                    </div>
									@endif
									@if($type=='anak')
                                    <div class="form-group">
                                        <label>Kelas</label>
                                        <input type="text" class="form-control" name="class"
                                            placeholder="Masukkan kelas/jurusan" aria-describedby="class-error">
                                    </div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Jam Mulai Sekolah</label>
												<input type="time" class="form-control" name="school_start_time">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Jam Selesai Sekolah</label>
												<input type="time" class="form-control" name="school_end_time">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Kegiatan Setelah Sekolah</label>
												<textarea class="form-control" name="activity"></textarea>
											</div>
										</div>
									</div>
									@elseif($type=='balita')
									<div class="form-group">
										<label>Kegiatan Belajar Sehari-hari</label>
										<textarea class="form-control" name="activity" required></textarea>
                                        <span class="form-text text-muted">Misal TK, TPA atau lainnya</span>
									</div>
									@endif
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 2-->

                        <!--begin: Form Wizard Step 3-->
                        <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Seputar Tahsin</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v1__form">
									@if($type=='dewasa')
                                    <div class="form-group">
                                        <label>Jadwal Tahsin yang diinginkan</label>
                                        <div class="radio-inline">
											<label class="radio">
												<input type="radio" name="schedule_option" value="weekday">
												<span></span>
												Hari Biasa (Senin-Jum'at)
											</label>
											<label class="radio">
												<input type="radio" name="schedule_option" value="weekend">
												<span></span>
												Hari Libur (Sabtu-Ahad)
											</label>
										</div>
                                    </div>
									@endif
									<div class="form-group">
										<label>Referensi Peserta Tahsin di RTQ</label>
										<input class="form-control" name="reference" />
                                        <span class="form-text text-muted">Masukkan nama peserta tahsin yang sudah tergabung di RTQ</span>
									</div>
									@if(in_array($type,['anak','balita']))
									<div class="form-group">
										<label>Jadwal Tahsin Saudara di RTQ</label>
										<textarea class="form-control" name="reference_schedule"></textarea>
                                        <span class="form-text text-muted">Jika ada kakak/saudara yang sudah ikut, mohon sebutkan nama dan jadwal tahsinnya</span>
									</div>
									@endif
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 3-->

                        <!--begin: Form Wizard Step 4-->
                        <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Syarat dan Ketentuan</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v1__review">
                                    <div class="kt-wizard-v1__review-item">
                                        <div class="kt-wizard-v1__review-title">
                                            Dengan mendaftar maka anda menyetujui Syarat dan Ketentuan yang ditetapkan :
                                        </div>
                                        <div class="kt-wizard-v1__review-content">
                                            <ol>
												<li>Anda setuju mendaftar sebagai calon peserta tahsin dan masuk dalam daftar tunggu kami</li>
												<li>Data yang anda masukkan akan kami gunakan untuk kebutuhan internal dan penilaian atas anda</li>
												<li>Kami akan menghubungi anda jika data anda cocok dan tersedia kuota sesuai pilihan yang telah anda tentukan</li>
												<li>Penilaian kami bersifat mutlak dan tidak dapat diganggu gugat</li>
												<li>Apabila anda selama proses masa tunggu ingin membatalkan, mohon konfirmasi kepada kami terlebih dahulu</li>
											</ol>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v1__review-item">
										<div class="kt-checkbox-inline">
											<label class="kt-checkbox">
												<input type="checkbox" name="term_condition" value="1" required> Saya Menyetujui syarat dan ketentuan diatas
												<span></span>
											</label>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 4-->

                        <!--begin: Form Actions -->
                        <div class="kt-form__actions">
                            <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                data-ktwizard-type="action-prev">
                                Sebelumnya
                            </button>
                            <button class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                data-ktwizard-type="action-submit">
                                Daftar
                            </button>
                            <button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                data-ktwizard-type="action-next">
                                Selanjutnya
                            </button>
                        </div>

                        <!--end: Form Actions -->
                    </form>

                    <!--end: Form Wizard Form-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
	<link href="{{ asset('assets/css/pages/support-center/home-1.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/input-mask.js') }}" type="text/javascript"></script>

<script>
	var KTWizard1 = function () {
	// Base elements
	var wizardEl;
	var formEl;
	var validator;
	var wizard;

	// Private functions
	var initWizard = function () {
		// Initialize form wizard
		wizard = new KTWizard('kt_wizard_v1', {
			startStep: 1, // initial active step number
			clickableSteps: true  // allow step clicking
		});

		// Validation before going to next page
		wizard.on('beforeNext', function(wizardObj) {
			if (validator.form() !== true) {
				wizardObj.stop();  // don't go to the next step
			}
		});

		wizard.on('beforePrev', function(wizardObj) {
			if (validator.form() !== true) {
				wizardObj.stop();  // don't go to the next step
			}
		});

		// Change event
		wizard.on('change', function(wizard) {
			setTimeout(function() {
				KTUtil.scrollTop();
			}, 500);
		});
	}

	var initValidation = function() {
		validator = formEl.validate({
			// Validate only visible fields
			ignore: ":hidden",

			// Validation rules
			rules: {
				//= Step 1
				nik: {
					required: true,
					minlength: 16,
				},
				full_name: {
					required: true
				},
				short_name: {
					required: true
				},
				gender: {
					required: true
				},
				birth_place: {
					required: true
				},
				birth_date: {
					required: true,
					date: true,
				},
				address: {
					required: true
				},
				phone: {
					required: true
				},
				email: {
					required: true,
					email: true,
				},

				//= Step 2
				school_level: {
					required: true
				},
				school_name: {
					required: true
				},

				//= Step 3
				reference: {
					required: true
				},

				//= Step 4
				term_condition: {
					required: true
				},
			},

			// Display error
			invalidHandler: function(event, validator) {
				KTUtil.scrollTop();

				swal.fire({
					"title": "",
					"text": "Ada beberapa data yang belum sesuai, mohon cek kembali",
					"type": "error",
					"confirmButtonClass": "btn btn-secondary"
				});
			},

			// Submit valid form
			submitHandler: function (form) {

			}
		});
	}

	var initSubmit = function() {
		var btn = formEl.find('[data-ktwizard-type="action-submit"]');

		btn.on('click', function(e) {
			e.preventDefault();

			if (validator.form()) {
				// See: src\js\framework\base\app.js
				KTApp.progress(btn);
				//KTApp.block(formEl);

				// See: http://malsup.com/jquery/form/#ajaxSubmit
				formEl.ajaxSubmit({
					success: function() {
						KTApp.unprogress(btn);
						//KTApp.unblock(formEl);

						swal.fire({
							"title": "",
							"text": "Formulir anda telah kami terima, mohon tunggu informasi selanjutnya",
							"type": "success",
							"confirmButtonClass": "btn btn-secondary"
						})
						.then((value) => {
							window.location.href = "{{ route('home') }}"
						});
					},
					error: function (xhr, ajaxOptions, thrownError) {
						KTApp.unprogress(btn);
						//KTApp.unblock(formEl);

						swal.fire({
							"title": "Gagal mengirim data, mohon coba kembali",
							"text": xhr.responseJSON.message,
							"type": "error",
							"confirmButtonClass": "btn btn-secondary"
						});
					}
				});
			}
		});
	}

	return {
		// public functions
		init: function() {
			wizardEl = KTUtil.get('kt_wizard_v1');
			formEl = $('#kt_form');

			initWizard();
			initValidation();
			initSubmit();
		}
	};
}();

jQuery(document).ready(function() {
	KTWizard1.init();
	$('#nik').inputmask({"mask": "9999999999999999"});
});

</script>
@endpush
