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
<div class="row">
    <div class="col">

        <x-validation />

        <!--begin::Portlet-->
        <!--begin::Form-->
        <form class="kt-form" method="POST" action="{{ route('teacher.schedules.update', $schedule->id) }}">
        @csrf
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Jadwal {{ $schedule->batch->name }} - {{ $schedule->scheduled_at->format('d M Y') }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('teacher.schedules.index') }}" class="btn btn-warning btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                            @if(!$schedule->end_at)
                            <a href="{{ route('teacher.schedules.close', $schedule->id) }}" class="btn btn-success btn-icon-sm"
                            onclick="return confirm('Apakah anda yakin ingin menutup kelas?')">
                                tutup Kelas
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__body">
                <div class="row">
                        <div class="col-12 col-md-4">
                            <label>Mulai Kelas</label>
                            <input type="time" class="form-control"
                            name="start_at"  value="{{ $schedule->start_at?->format("H:i") }}"  disabled="disabled" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Selesai Kelas</label>
                            <input type="time" class="form-control"
                            name="end_at"  value="{{ $schedule->end_at?->format("H:i") }}"  disabled="disabled" />
                            @if(!$schedule->end_at)
                            <span class="text-help">Saat "Tutup Kelas", Jam selesai akan otomatis tercatat</span>
                            @endif
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Tempat</label>
                            <input type="text" class="form-control"
                            name="place"  value="{{ $schedule->place }}" required />
                        </div>
                    </div>
                    <h3 class="mt-2">{{Auth::user()->name}} {{ $teacherPresent->is_badal?'(Guru Pengganti)':'' }}</h3>
                    @if($teacherPresent->photo)
                    <a href="{{ asset('storage/'.$teacherPresent->photo) }}" target="_blank">Bukti Foto</a>
                    @endif
                    @if($canUpdate)
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label>Status</label>
                            <select class="form-control" name="status[{{$teacherPresent->id}}]" disabled>
                                @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected($status==$teacherPresent->status)>@lang('app.present.status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Jam Kehadiran</label>
                            <input type="time" class="form-control"
                            name="attended_at[{{$teacherPresent->id}}]"  value="{{ $teacherPresent->attended_at?->format('H:i') }}"  disabled="disabled" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Keterangan</label>
                            <input type="text" class="form-control"
                            placeholder="Tidak ada keterangan"
                            name="description[{{$teacherPresent->id}}]"  value="{{ $teacherPresent->description }}" />
                        </div>
                    </div>
                    @else
                        @lang('app.present.status.'.$teacherPresent->status)
                        @if($teacherPresent->status=='present' && $teacherPresent->attended_at)
                            pada {{ $teacherPresent->attended_at->format('H:i')}}
                        @elseif($teacherPresent->status!='present' && $teacherPresent->description)
                            pada {{ $teacherPresent->description}}
                        @else
                            tanpa keterangan
                        @endif
                    @endif
                    <hr/>
                    <h4>Peserta</h4>
                    <div class="row">
                            @foreach($schedule->presents as $present)
                                @if($present->type=='teacher')
                                @continue
                                @endif
                                <div class="col-12 col-md-4">
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    @if($present->user->member->profile_picture)
                                                    <img 
                                                    src="{{ thumbnail($present->user->member->profile_picture, 300, 400) }}"
                                                    class="img-fluid" />
                                                    @else
                                                    <img 
                                                    src="{{ asset('assets/images/default.jpg') }}"
                                                    class="img-fluid" />
                                                    @endif
                                                </div>
                                                <div class="col-8">
                                                    <label class="form-control">{{ $present->name() }}
                                                    <a href="{{ route('teacher.schedules.presents.remove', [$schedule->id, $present->id]) }}" 
                                                    onclick="return confirm('Yakin ingin menghapus?')"
                                                    class="text-danger">(hapus)</a></label>
                                                    <select class="form-control mt-2" name="status[{{$present->id}}]">
                                                        @foreach($statuses as $status)
                                                        <option value="{{ $status }}" @selected($status==$present->status)>@lang('app.present.status.'.$status)</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control mt-2"
                                                    placeholder="Tidak ada keterangan"
                                                    name="description[{{$present->id}}]"  value="{{ $present->description }}" />     
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
                    </div>
            </div>
        </div>
        </form>

        <!--end::Form-->
        

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
    $('#kt-select2-user').select2({
        placeholder: 'Pilih Peserta',
    });
});
</script>
@endpush