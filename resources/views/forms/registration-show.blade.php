@extends('layouts.app')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Pendaftaran')
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
                No Pendaftaran {{ $registration->registration_no }} | Tahsin {{ $registration->type }}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('registrations.index') }}" class="btn btn-warning btn-icon-sm">
                        <i class="la la-arrow-left"></i>
                        @lang('Kembali')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
		<h3>Data Pribadi</h3>
        <p>Nama Lengkap : {{ $registration->full_name }}</p>
        <p>Nama Panggilan : {{ $registration->short_name }}</p>
        <p>Jenis Kelamin : @lang($registration->gender)</p>
        <p>Tempat, Tanggal Lahir : {{ $registration->birth_place }}, {{ $registration->birth_date?->format('d M Y') }}</p>
        @if($registration->birth_date)
        <p>Usia : {{ Carbon\Carbon::now()->diffInYears($registration->birth_date) }} Tahun</p>
        @endif
        <p>Alamat : {{ $registration->address }}</p>
        <p>No. Telp : {{ $registration->phone }}</p>
        <p>Email : {{ $registration->email }}</p>
        @if(in_array($registration->type,['anak','balita']))
        <p>Nama Ayah : {{ $registration->father_name }}</p>
        <p>Nama Ibu : {{ $registration->mother_name }}</p>
        @endif
        <h3>Data Pendidikan</h3>
        @if(in_array($registration->type,['anak','dewasa']))
        <p>Tingkat Pendidikan : {{ $registration->school_level }}</p>
        <p>Nama Instansi/Sekolah : {{ $registration->school_name }}</p>
        @endif
        @if($registration->type=='anak')
        <p>Kelas : {{ $registration->full_name }}</p>
        <p>Jam Sekolah  : {{ $registration->school_start_time?->format('H:i') }} - {{ $registration->school_end_time?->format('H:i') }}</p>
        <p>Kegiatan Setelah Sekolah  : {{ $registration->activity }}</p>
        @elseif($registration->type=='balita')
        <p>Kegiatan Belajar Sehari-hari  : {{ $registration->activity }}</p>
        @endif
        <h3>Data Tahsin</h3>
        <p>Referensi : {{ $registration->reference }}</p>
        @if(in_array($registration->type,['anak','balita']))
        <p>Jadwal tahsin saudara : {{ $registration->reference_schedule }}</p>
        @endif
    </div>
</div>
@if(!$registration->user_id)
<!--begin::Portlet-->
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Diterima di Halaqoh
            </h3>
        </div>
    </div>

    <!--begin::Form-->
    <form class="kt-form" method="POST" action="{{ route('registrations.update', $registration->id) }}">
        @csrf
        <input type="hidden" name="_method" value="PUT" />
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                <div class="form-group">
                    <label>@lang('Batch')</label>
                    <select name="batch_id" class="form-control kt-select2" required>
                        <option value="">--- Pilih Halaqoh ---</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->course->name }} @lang('Batch') {{ $batch->name }} : {{ $batch->members_count }} peserta</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
@endif
@endsection