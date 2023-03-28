@extends('layouts.app')
@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $(document).on("click", ".delete", function () {
                if (confirm("@lang('Are you sure?')")) {
                    var id = $(this).data('id');
                    var url =
                        "{{ route('schedules.presents.index', $schedule->id) }}";
                    var dltUrl = url + "/" + id;
                    $.ajax({
                        url: dltUrl,
                        type: "DELETE",
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (dataResult) {
                            if (dataResult.statusCode == 200) {
                                alert('@lang('
                                    Deleted Successfully ')');
                                location.reload(true);
                            }
                        }
                    });
                }
            });
        });

    </script>
@endpush
@section('breadcrumbs')
<a href="{{ route('schedules.index') }}" class="kt-subheader__breadcrumbs-link">
    @lang("Schedule")</a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
            @lang('Presents')
        </span>
        @endsection
        @section('content')
        <x-message />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon2-users"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        {{ $total }} @lang('Presents')<br />@lang('Schedule') {{ $schedule->batch->name }}
                        {{ $schedule->scheduled_at->format('d M Y') }}
                        {{ $schedule->start_at->format('H:i') }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            @role('administrator')
                                <a href="{{ route('schedules.presents.create', ['schedule'=>$schedule->id,'type'=>'teacher']) }}"
                                    class="btn btn-primary btn-sm mt-2">
                                    @lang('+ Pengajar')
                                </a>
                                <a href="{{ route('schedules.presents.create', ['schedule'=>$schedule->id,'type'=>'member']) }}"
                                    class="btn btn-primary btn-sm mt-2">
                                    @lang('+ Anggota')
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin: Datatable -->
                <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th title="Field #1">@lang('#')</th>
                            <th title="Field #2">@lang('Name')</th>
                            <th title="Field #4">@lang('Status')</th>
                            <th title="Field #4">@lang('Keterangan')</th>
                            <th title="Field #2">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presents as $present)
                            <tr>
                                <td>
                                    @if($present->type=='member')
                                        <a href="{{ thumbnail($present->user->member->profile_picture) }}"
                                            data-lightbox="{{ $present->id }}"><img
                                                src="{{ thumbnail($present->user->member->profile_picture) }}"
                                                class="img-fluid" /></a>
                                    @elseif($present->photo)
                                        <a href="{{ asset('storage/'.$present->photo) }}"
                                            data-lightbox="{{ $present->id }}"><img
                                                src="{{ asset('storage/'.$present->photo) }}"
                                                class="img-fluid" /></a>
                                    @endif
                                </td>
                                <td>{{ $present->user->name }} - {{ $present->type }}</td>
                                <td>@lang('app.present.status.'.$present->status)
                                    {{ $present->status=='present' && $present->attended_at?__('at :time', ['time'=>$present->attended_at?->format('H:i')]):'' }}
                                </td>
                                <td>
                                    {{ $present->type=='teacher' && $present->is_badal?'(Badal)':'' }}
                                    {{ $present->type=='member' && $present->is_transfer?'(Operan)':'' }}
                                    {{ $present->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="action" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="action">
                                            <a href="{{ route('schedules.presents.edit', [$schedule->id,$present->id]) }}"
                                                class="dropdown-item text-warning">
                                                <i class="la la-edit"></i> @lang('Edit')
                                            </a>
                                            <button type="button" class="dropdown-item" onclick="addIqob({{ $present->user_id }})">
                                                <i class="la la-money"></i> @lang('Iqob')
                                            </button>
                                            @if($present->type=='member')
                                                @foreach($statuses as $status)
                                                    @if($status == $present->status)
                                                        @continue
                                                    @endif
                                                    <a href="{{ route('schedules.presents.change', [$schedule->id,$present->id,$status]) }}"
                                                        class="dropdown-item text-primary">
                                                        <i class="la la-check"></i> @lang('app.present.status.'.$status)
                                                    </a>
                                                @endforeach
                                            @endif
                                            <a href="javascript:;" class="dropdown-item text-danger delete"
                                                data-id="{{ $present->id }}">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table></div>

                <!--end: Datatable -->
            </div>
        </div>
        @endsection
        @push('styles')
                <link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
        @endpush
        @push('scripts')
	        <script src="{{ asset('datatables/datatables.min.js') }}" type="text/javascript"></script>
            <!-- Modal -->
            <div class="modal fade" id="iqobModal" tabindex="-1" aria-labelledby="iqobModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('violations.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="iqobModalLabel">Input Iqob</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="redirect" value="{{ url()->current() }}" />
                            <input type="hidden" name="user_id" id="user_id" value="0" />
                            <label class="">Tanggal Pelanggaran</label>
                            <input type="date" class="form-control" id="violated_date" name="violated_date" value="{{ $present->created_at->format('Y-m-d') }}" required />
                            <label class="">Deskripsi</label>
                            <input type="string" class="form-control" id="description" name="description" placeholder="masukkan alasan" required />
                            <label class="mt-2">Nominal</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="0" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                function addIqob(user_id)
                {
                    $('#description').val('');
                    $('#amount').val(0);
                    $('#user_id').val(user_id);
                    $('#iqobModal').modal('show');
                }
                $(document).ready(function () {
                    $('.table').DataTable();
                });
            </script>
        @endpush
