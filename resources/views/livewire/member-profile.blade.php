<div>
    @if($member)
    <p><img src="{{ $profile_picture }}" class="img-fluid" width="200" /></p>
    <button wire:click="rotate" type="button" class="btn btn-warning btn-icon-sm"><i class="fas fa-sync-alt"></i> Rotate</button>
    <div class="spinner-border" wire:loading.delay>
    <span class="sr-only">Loading...</span>
    </div>
    @endif
</div>
