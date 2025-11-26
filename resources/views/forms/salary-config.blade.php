@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('salaries.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Salaries")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Konfigurasi
</span>
@endsection
@section('content')
<div class="row">
    <div class="col">

        <x-validation/>
        @if(session()->has('message'))
        <x-alert type="success">{{ session('message') }}</x-alert>
        @endif

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Konfigurasi Bisyaroh
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('salaries.index') }}" class="btn btn-warning btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            <form class="kt-form" method="POST" action="{{ route('salaries.config') }}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        @foreach ($course_types as $type)
                        <div class="form-group">
                            <label>Kelas {{ $type }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="number" name="{{ Str::snake($type) }}" class="form-control"
                                value="{{ old(Str::snake($type), $settings[Str::snake($type)]) }}"
                                required>
                            </div>
                        </div>
                        @endforeach
                        <div class="form-group">
                            <label>Operan Santri</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="number" name="oper_santri" class="form-control"
                                value="{{ old('oper_santri', $settings['oper_santri']) }}"
                                required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Transportasi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="number" name="transportasi" class="form-control"
                                value="{{ old('transportasi', $settings['transportasi']) }}"
                                required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tunjangan Kuliah/Pribadi/Keluarga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="number" name="tunjangan" class="form-control"
                                value="{{ old('tunjangan', $settings['tunjangan']) }}"
                                required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Maksimal Kehadiran yang mendapatkan tunjangan (dalam persentase)</label>
                            <div class="input-group">
                                <input type="number" name="maks_izin" class="form-control"
                                value="{{ old('maks_izin', $settings['maks_izin']) }}"
                                required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Kali</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Potongan Telat Tanpa Konfirmasi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="number" name="telat_tanpa_konfirmasi" class="form-control"
                                value="{{ old('telat_tanpa_konfirmasi', $settings['telat_tanpa_konfirmasi']) }}"
                                required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Maksimal Telat Dengan Konfirmasi</label>
                            <div class="input-group">
                                <input type="number" name="maks_telat_dengan_konfirmasi" class="form-control"
                                value="{{ old('maks_telat_dengan_konfirmasi', $settings['maks_telat_dengan_konfirmasi']) }}"
                                required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Kali</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Maksimal Waktu Telat (Menit)</label>
                            <div class="input-group">
                                <input type="number" name="maks_waktu_telat" class="form-control"
                                value="{{ old('maks_waktu_telat', $settings['maks_waktu_telat']) }}"
                                required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Menit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('Simpan')</button>
                        <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
                    </div>
                </div>
            </form>

            <!--end::Form-->
        </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection