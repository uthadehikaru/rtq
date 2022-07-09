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
            <form class="kt-form" method="POST" action="{{ route('members.switch', $member->id) }}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Member')</label>
                            <select name="member_id" class="form-control" required>
                                <option value="">--- @lang('Select :name',['name'=>__('Member')]) ---</option>
                                @foreach($members as $otherMember)
                                    @if($otherMember->id==$member->id)
                                        @continue
                                    @endif
                                    <option value="{{ $otherMember->id }}">{{ $otherMember->full_name }} - {{ $otherMember->batchName() }}</option>
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