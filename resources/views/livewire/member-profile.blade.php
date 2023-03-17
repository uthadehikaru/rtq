<div>
    @if($profile_picture)
    <p><img src="{{ $profile_picture }}" id="profilepicture" class="img-fluid" width="200" /></p>
    <button type="button" class="btn btn-success btn-icon-sm save"><i class="fas fa-save"></i> Update Image</button>
    <button type="button" class="btn btn-warning btn-icon-sm rotate"><i class="fas fa-sync-alt"></i> Rotate</button>
    <div class="spinner-border" wire:loading.delay>
    <span class="sr-only">Loading...</span>
    </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('croppie/croppie.css') }}" />
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
        enableOrientation: true
    });
    $('.rotate').on('click', function(ev) {
        profilepicture.rotate(-90);
    });
    $('.save').on('click', function(ev) {
        profilepicture.result({
            type: 'base64',
        }).then(function (resp) {
            @this.set('profile_picture', resp);
        });
    });
</script>
@endpush
