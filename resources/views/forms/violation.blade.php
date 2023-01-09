@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('violations.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Pelanggaran") </a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $violation?'Ubah':'Tambah' }}
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
                        Form Pelanggaran
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('violations.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($violation)
            <form class="kt-form" method="POST" action="{{ route('violations.update', $violation->id) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('violations.store') }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <input type="hidden" name="type" value="{{ $type }}" />
                        <div class="form-group">
                            <label>@lang('Tanggal')</label>
                            <input type="date" name="violated_date" class="form-control" placeholder="@lang('Tanggal Pelanggaran')"
                            value="{{ old('violated_date', $violation?$violation->violated_date?->format('Y-m-d'):date('Y-m-d')) }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nama')</label>
                            <select name="user_id" class="form-control kt-select2" required>
                                <option value="">--- Pilih ---</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($violation && $user->id==$violation->user_id)>{{ $user->name }}</option>                                        
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Deskripsi')</label>
                            <input type="text" name="description" class="form-control" placeholder="@lang('Detail Pelanggaran')"
                            value="{{ old('description', $violation?$violation->description:'') }}"
                            required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Iqob')</label>
                            <input type="number" name="amount" class="form-control" placeholder="@lang('Jumlah Iqob')"
                            value="{{ old('amount', $violation?$violation->amount:0) }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Diselesaikan pada')</label>
                            <input type="date" name="paid_at" class="form-control" placeholder="@lang('Tanggal penyelesaian')"
                            value="{{ old('paid_at', $violation?$violation->paid_at?->format('Y-m-d'):'') }}">
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
        placeholder: "@lang('Pilih '.$type)"
    });
});
</script>
@endpush