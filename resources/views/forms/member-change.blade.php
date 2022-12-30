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
            <form class="kt-form" method="POST" action="{{ route('members.change', $member->id) }}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>@lang('Batch')</label>
                            <select name="batch_id" class="form-control kt-select2" required>
                                <option value="">--- @lang('Select :name',['name'=>__('Batch')]) ---</option>
                                @foreach($batches as $batch)
                                    @if($member->batch() && $batch->id==$member->batch()->id)
                                        @continue
                                    @endif
                                    <option value="{{ $batch->id }}">{{ $batch->course->name }} @lang('Batch') {{ $batch->name }} : {{ $batch->members->count() }} peserta</option>
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
<script type="text/javascript">
jQuery(document).ready(function () {    
    $('.kt-select2').select2({
        placeholder: "@lang('Pilih Halaqoh')"
    });
});
</script>
@endpush