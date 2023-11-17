@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('programs.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Program") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    @lang('New Program')
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
                            <a href="{{ route('programs.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($program)
            <form class="kt-form" method="POST" action="{{ route('programs.update', $program->id) }}" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('programs.store') }}" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input type="text" name="title" class="form-control" placeholder="@lang('Enter Program title')"
                                    value="{{ old('title', $program?$program->title:'') }}"
                                    required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('Slug')</label>
                                    <input type="text" name="slug" class="form-control" placeholder="program slug" readonly
                                    value="{{ old('slug', $program?$program->slug:'') }}"
                                    required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('Amount')</label>
                                    <input type="number" name="amount" class="form-control" placeholder="@lang('Enter Program amount')"
                                    value="{{ old('amount', $program?$program->amount:'') }}"
                                    required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('Donatur')</label>
                                    <input type="number" name="qty" class="form-control" placeholder="program qty"
                                    value="{{ old('qty', $program?$program->qty:'') }}"
                                    required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Thumbnail')</label>
                                    @if($program && $program->thumbnail)
                                    <img src="{{ $program->imageUrl('thumbnail') }}" />
                                    @endif
                                    <input type="file" name="thumbnail" class="form-control" accept="image" />
                                </div>
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