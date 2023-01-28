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
                @if(!isset($teacherPresents[$detail->user_id]))
                @continue
                @endif
                <h5>@lang('Teacher') {{ $detail->user->name }}</h5>
                <p>Total : <x-money :amount="$detail->amount" /></p>
                <table class="table table-striped">
                    <tr>
                        <td>Jadwal : {{ $detail->summary['own'] }} (Badal : {{ $detail->summary['switch'] }})</td>
                        <td>Pokok : @money($detail->summary['base'])</td>
                    </tr>
                    <tr>
                        <td>Hadir : {{ $detail->summary['present'] }} | Tidak Hadir : {{ $detail->summary['absent'] }}</td>
                        <td>Transportasi : @money($detail->summary['transportasi'])</td>
                    </tr>
                    <tr>
                        <td>
                            Telat : {{ $detail->summary['late'] }}
                            (Konfirmasi : {{ $detail->summary['late_with_confirm'] }})
                        </td>
                        <td>Potongan Telat : @money($detail->summary['potongan_telat'])</td>
                    </tr>
                    <tr>
                        <td>Total Oper : {{ $detail->summary['oper_santri'] }}</td>
                        <td>Operan Santri : @money($detail->summary['nominal_oper'])</td>
                    </tr>
                    <tr>
                        <td>Izin : {{ $detail->summary['permit'] }}</td>
                        <td>Tunjangan : @money($detail->summary['tunjangan'])</td>
                    </tr>
                </table>
                <!--begin: Datatable -->
                <table class="table border" id="html_table" width="100%">
                    <thead>
                        <tr>
                            <th title="">@lang('Schedule')</th>
                            <th title="">@lang('Course')</th>
                            <th title="">@lang('Batch')</th>
                            <th title="">@lang('status')</th>
                            <th title="">@lang('Badal')</th>
                            <th title="">@lang('description')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teacherPresents[$detail->user_id] as $present)
                            <tr>
                                <td width="25%">{{ $present->schedule->scheduled_at->format('d M Y') }} {{ $present->schedule->start_at?->format('H:i') }}</td>
                                <td width="15%">{{ $present->schedule->batch->course->name }}</td>
                                <td width="15%">
                                    <span class="text-success">
                                        {{ $present->schedule->batch->name }}
                                    </span>
                                </td>
                                <td width="15%">
                                    <span class="text-{{ $present->status=='present'?'primary':'danger' }}">
                                        @lang('app.present.status.'.$present->status)
                                        @if($present->status=='present')
                                            pada {{ $present->attended_at?->format('H:i') }}
                                            @php $late = $present->attended_at->diffInMinutes($present->schedule->start_at); @endphp
                                            {{ $late>$detail->summary['maks_waktu_telat']?'(Telat '.$late.' menit)':'' }}
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $present->is_badal?'Ya':'Tidak'}}</td>
                                <td>
                                    {{ $present->description }}
                                </td>
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