@extends('datatables.datatable')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Kelas
</span>
@endsection
@section('buttons')
    <a href="{{ route('courses.create') }}" class="btn btn-primary btn-icon-sm">
        <i class="la la-plus"></i>
        @lang('New Course')
    </a>
@endsection