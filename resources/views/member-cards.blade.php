@extends('layouts.guest')
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Content -->
		<div class="p-4">
			<div class="row">
				@foreach ($members as $member)
					<div class="col-3 text-center">
						<img src="{{ thumbnail('idcards/'.$member->member_no.'.jpg') }}" class="img-fluid" />
						<p class="">{{ $member->member_no }} - {{ $member->full_name }}</p>
					</div>
				@endforeach
			</div>
		</div>
@endsection