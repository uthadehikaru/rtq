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
                <div class="table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th>@lang('Tanggal')</th>
                                <th>@lang('Halaqoh')</th>
                                <th>@lang('Pengajar')</th>
                                <th>@lang('Mulai')</th>
                                <th>@lang('Selesai')</th>
                                <th>@lang('Tempat')</th>
                                <th>@lang('Peserta')</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                @php
                                    $teachers = [];
                                    foreach ($schedule->presents->where('type', 'teacher') as $present) {
                                        if ($present->user) {
                                            $teachers[] = $present->user->name;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $schedule->scheduled_at->format('d M Y') }}</td>
                                    <td>{{ $schedule->batch->name }}</td>
                                    <td>{{ implode(', ', $teachers) }}</td>
                                    <td>{{ $schedule->start_at->format('H:i') }}</td>
                                    <td>{{ $schedule->end_at?->format('H:i') ?? '-' }}</td>
                                    <td>{{ $schedule->place }}</td>
                                    <td>{{ $schedule->presents_count }} ({{ $schedule->getSizeType($schedule->presents_count) }})</td>
                                    <td class="text-center">
                                        <a href="{{ route('teacher.schedules.detail', $schedule->id) }}" class="text-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">@lang('Belum ada jadwal yang diinput')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
