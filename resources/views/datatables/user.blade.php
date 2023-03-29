@extends('datatables.datatable')
@section('buttons')
        <div class="btn-group" role="group">
            <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="action">
                <a class="dropdown-item" href="{{ route('admin.users.check-roles') }}">
                    <i class="la la-cog"></i> Check Roles
                </a>
            </div>
        </div>
@endsection