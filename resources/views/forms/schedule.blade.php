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
                            <input type="datetime-local" name="scheduled_at" class="form-control"
                            value="{{ old('scheduled_at', $schedule?$schedule->scheduled_at:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Batch')</label>
                            <select class="form-control" name="batch_id" required>
                                <option value="">@lang('Select Batch')</option>
                                @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ $schedule && $schedule->batch_id==$batch->id?'selected':'' }}>{{ $batch->course->name }} {{ $batch->name }} {{ $batch->teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Switch Teacher')</label>
                            <select class="form-control" name="teacher_id">
                                <option value="">Tidak ada pengganti</option>
                                @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $schedule && $schedule->teacher_id==$teacher->id?'selected':'' }}>{{ $teacher->name }}</option>
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

        <!--end::Portlet-->
    </div>
</div>
@endsection