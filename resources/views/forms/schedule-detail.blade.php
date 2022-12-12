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
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Jadwal {{ $schedule->batch->name }} - {{ $schedule->scheduled_at->format('d M Y H:i') }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('teacher.schedules.index') }}" class="btn btn-warning btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__body">
                <!--begin::Form-->
                <form class="kt-form" method="POST" action="{{ route('teacher.schedules.update', $schedule->id) }}">
                    @csrf
                <div class="row">
                        <div class="col-12 col-md-4">
                            <label>Mulai Kelas</label>
                            <input type="time" class="form-control"
                            name="start_at"  value="{{ $schedule->start_at?->format("H:i") }}"  disabled="disabled" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Selesai Kelas</label>
                            <input type="time" class="form-control"
                            name="end_at"  value="{{ $schedule->end_at?->format("H:i") }}" />
                        </div>
                    </div>
                    <h3>{{Auth::user()->name}}</h3>
                    @if($canUpdate)
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label>Status</label>
                            <select class="form-control" name="status[{{$teacherPresent->id}}]" disabled="disabled">
                                @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected($status==$teacherPresent->status)>@lang('app.present.status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Jam Kehadiran (diisi jika hadir)</label>
                            <input type="time" class="form-control"
                            name="attended_at[{{$teacherPresent->id}}]"  value="{{ $teacherPresent->attended_at?->format('H:i') }}"  disabled="disabled" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Keterangan</label>
                            <input type="text" class="form-control"
                            placeholder="Masukkan keterangan"
                            name="description[{{$teacherPresent->id}}]"  value="{{ $teacherPresent->description }}"  disabled="disabled" />
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule->presents as $present)
                                @if($present->type=='teacher')
                                @continue
                                @endif
                                <tr>
                                    <td>
                                        {{ $present->name() }}
                                        <a href="{{ route('teacher.schedules.presents.remove', [$schedule->id, $present->id]) }}" 
                                        onclick="return confirm('Yakin ingin menghapus?')"
                                        class="text-danger">(hapus)</a>
                                    </td>
                                    <td>
                                        <select class="form-control" name="status[{{$present->id}}]">
                                            @foreach($statuses as $status)
                                            <option value="{{ $status }}" @selected($status==$present->status)>@lang('app.present.status.'.$status)</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                        placeholder="Masukkan keterangan"
                                        name="description[{{$present->id}}]"  value="{{ $present->description }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
                    </div>
                </form>

                <!--end::Form-->
            </div>
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
                            <label>Peserta</label>
                            <select class="form-control" name="user_id">
                                <option value="">Pilih Peserta</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->member->batch()->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                @foreach($statuses as $status)
                                <option value="{{ $status }}">@lang('app.present.status.'.$status)</option>
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