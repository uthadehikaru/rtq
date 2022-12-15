@extends('layouts.app')
@section('content')
<x-validation />
<div class="row">
    <div class="col-lg-6">
        <div class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate">
            <div class="kt-portlet__body">
                <div class="kt-iconbox__body">
                    <div class="kt-iconbox__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path
                                    d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                    fill="#000000" opacity="0.3"></path>
                                <path
                                    d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                    fill="#000000"></path>
                            </g>
                        </svg> </div>
                    <div class="kt-iconbox__desc">
                        <h3 class="kt-iconbox__title">
                            <a class="kt-link" href="#">@lang('Batches')</a>
                        </h3>
                        <div class="kt-iconbox__content">
                            {{ $batches->count() }} @lang('Data')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="kt-portlet kt-iconbox kt-iconbox--warning kt-iconbox--animate">
            <div class="kt-portlet__body">
                <div class="kt-iconbox__body">
                    <div class="kt-iconbox__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path
                                    d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                                    fill="#000000" opacity="0.3"></path>
                                <path
                                    d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z"
                                    fill="#000000"></path>
                                <path
                                    d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                                    fill="#000000"></path>
                            </g>
                        </svg> </div>
                    <div class="kt-iconbox__desc">
                        <h3 class="kt-iconbox__title">
                            <a class="kt-link" href="#">@lang('Schedules')</a>
                        </h3>
                        <div class="kt-iconbox__content">
                            {{ $schedules->count() }} @lang('Data')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                @lang('Mulai Kelas')
                            </h3>
                        </div>
                    </div>
                    <form class="kt-form" action="{{ route('teacher.schedules.create') }}"
                        method="post">
                        @csrf
                        <div class="kt-portlet__body">
                            <div class="alert alert-primary" role="alert">
                                <p>Saat menekan tombol "Mulai", kelas akan otomatis tercatat dimulai saat ini
                                    dan waktu hadir pengajar mengikut waktu mulai kelas.
                                    pastikan memulai kelas sesuai jadwal yang telah ditentukan</p>
                            </div>
                            <div class="kt-section kt-section--first">
                                <div class="form-group">
                                    <label>@lang('Batch')</label>
                                    <select class="form-control" name="batch_id" required>
                                        <option value="">@lang('Select Batch')</option>
                                        @foreach($batches as $batch)
                                            <option value="{{ $batch->id }}">{{ $batch->course->name }}
                                                {{ $batch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Guru Pengganti</label>
                                    <div class="col-9 col-form-label">
                                        <div class="radio-inline">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" name="badal" value="1" required>
                                                <span></span>Ya</label>
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" name="badal" checked="checked" value="0" required>
                                                <span></span>Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary">Mulai</button>
                                <button type="reset" class="btn btn-secondary">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @lang('Jadwal Terbaru')
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
                                        {{ $schedule->scheduled_at->format('d M Y H:i') }}
                                        @if($schedule->teacher_id)
                                            digantikan oleh {{ $schedule->teacher->name }}
                                        @endif
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
</div>
@endsection
