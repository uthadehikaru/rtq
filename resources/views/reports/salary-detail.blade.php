@extends('layouts.guest')
@section('content')
<div class="row">
    <div class="col">

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__body">
                <h3>@lang('Salaries') {{ $salary->name }}</h3>
                <p>Periode {{ $salary->period() }}</p>
                
                @foreach($salary->details as $detail)
                <h5>@lang('Teacher') {{ $detail->teacher->name }}</h5>
                <!--begin: Datatable -->
                <table class="table border" id="html_table" width="100%">
                    <thead>
                        <tr>
                            <th title="">@lang('Schedule')</th>
                            <th title="">@lang('Course')</th>
                            <th title="">@lang('Batch')</th>
                            <th title="">@lang('status')</th>
                            <th title="">@lang('description')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teacherPresents[$detail->teacher_id] as $present)
                            <tr>
                                <td width="25%">{{ $present->schedule->scheduled_at->format('d M Y H:i:s') }}</td>
                                <td width="15%">{{ $present->schedule->batch->course->name }}</td>
                                <td width="15%">
                                    <span class="text-{{ $present->schedule->batch->teacher_id==$present->teacher_id?'success':'warning' }}">
                                        {{ $present->schedule->batch->name }}
                                    </span>
                                </td>
                                <td width="15%">
                                    <span class="text-{{ $present->status=='present'?'primary':'danger' }}">
                                        {{ $present->status }}
                                    </span>
                                </td>
                                <td>{{ $present->status=='present'?$present->attended_at->format('H:i:s'):$present->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection