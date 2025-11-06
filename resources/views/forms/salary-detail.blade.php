@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('salaries.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Salaries")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<a href="{{ route('salaries.details.index', $detail->salary_id) }}" class="kt-subheader__breadcrumbs-link">
@lang("Details")</a>
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
                            <a href="{{ route('salaries.details.index', $detail->salary_id) }}" class="btn btn-warning btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            @if($detail)
            <form class="kt-form" method="POST" action="{{ route('salaries.details.update', [$detail->salary_id, $detail->id]) }}">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="kt-form" method="POST" action="{{ route('salaries.details.store', $detail->salary_id) }}">
            @endif
                @csrf
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <h4 class="kt-section__title">@lang('Nominal per Kelas')</h4>
                        @foreach (['tahsin_anak','tahsin_dewasa','tahsin_balita','talaqqi_jamai'] as $type)
                        <div class="form-group">
                            <label>{{ Str::title($type) }}</label>
                            <input type="number" name="{{ $type }}[amount]" class="form-control"
                            value="{{ old($type.'.amount', isset($detail) && isset($detail->summary[$type]['amount']) ? $detail->summary[$type]['amount'] : 0) }}"
                            required>
                        </div>
                        @endforeach
                        
                        <h4 class="kt-section__title mt-4">@lang('Total Semua Kelas')</h4>
                        <div class="form-group">
                            <label>@lang('total')</label>
                            <input type="number" name="base" class="form-control"
                            value="{{ old('base', isset($detail) && isset($detail->summary['base']) ? $detail->summary['base'] : 0) }}"
                            disabled>
                        </div>
                        
                        <h4 class="kt-section__title mt-4">@lang('Tunjangan dan Potongan')</h4>
                        <div class="form-group">
                            <label>@lang('Transportasi')</label>
                            <input type="number" name="transportasi" class="form-control"
                            value="{{ old('transportasi', isset($detail) && isset($detail->summary['transportasi']) ? $detail->summary['transportasi'] : 0) }}"
                            required>
                        </div>
                        
                        <div class="form-group">
                            <label>@lang('Potongan Telat')</label>
                            <input type="number" name="potongan_telat" class="form-control"
                            value="{{ old('potongan_telat', isset($detail) && isset($detail->summary['potongan_telat']) ? $detail->summary['potongan_telat'] : 0) }}"
                            required>
                        </div>
                        
                        <div class="form-group">
                            <label>@lang('Operan Santri')</label>
                            <input type="number" name="nominal_oper" class="form-control"
                            value="{{ old('nominal_oper', isset($detail) && isset($detail->summary['nominal_oper']) ? $detail->summary['nominal_oper'] : 0) }}"
                            required>
                        </div>
                        
                        <div class="form-group">
                            <label>@lang('Tunjangan')</label>
                            <input type="number" name="tunjangan" class="form-control"
                            value="{{ old('tunjangan', isset($detail) && isset($detail->summary['tunjangan']) ? $detail->summary['tunjangan'] : 0) }}"
                            required>
                        </div>
                        
                        <div class="form-group">
                            <label>@lang('Total Keseluruhan')</label>
                            <input type="number" name="amount" class="form-control"
                            value="{{ old('amount', isset($detail) ? $detail->amount : 0) }}"
                            disabled>
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