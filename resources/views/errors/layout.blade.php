@extends('layouts.guest')
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Content -->

		<x-header />

		
		<div class="kt-container ">
			<div class="row">
				<div class="col">
                    <div class="card card-custom p-6 mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="row">
                                <!--begin::Content-->
                                <div class="col-sm-7">
                                    <h2 class="text-dark mb-4">@yield('code')</h2>
                                    <p class="text-dark-50 line-height-lg">
                                        @yield('message') klik tombol kembali ke halaman awal
                                    </p>
                                </div>
                                <!--end::Content-->

                                <!--begin::Button-->
                                <div class="col-sm-5 d-flex align-items-center justify-content-sm-end">
                                    <a href="{{ route('home') }}" class="btn font-weight-bolder text-uppercase font-size-lg btn-primary py-3 px-6">Kembali</a>
                                </div>
                                <!--end::Button-->
                            </div>
                        </div>
                    </div>
				</div>
				
			</div>
		</div>

		<!-- end:: iconbox -->
@endsection
@push('styles')
<link href="{{ asset('assets/css/pages/support-center/home-1.css') }}" rel="stylesheet" type="text/css">
@endpush