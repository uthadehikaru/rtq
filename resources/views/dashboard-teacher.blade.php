    @extends('layouts.app')
@section('content')
<x-message />
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                @lang('Absen Kelas')
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <a href="{{ route('teacher.schedules.create') }}" class="btn btn-primary">FORM ABSEN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                @lang('Rekapitulasi') {{ date('F Y') }}
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <p>Total : {{ $schedules->count() }}</p>
                        @foreach($presents as $status=>$list)
                        <p>@lang('app.present.status.'.$status) : {{ $list->count() }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @lang('Jadwal') {{ date('F Y') }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">

                </div>
            </div>
            <div class="kt-portlet__body">

                @if($schedules)
                    <div class="kt-timeline-v2">
                        <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
                            @foreach($schedules as $schedule)
                                <div class="kt-timeline-v2__item">
                                    <a href="{{ route('teacher.schedules.detail', $schedule->id) }}"
                                        class="kt-timeline-v2__item-time btn btn-sm btn-icon btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <div class="kt-timeline-v2__item-cricle">
                                        <i class="fa fa-genderless kt-font-primary"></i>
                                    </div>
                                    <div class="kt-timeline-v2__item-text  kt-padding-top-5">
                                        Jadwal Halaqoh {{ $schedule->batch->name }} pada
                                        {{ $schedule->scheduled_at->format('d M Y') }}
                                        {{ $schedule->start_at->format('H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-center">@lang('Belum ada jadwal yang diinput')</p>
                @endif

            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <x-violation-card :violations="$violations" />
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