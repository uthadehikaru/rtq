@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('courses.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Course") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<a href="{{ route('courses.batches.index', $course->id) }}" class="kt-subheader__breadcrumbs-link">
@lang("Batch") </a>
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
                            <a href="{{ route('courses.batches.index', $course->id) }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($batch)
            <form class="kt-form" method="POST" action="{{ route('courses.batches.update', [$course->id, $batch->id]) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('courses.batches.store', $course->id) }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Kode')</label>
                            <input type="text" name="code" class="form-control" placeholder="@lang('Masukkan kode kelas')"
                            value="{{ old('code', $batch?$batch->code:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('Enter Batch Name')"
                            value="{{ old('name', $batch?$batch->name:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <input type="text" name="description" class="form-control" placeholder="@lang('Enter Description')"
                            value="{{ old('description', $batch?$batch->description:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Jam Mulai')</label>
                            <input type="time" name="start_time" class="form-control"
                            value="{{ old('start_time', $batch?$batch->start_time?->format('H:i:s'):'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Tempat')</label>
                            <input type="text" name="place" class="form-control" placeholder="@lang('Nama Tempat')"
                            value="{{ old('place', $batch?$batch->place:'') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label>@lang('Pengajar')</label>
                            <select class="form-control" id="teacher" name="teacher_ids[]" multiple>
                                @foreach ($teachers as $id=>$name)
                                    <option value="{{ $id }}" {{ $batch && $batch->teachers->contains($id)?'selected':'' }}>{{ $name }}</option>
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
@push('scripts')
    <script>
        // Class definition
        var KTSelect2 = function() {
            // Private functions
            var demos = function() {
                // multi select
                $('#teacher').select2({
                    placeholder: 'Pilih Pengajar',
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