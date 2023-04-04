@extends('datatables.datatable')
@section('breadcrumbs')
<a href="{{ route('courses.index') }}" class="kt-subheader__breadcrumbs-link">
Kelas</a>
<span class="kt-subheader__breadcrumbs-separator"></span>
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Halaqoh
</span>
@endsection
@section('buttons')
        <a href="{{ route('courses.index') }}" class="btn btn-warning btn-icon-sm">
            <i class="la la-arrow-left"></i>
            @lang('Back')
        </a>
        <div class="btn-group" role="group">
            <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="action">
                <a href="{{ route('courses.batches.export', $course->id) }}" class="dropdown-item text-success">
                    <i class="la la-download"></i>
                    @lang('Export (.xls)')
                </a>
                <a href="{{ route('courses.batches.create', $course->id) }}" class="dropdown-item text-primary">
                    <i class="la la-plus"></i>
                    @lang('New Batch')
                </a>
            </div>
        </div>
@endsection