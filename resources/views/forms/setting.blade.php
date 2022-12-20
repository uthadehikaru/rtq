@extends('layouts.app')
@section('breadcrumbs')
<span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
    Pengaturan
</span>
@endsection
@section('content')
<x-message />
<form class="kt-form" method="POST"
    action="{{ route('settings.store') }}">
    @csrf
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#homepage">Halaman Depan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#general">Umum</a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="homepage" role="tabpanel" aria-labelledby="homepage">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>Tagline</label>
                            <input type="text" name="tagline" class="form-control" value="{{ $homepage['tagline']->payload }}">
                        </div>
                        <div class="form-group">
                            <label>Tentang Kami</label>
                            <textarea name="about" id="about" class="form-control">
                            {{ $homepage['about']->payload }}
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general">General</div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                <button type="reset" class="btn btn-secondary">@lang('Cancel')</button>
            </div>
        </div>
    </div>

</form>
@endsection
@push('scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}?v=4.1"></script>
<script>
CKEDITOR.replace( 'about' , {
    filebrowserUploadUrl: "{{ route('upload', ['_token' => csrf_token() ]) }}",
    filebrowserUploadMethod: 'form',
});
</script>
@endpush