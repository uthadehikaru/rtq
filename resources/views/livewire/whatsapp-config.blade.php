<div>
    <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile" id="kt_page_portlet">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">Whatsapp API</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
                <div class="spinner-border" role="status" wire:loading.delay>
                <span class="sr-only">Loading...</span>
                </div>
			</div>
		</div>
		<div class="kt-portlet__body d-flex flex-column align-items-center">
            <p class="">Endpoint : {{ $whatsapp_url }}</p>
            <p class="">Service : {{ $service ?? 'No Service' }}</p>
            <p class="">Status : {{ $status }}</p>
            @if($qrcode)
                <div wire:poll="refreshQrcode" class="d-flex flex-column align-items-center">
                    <img src="data:image/png;base64,{{ $qrcode }}" width="200" height="200" alt="QR Code">
                    <button class="btn btn-primary" wire:click="refreshQrcode">
                        <div wire:loading.remove>Refresh QR Code</div>
                        <div wire:loading wire:target="refreshQrcode">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </button>
                </div>
            @endif
            @if($status == 'logged_out')
                <button class="btn btn-primary" wire:click="clear">
                    <div wire:loading.remove>Request QR Code</div>
                    <div wire:loading wire:target="clear">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </button>
            @elseif($status == 'connected')
                <button class="btn btn-danger" wire:click="logout" wire:confirm="Apakah Anda yakin ingin logout?">
                    <div wire:loading.remove>Logout</div>
                    <div wire:loading wire:target="logout">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </button>
            @endif
        </div>
    </div>
</div>
