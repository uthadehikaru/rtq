@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('salaries.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Course") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('New Course')
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
                            <a href="{{ route('salaries.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($salary)
            <form class="kt-form" method="POST" action="{{ route('salaries.update', $salary->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('salaries.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('Period Name')"
                            value="{{ old('name', $salary?$salary->name:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Start Date')</label>
                            <div class="input-group">
                                <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', $salary?$salary->start_date->format('Y-m-d'):'') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('End Date')</label>
                            <div class="input-group">
                                <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', $salary?$salary->end_date->format('Y-m-d'):'') }}" required>
                            </div>
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