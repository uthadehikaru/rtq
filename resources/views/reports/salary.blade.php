@extends('layouts.app')
@section('breadcrumbs')
<a href="{{ route('salaries.index') }}" class="kt-subheader__breadcrumbs-link">
@lang("Salaries")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<a href="{{ route('salaries.details.index', $salary->id) }}" class="kt-subheader__breadcrumbs-link">
@lang("Details")</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    {{ $title }}
</span>
@endsection
@section('content')
<div class="row">
    <div class="col">

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
                            <a href="{{ route('salaries.details.index', $salary->id) }}" class="btn btn-warning btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="kt-portlet__body">
                <h3>@lang('Salaries') {{ $salary->name }}</h3>
                <p>{{ $salary->period() }}</p>
                <!--begin: Datatable -->
                <table class="table" id="html_table" width="100%">
                    <thead>
                        <tr>
                            <th title="">@lang('Teacher')</th>
                            <th title="">@lang('Amount')</th>
                            <th title="">@lang('Present')</th>
                            <th title="">@lang('Late')</th>
                            <th title="">@lang('Absent')</th>
                            <th title="">@lang('Permit')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salary->details as $detail)
                            <tr>
                                <td>{{ $detail->teacher->name }}</td>
                                <td>{{ $detail->amount }}</td>
                                <td>{{ $detail->summary['present'] }}</td>
                                <td>{{ $detail->summary['late'] }}</td>
                                <td>{{ $detail->summary['absent'] }}</td>
                                <td>{{ $detail->summary['permit'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <a href="{{ route('salaries.report', $salary->id) }}?detail" class="btn btn-primary">@lang('Detail')</a>
                </div>
            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection