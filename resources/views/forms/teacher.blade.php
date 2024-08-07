@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('teachers.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Teachers") </a>
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
                            <a href="{{ route('teachers.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($teacher)
            <form class="kt-form" method="POST" action="{{ route('teachers.update', $teacher->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('teachers.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('Enter') @lang('Name')"
                            value="{{ old('name', $teacher?$teacher->name:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="email" name="email" class="form-control" placeholder="@lang('Enter') @lang('Email')"
                            value="{{ old('email', $teacher?$teacher->user->email:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Batches')</label>
                            <select class="form-control" id="batch" name="batch_ids[]" multiple>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ $teacher && $teacher->batches->contains($batch->id)?'selected':'' }}>{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control">
                                <option value="tetap" @selected($teacher && $teacher->status=='tetap')>Tetap</option>
                                <option value="training" @selected($teacher && $teacher->status=='training')>Training</option>
                                <option value="khidmat" @selected($teacher && $teacher->status=='khidmat')>Khidmat</option>
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
@push('scripts')
    <script>
        // Class definition
        var KTSelect2 = function() {
            // Private functions
            var demos = function() {
                // multi select
                $('#batch').select2({
                    placeholder: 'Pilih Halaqoh',
                });
            }

            // Public functions
            return {
                init: function() {
                    demos();
                }
            };
        }();

        // Initialization
        jQuery(document).ready(function() {
            KTSelect2.init();
        });
    </script>
@endpush