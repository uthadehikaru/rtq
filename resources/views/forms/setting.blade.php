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
                            <label>Banner</label>
                            <div class="dropzone dropzone-default" id="banner">
                                <div class="dropzone-msg dz-message needsclick">
                                    <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                                    <span class="dropzone-msg-desc">File yang diperbolehkan maks. 2 mb berekstensi .jpg/.png</span>
                                </div>
                            </div>
                        </div>
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
                        <div class="form-group">
                            <label>Instagram</label>
                            <input type="text" name="instagram" class="form-control" value="{{ $homepage['instagram']->payload }}">
                        </div>
                        <div class="form-group">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $homepage['whatsapp']->payload }}">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>ID Card</label>
                            <div class="dropzone dropzone-default" id="idcard">
                                <div class="dropzone-msg dz-message needsclick">
                                    <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                                    <span class="dropzone-msg-desc">File yang diperbolehkan maks. 2 mb berekstensi .jpg/.png</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control" value="{{ $general['latitude']->payload }}">
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="{{ $general['longitude']->payload }}">
                        </div>
                    </div>
                </div>
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
KTUtil.ready(function() {
    $('#banner').dropzone({
            url: "{{ route('dropzone') }}",
            paramName: "file",
            maxFiles: 1,
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            init: function() {
                @if(isset($homepage['banner']))
                var thisDropzone = this;
                var mockFile = { name: 'Banner', size: 12345, type: 'image/jpeg' };
                thisDropzone.emit("addedfile", mockFile);
                thisDropzone.emit("thumbnail", mockFile, "{{ $homepage['banner']->payload }}")
                thisDropzone.emit("success", mockFile);
                @endif
                this.on("maxfilesexceeded", function(file){
                    this.removeFile(file);
                    alert("No more files please!");
                });
            }, 
            sending: function(file, xhr, formData) {
                formData.append("_token", "{{ csrf_token() }}");
            formData.append("name", "banner");
            },
            accept: function(file, done) {
                done();
            },
            removedfile: function(file) {
                var name = file.name; 
                
                $.ajax({
                    type: 'POST',
                    url: '{{ route('dropzone') }}',
                    data: {name: 'banner', _token: '{{ csrf_token() }}', delete: true},
                    sucess: function(data){
                        console.log('success: ' + data);
                    }
                });
                var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            }
        });

    $('#idcard').dropzone({
        url: "{{ route('dropzone') }}",
        paramName: "file",
        maxFiles: 1,
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        init: function() {
            @if($general['idcard'])
            var thisDropzone = this;
            var mockFile = { name: 'IDCard', size: 12345, type: 'image/jpeg' };
            thisDropzone.emit("addedfile", mockFile);
            thisDropzone.emit("thumbnail", mockFile, "{{ $general['idcard']->payload }}")
            thisDropzone.emit("success", mockFile);
            @endif
            this.on("maxfilesexceeded", function(file){
                this.removeFile(file);
                alert("No more files please!");
            });
        }, 
        sending: function(file, xhr, formData) {
            formData.append("_token", "{{ csrf_token() }}")
            formData.append("name", "idcard");
        },
        accept: function(file, done) {
            done();
        },
        removedfile: function(file) {
            var name = file.name; 
            
            $.ajax({
                type: 'POST',
                url: '{{ route('dropzone') }}',
                data: {name: 'idcard', _token: '{{ csrf_token() }}', delete: true},
                sucess: function(data){
                    console.log('success: ' + data);
                }
            });
            var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        }
    });
});
</script>
@endpush