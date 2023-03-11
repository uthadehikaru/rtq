@extends('layouts.guest')
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Content -->
		<div class="p-4">
			<div class="row">
				@foreach ($members as $member)
					<div class="col-6 col-md-2 text-center">
						<img data-src="{{ thumbnail($member->profile_picture) }}" class="lazy img-fluid" />
						<p class="">{{ $member->full_name }}</p>
					</div>
				@endforeach
			</div>
		</div>
@endsection
@push('scripts')
	 <!-- cdnjs -->
	 <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
       
	<script>
	$(function() {
        $('.lazy').Lazy();
    });
    </script>
@endpush