@extends('layouts.app')
@push('scripts')
    <script type="text/javascript">
        var KTDatatable = function () {
            // Private functions

            // table initializer
            var table = function () {

                var datatable = $('.kt-datatable').KTDatatable({
                    data: {
                        saveState: {
                            cookie: false
                        },
                    },
                    search: {
                        input: $('#generalSearch'),
                    },
                });
                    
                $('#kt_datatable_search_type').on('change', function() {
                datatable.search($(this).val().toLowerCase(), 'Tipe');
                });
                    
                $('#kt_datatable_search_batch').on('change', function() {
                datatable.search($(this).val().toLowerCase(), 'Halaqoh');
                });

                $('#kt_datatable_search_batch, #kt_datatable_search_type').selectpicker();

            };

            return {
                // Public functions
                init: function () {
                    // init dmeo
                    table();
                },
            };
        }();

        jQuery(document).ready(function () {
            KTDatatable.init();
        });

    </script>
@endpush
@section('breadcrumbs')
<a href="{{ route('schedules.index') }}" class="kt-subheader__breadcrumbs-link">
    @lang("Schedule")</a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
            @lang('Reports')
        </span>
        @endsection
        @section('content')
        @if(session()->has('message'))
            <x-alert type="success">{{ session()->get('message') }}</x-alert>
        @endif
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon2-users"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Laporan
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            @role('administrator')
                                <a href="{{ route('schedules.export') }}" class="btn btn-primary btn-icon-sm">
                                    <i class="la la-download"></i>
                                    Export (.xls)
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin: Search Form -->
                <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="@lang('Search')..."
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="d-flex align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">Halaqoh:</label>
                                        <div class="dropdown bootstrap-select form-control">
                                            <select class="form-control"
                                                id="kt_datatable_search_batch" tabindex="null">
                                                <option value="">Semua</option>
                                                @foreach ($batches as $batch)
                                                    <option value="{{ $batch->name }}">{{ $batch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="d-flex align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">Tipe:</label>
                                        <div class="dropdown bootstrap-select form-control">
                                            <select class="form-control"
                                                id="kt_datatable_search_type" tabindex="null">
                                                <option value="">Semua</option>
                                                <option value="Pengajar">Pengajar</option>
                                                <option value="Anggota">Anggota</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end: Search Form -->
            </div>
            <div class="kt-portlet__body kt-portlet__body--fit">

                <!--begin: Datatable -->
                <table class="kt-datatable" id="html_table" width="100%">
                    <thead>
                        <tr>
                            <th title="Field #1">@lang('Scheduled at')</th>
                            <th title="Field #2">@lang('Halaqoh')</th>
                            <th title="Field #2">@lang('Name')</th>
                            <th title="Field #2">@lang('Tipe')</th>
                            <th title="Field #4">@lang('Status')</th>
                            <th title="Field #5">@lang('Keterangan')</th>
                            <th title="Field #6">@lang('Aksi')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presents as $present)
                            <tr>
                                <td>{{ $present->schedule->scheduled_at->format('d/m/y h:i') }}
                                </td>
                                <td>{{ $present->schedule->batch->name }}</td>
                                <td>{{ $present->user->name }}</td>
                                <td>@lang('app.present.type.'.$present->type)</td>
                                <td>@lang('app.present.status.'.$present->status)
                                    {{ $present->status=='present' && $present->attended_at?__('at :time', ['time'=>$present->attended_at?->format('H:i')]):'' }}
                                </td>
                                <td>
                                    {{ $present->type=='teacher' && $present->is_badal?'(Badal)':'' }}
                                    {{ $present->description }}</td>
                                <td>
                                    <a href="{{ route('schedules.presents.edit', [$present->schedule_id,$present->id]) }}?redirect={{ url()->current() }}" class="text-warning">
                                    <i class="la la-edit"></i> @lang('Edit')
                                    </a>
                                    @foreach($statuses as $status)
                                    @if ($status == $present->status)
                                        @continue
                                    @endif
                                    <a href="{{ route('schedules.presents.change', [$present->schedule_id,$present->id,$status]) }}" class="text-primary">
                                        <i class="la la-check"></i> @lang('app.present.status.'.$status)
                                    </a>
                                    @endforeach
                                    <a href="javascript:;" class="text-danger delete" data-id="{{ $present->id }}">
                                        <i class="la la-trash"></i> @lang('Delete')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
        @endsection
