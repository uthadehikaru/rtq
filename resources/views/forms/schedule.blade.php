@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('schedules.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Schedule") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('New Schedule')
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
                            <a href="{{ route('schedules.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($schedule)
            <form class="kt-form" method="POST" action="{{ route('schedules.update', $schedule->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('schedules.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Scheduled At')</label>
                            <input type="date" name="scheduled_at" class="form-control"
                            value="{{ old('scheduled_at', $schedule?$schedule->scheduled_at->format('Y-m-d'):'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Batch')</label>
                            <select class="form-control" name="batch_id" required>
                                <option value="">@lang('Select Batch')</option>
                                @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ $schedule && $schedule->batch_id==$batch->id?'selected':'' }}>{{ $batch->course->name }} {{ $batch->name }} ({{ $batch->start_time?->format('H:i') }} @ {{ $batch->place }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Teacher')</label>
                            <select class="form-control kt-select2" name="teacher_ids[]" multiple>
                                @foreach($teachers as $teacher)
                                <option value="{{ $teacher->user_id }}" {{ $schedule && $schedule->teachers()->find($teacher->id)?'selected':'' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Mulai')</label>
                            <input type="time" name="start_at" class="form-control"
                            value="{{ old('start_at', $schedule?$schedule->start_at?->format('H:i'):'') }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Selesai')</label>
                            <input type="time" name="end_at" class="form-control"
                            value="{{ old('start_at', $schedule?$schedule->end_at?->format('H:i'):'') }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Tempat')</label>
                            <input type="text" name="place" class="form-control"
                            value="{{ old('place', $schedule?$schedule->place:'') }}">
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

        <!--end::Portlet-->
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
jQuery(document).ready(function() {
    $('.kt-select2').select2({
        placeholder: 'Pilih Pengajar',
    });
});
</script>
@endpush