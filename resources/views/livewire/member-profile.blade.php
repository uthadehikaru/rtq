<div>
    <p><img src="{{ $profile_picture }}" id="profilepicture" class="img-fluid" width="200" /></p>
    <a class="btn btn-primary btn-sm text-white file-btn">
        <span>Upload</span>
        <input type="file" id="upload" value="Choose a file" accept="image/*" />
    </a>
    <button type="button" class="btn btn-warning btn-sm btn-icon-sm rotate"><i class="fas fa-sync-alt"></i> Rotate</button>    
    <button type="button" class="btn btn-success btn-sm btn-icon-sm save"><i class="fas fa-save"></i> Update Image</button>
    
    <div class="spinner-border" wire:loading.delay>
    <span class="sr-only">Loading...</span>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('croppie/croppie.css') }}" />
<style>
    
.file-btn {
    position: relative;
}
.file-btn input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
}
</style>
@endpush
@push('scripts')
<script src="{{ asset('croppie/croppie.min.js') }}"></script>
<script>
    var vEl = document.getElementById('profilepicture'),
    profilepicture = new Croppie(vEl, {
        viewport: {
            width: 270,
            height: 270,
        },
        boundary: {
            width: 300,
            height: 300
        },
        enableOrientation: true,
		enableExif: true
    });
    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                profilepicture.bind({
                    url: e.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            
            reader.readAsDataURL(input.files[0]);
        }
        else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }
    $(document).ready(function() {
        profilepicture.setZoom(0);
        $('#upload').on('change', function () { readFile(this); });
        $('.rotate').on('click', function(ev) {
            profilepicture.rotate(-90);
        });
    });
    $('.save').on('click', function(ev) {
        profilepicture.result({
            type: 'base64',
        }).then(function (resp) {
            @this.set('profile_picture', resp);
        });
    });
    Livewire.on('refresh',function(){
        window.location.reload();
    })
</script>
@endpush
