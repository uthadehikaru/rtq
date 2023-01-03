@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('schedules.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Schedule") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<a href="{{ route('schedules.presents.index', $schedule->id) }}" class="kt-subheader__breadcrumbs-link">
@lang("Present") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $present?'Ubah':'Tambah'}} Absensi
</span>
@endsection
@section('content')
<div class="row">
    <div class="col">

        <x-validation/>

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        {{ $title }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('schedules.presents.index', $schedule->id) }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($present)
            <form class="kt-form" method="POST" action="{{ route('schedules.presents.update', [$present->schedule_id,$present->id]) }}{{ request()->has('redirect')?'?redirect='.request()->get('redirect'):'' }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('schedules.presents.store', $schedule->id) }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        @if(!$present)
                        <div class="form-group">
                            <label>@lang('Anggota')</label>
                            <select class="form-control" name="user_id" required>
                                <option value="">@lang('Pilih Pengajar')</option>
                                @foreach($teachers as $id=>$name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select class="form-control" name="status" required>
                                <option value="">@lang('Select Status')</option>
                                @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $present && $present->status==$status?'selected':'' }}>@lang('app.present.status.'.$status)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <textarea class="form-control" name="description" placeholder="isi keterangan jika dibutuhkan">{{ $present?->description }}</textarea>
                        </div>
                        @if($present->type=='teacher')
                        <div class="form-group">
                            <label>@lang('Attended At') (Isi jika status hadir)</label>
                            <input type="time" name="attended_at" class="form-control"
                            value="{{ old('attended_at', $present?$present->attended_at?->format('H:i'):'') }}">
                        </div>
                        <div class="form-group">
                            <label>Guru Pengganti</label>
                            <div class="radio-inline">
                                <label class="radio">
                                <input type="radio" value="1" name="is_badal" @checked($present->is_badal)>
                                Ya</label>
                                <label class="radio">
                                <input type="radio" value="0" name="is_badal" @checked(!$present->is_badal)>
                                Tidak</label>
                            </div>
                        </div>
                        @endif
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

        <!--end::Portlet-->
    </div>
</div>
@endsection