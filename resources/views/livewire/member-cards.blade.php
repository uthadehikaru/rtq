<div>
    <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile" id="kt_page_portlet">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">Kartu Anggota</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<a href="{{ route('home') }}" class="btn btn-clean kt-margin-r-10">
					<i class="la la-arrow-left"></i>
					<span class="kt-hidden-mobile">@lang('Back')</span>
				</a>
				<button class="btn btn-brand" wire:click="download" wire:loading.class="disabled">
					<i class="la la-save"></i>
					<span class="kt-hidden-mobile">@lang('Download (.zip)')</span>
				</button>
                <div class="spinner-border" role="status" wire:loading.delay>
                <span class="sr-only">Loading...</span>
                </div>
			</div>
		</div>
		<div class="kt-portlet__body">
			<!-- begin:: Content -->
			<div class="p-4">
				<div class="row">
					@foreach ($members as $member)
						<div class="col-6 col-md-3 text-center">
							<img src="{{ thumbnail('idcards/'.$member->member_no.'.jpg') }}" class="img-fluid" />
							<p class="">{{ $member->member_no }} - {{ $member->full_name }}</p>
						</div>
					@endforeach
				</div>
			</div>
			<div class="row">{{ $members->links() }}</div>
		</div>
	</div>
</div>