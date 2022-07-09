@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('schedules.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Schedule") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<a href="{{ route('schedules.presents.index', $present->schedule_id) }}" class="kt-subheader__breadcrumbs-link">
@lang("Present") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('Edit Present') {{ $present->name() }}
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
                            <a href="{{ route('schedules.presents.index', $present->schedule_id) }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($present)
            <form class="kt-form" method="POST" action="{{ route('schedules.presents.update', [$present->schedule_id,$present->id]) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('schedules.presents.store', $present->schedule_id) }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
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
                            <textarea class="form-control" name="description">{{ $present->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('Attended At')</label>
                            <input type="time" name="attended_at" class="form-control"
                            value="{{ old('attended_at', $present?$present->attended_at:'') }}">
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