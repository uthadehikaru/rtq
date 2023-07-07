@extends('layouts.app')
@section('content')
<x-message />
<div class="row">
    <div class="col-lg-4">
        <div class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate">
            <div class="kt-portlet__body">
                <div class="kt-iconbox__body">
                    <div class="kt-iconbox__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                            </g>
                        </svg> </div>
                    <div class="kt-iconbox__desc">
                        <h3 class="kt-iconbox__title">
                            <a class="kt-link text-primary" href="{{ route('payments.index') }}">@lang('Payments')</a>
                        </h3>
                        <div class="kt-iconbox__content">
                            {{ $unconfirmed_payments }} @lang('Pembayaran belum dikonfirmasi')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="kt-portlet kt-iconbox kt-iconbox--warning kt-iconbox--animate">
            <div class="kt-portlet__body">
                <div class="kt-iconbox__body">
                    <div class="kt-iconbox__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"></path>
                                <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000"></path>
                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"></path>
                            </g>
                        </svg> </div>
                    <div class="kt-iconbox__desc">
                        <h3 class="kt-iconbox__title">
                            <a class="kt-link text-primary" href="{{ route('courses.index') }}">@lang('Batches')</a>
                        </h3>
                        <div class="kt-iconbox__content">
                            {{ $batches }} @lang('Halaqoh')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="kt-portlet kt-iconbox kt-iconbox--primary kt-iconbox--animate">
            <div class="kt-portlet__body">
                <div class="kt-iconbox__body">
                    <div class="kt-iconbox__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                            </g>
                        </svg> </div>
                    <div class="kt-iconbox__desc">
                        <h3 class="kt-iconbox__title">
                            <a class="kt-link text-primary" href="{{ route('members.index') }}">@lang('Peserta Tahsin')</a>
                        </h3>
                        <div class="kt-iconbox__content">
                            {{ $members }} @lang('Peserta Aktif')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget14">
                <div class="kt-widget14__header kt-margin-b-30">
                    <h3 class="kt-widget14__title">
                        Pembayaran 12 periode terakhir
                    </h3>
                    <span class="kt-widget14__desc">
                    <span class="kt-bg-success px-2 text-white">lunas</span>
                    <span class="kt-bg-primary px-2 text-white">baru</span>
                    </span>
                </div>
                <div class="kt-widget14__chart" style="height:120px;">
                    <canvas id="kt_chart_period_payment"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget14">
                <div class="kt-widget14__header">
                    <h3 class="kt-widget14__title">
                        Presentase Peserta Halaqoh
                    </h3>
                    <span class="kt-widget14__desc">
                        {{ config('app.name') }}
                    </span>
                </div>
                <div class="kt-widget14__content">
                    <div class="kt-widget14__chart">
                        <div class="kt-widget14__stat">{{ $members }}</div>
                        <canvas id="kt_chart_batch_member" style="height: 140px; width: 140px;"></canvas>
                    </div>
                    <div class="kt-widget14__legends">
                        @foreach($types as $color=>$type)
                        <div class="kt-widget14__legend">
                            <span class="kt-widget14__bullet kt-bg-{{$color}}"></span>
                            <span class="kt-widget14__stats">{{ $courses[$type] }} {{ $type }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!--end:: Widgets/Profit Share-->
    </div>
    <div class="col-md-3">
        <div class="card card-custom bg-info card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body my-4">
                <a href="{{ route('biodata.index') }}" class="card-title font-weight-bolder text-white font-size-h6 mb-4 text-hover-state-dark d-block">
                    Verifikasi Biodata
                </a>

                <div class="font-weight-bold text-white font-size-sm">
                    <span class="text-dark-75 font-weight-bolder font-size-h2 mr-2">{{ $biodata_verified }}</span>dari {{ $biodata_count }} telah terverifikasi
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom bg-success card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body my-4">
                <a href="{{ route('registrations.index') }}" class="card-title font-weight-bolder text-white font-size-h6 mb-4 text-hover-state-dark d-block">
                    Waiting List
                </a>

                <div class="font-weight-bold text-white font-size-sm">
                    <span class="text-dark-75 font-weight-bolder font-size-h2 mr-2">{{ $waitinglist }}</span>calon peserta
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom bg-dark card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body my-4">
                <a href="{{ route('violations.index') }}" class="card-title font-weight-bolder text-white font-size-h6 mb-4 text-hover-state-dark d-block">
                    Pelanggaran
                </a>

                <div class="font-weight-bold text-white font-size-sm">
                    <span class="text-dark-75 font-weight-bolder font-size-h2 mr-2">{{ $violation_count }}</span>belum diselesaikan
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
jQuery(document).ready(function() {
    
    var chartContainer = KTUtil.getByID('kt_chart_period_payment');

    if (!chartContainer) {
        return;
    }

    var chartData = {
        labels: [
                @foreach ($periods as $period)
                    "{{ $period->name }}",
                @endforeach],
        datasets: [{
            //label: 'Dataset 1',
            backgroundColor: KTApp.getStateColor('success'),
            data: [
                @foreach ($periods as $period)
                    "{{ $period->lunas_count }}",
                @endforeach
            ]
        }, {
            //label: 'Dataset 2',
            backgroundColor: KTApp.getStateColor('primary'),
            data: [
                @foreach ($periods as $period)
                    "{{ $period->new_count }}",
                @endforeach
            ]
        }]
    };

    var chart = new Chart(chartContainer, {
        type: 'bar',
        data: chartData,
        options: {
            title: {
                display: false,
            },
            tooltips: {
                intersect: false,
                mode: 'nearest',
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            barRadius: 4,
            scales: {
                xAxes: [{
                    display: false,
                    gridLines: false,
                    stacked: true
                }],
                yAxes: [{
                    display: false,
                    stacked: true,
                    gridLines: false
                }]
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            }
        }
    });

    var randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    @foreach ($courses as $type=>$total)
                        {{ round($total/$members*100) }},
                    @endforeach
                ],
                backgroundColor: [
                    KTApp.getStateColor('success'),
                    KTApp.getStateColor('danger'),
                    KTApp.getStateColor('primary')
                ]
            }],
            labels: [
                'Tahsin Anak (%)',
                'Tahsin Dewasa (%)',
                'Tahsin Balita (%)'
            ]
        },
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Technology'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10, 
                caretPadding: 0,
                displayColors: false,
                backgroundColor: KTApp.getStateColor('brand'),
                titleFontColor: '#ffffff', 
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            }
        }
    };

    var ctx = KTUtil.getByID('kt_chart_batch_member').getContext('2d');
    var myDoughnut = new Chart(ctx, config);
});
</script>
@endpush