@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('courses.index') }}" class="kt-subheader__breadcrumbs-link">
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
                            <a href="{{ route('courses.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($course)
            <form class="kt-form" method="POST" action="{{ route('courses.update', $course->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('courses.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('Enter Course Name')"
                            value="{{ old('name', $course?$course->name:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Tipe')</label>
                            <div class="input-group">
                                <select class="form-control" name="type" required>
                                    @foreach ($types as $type)
                                    <option value='{{ $type }}'>{{ $type }}</option>
                                    @endforeach
                                </select>
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