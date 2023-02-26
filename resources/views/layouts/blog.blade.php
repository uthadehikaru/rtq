@extends('layouts.guest')
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Content -->

		<x-header />

        <div class="kt-container">
            <div class="kt-portlet py-4 px-2">
            @yield('blog')
            </div>
        </div>
</div>s
@endsection
@push('styles')
<link href="{{ asset('assets/css/pages/support-center/home-1.css') }}" rel="stylesheet" type="text/css">
@endpush