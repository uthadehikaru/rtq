@extends('layouts.app')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Jadwal 
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
                        Jadwal {{ $schedule->batch->name }} - {{ $schedule->scheduled_at->format('d M Y H:i') }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ route('schedules.index') }}" class="btn btn-default btn-icon-sm">
                                <i class="la la-arrow-left"></i>
                                @lang('Back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            <form class="kt-form" method="POST" action="{{ route('schedules.update', $schedule->id) }}">
                <input type="hidden" name="_method" value="PUT" />
                @csrf
                <div class="kt-portlet__body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule->presents as $present)
                                <tr>
                                    <td>{{ $present->name() }}</td>
                                    <td>
                                        <select class="form-control" name="status[{{$present->id}}]">
                                            @foreach($statuses as $status)
                                            <option value="{{ $status }}" @selected($status==$present->status)>@lang('app.present.status.'.$status)</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control"
                                    placeholder="Masukkan keterangan"
                                    name="description[{{$present->id}}]"  value="{{ $present->description }}" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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