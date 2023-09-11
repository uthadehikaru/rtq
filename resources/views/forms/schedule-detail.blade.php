@extends('layouts.app')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Jadwal 
</span>
@endsection
@section('content')
@if(session()->has('message'))
<x-alert type="success">{{ session()->get('message') }}</x-alert>
@endif

<x-alert type="info" icon="flaticon-information ">Perhatian : Untuk perubahan status dan deskripsi peserta, tidak perlu mengklik tombol simpan lagi. Sistem akan otomatis memperbaharui saat ada perubahan.</x-alert>
<div class="row">
    <div class="col">

        <!--begin::Portlet-->
        <!--begin::Form-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Jadwal {{ $schedule->batch->name }} - {{ $schedule->scheduled_at->format('d M Y') }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions text-right">
                            <a href="{{ route('teacher.schedules.index') }}" class="btn btn-warning btn-icon-sm mt-2">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                            @if(!$schedule->end_at)
                            <a href="{{ route('teacher.schedules.close', $schedule->id) }}" class="btn btn-success btn-icon-sm mt-2"
                            onclick="return confirm('Apakah anda yakin ingin menutup kelas?')">
                                tutup Kelas
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($teacherPresent)
            <div class="kt-portlet__body">
                <livewire:teacher :present="$teacherPresent" :schedule="$schedule" />
            </div>
            @endif
            <livewire:presents :presents="$schedule->presents" />
        </div>
        

        <form class="kt-form" method="POST" action="{{ route('teacher.schedules.presents.add', $schedule->id) }}">
        @csrf
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Oper Santri
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <button class="btn btn-primary btn-icon-sm">
                                <i class="la la-plus"></i>
                                @lang('Tambah')
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label>Peserta (bisa lebih dari satu)</label>
                            <select class="form-control select2" id="kt-select2-user" name="user_id[]" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->member->batch()->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected($status=='present')>@lang('app.present.status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Keterangan</label>
                            <input type="text" class="form-control"
                            placeholder="Masukkan keterangan"
                            name="description" />
                        </div>
                    </div>
            </div>
        </div>
        </form>

    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
jQuery(document).ready(function() {
    Livewire.on('message', id => {
        $('#message-'+id).text('Berhasil disimpan');
        setInterval(function() {
            $('#message-'+id).text('');
        }, 2000);
    });

    $('#kt-select2-user').select2({
        placeholder: 'Pilih Peserta',
    });
});
</script>
@endpush