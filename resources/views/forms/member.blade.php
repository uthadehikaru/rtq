@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('members.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Members") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $title }}
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
                            <a href="{{ route('members.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($member)
            <form class="kt-form" method="POST" action="{{ route('members.update', $member->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('members.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Full Name')</label>
                            <input type="text" name="full_name" class="form-control" placeholder="@lang('Enter') @lang('Full Name')"
                            value="{{ old('full_name', $member?$member->full_name:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Short Name')</label>
                            <input type="text" name="short_name" class="form-control" placeholder="@lang('Enter') @lang('Short Name')"
                            value="{{ old('short_name', $member?$member->short_name:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="email" name="email" class="form-control" placeholder="@lang('Enter') @lang('Email')"
                            value="{{ old('email', $member?$member->email:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Phone')</label>
                            <input type="text" name="phone" class="form-control" placeholder="@lang('Enter') @lang('Phone')"
                            value="{{ old('phone', $member?$member->phone:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Gender')</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" value="male" {{ $member && $member->gender=='male'?'checked="checked"':'' }} name="gender"> @lang('Male')
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" value="female" {{ $member && $member->gender=='female'?'checked="checked"':'' }} name="gender"> @lang('Female')
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Address')</label>
                            <input type="text" name="address" class="form-control" placeholder="@lang('Enter') @lang('Address')"
                            value="{{ old('address', $member?$member->address:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Post Code')</label>
                            <input type="text" name="postcode" class="form-control" placeholder="@lang('Enter') @lang('Post Code')"
                            value="{{ old('postcode', $member?$member->postcode:'') }}"
                            >
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