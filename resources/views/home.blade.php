@inject('settings', 'App\Services\SettingService')
@extends('layouts.guest')
@section('content')
<div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Content -->

		<x-header />

		<x-blogs />
		<!-- begin:: Section -->
		@if($settings->value('banner'))
		<img src="{{ $settings->value('banner') }}" width="100%" class="mb-4 p-4" />
		@endif
		<div class="kt-container ">
			<div class="row">
				<div class="col-lg-4">
					<a href="{{ route('register', 'balita') }}" class="kt-portlet kt-iconbox kt-iconbox--animate-slow">
						<div class="kt-portlet__body">
							<div class="kt-iconbox__body">
								<div class="kt-iconbox__icon">
								<img src="{{ asset('assets/images/talita.png') }}" width="100px" />	
								</div>
								<div class="kt-iconbox__desc">
									<h3 class="kt-iconbox__title">
										Talita
									</h3>
									<div class="kt-iconbox__content">
										Tahsin Balita
									</div>
									<p class="mt-2 font-weight-bold">
										Pendaftaran Tahsin Balita
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-square-fill" viewBox="0 0 16 16">
										<path d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12zM5.904 10.803 10 6.707v2.768a.5.5 0 0 0 1 0V5.5a.5.5 0 0 0-.5-.5H6.525a.5.5 0 1 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 .707.707z"/>
										</svg>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-4">
					<a href="{{ route('register', 'anak') }}" class="kt-portlet kt-iconbox kt-iconbox--animate">
						<div class="kt-portlet__body">
							<div class="kt-iconbox__body">
								<div class="kt-iconbox__icon">
									<img src="{{ asset('assets/images/tahsin anak.png') }}" width="100px" />
								</div>
								<div class="kt-iconbox__desc">
									<h3 class="kt-iconbox__title">
										Tahsin Anak
									</h3>
									<div class="kt-iconbox__content">
										SD, SMP, SMA sederajat
									</div>
									<p class="mt-2 font-weight-bold">
										Pendaftaran Tahsin Anak
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-square-fill" viewBox="0 0 16 16">
										<path d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12zM5.904 10.803 10 6.707v2.768a.5.5 0 0 0 1 0V5.5a.5.5 0 0 0-.5-.5H6.525a.5.5 0 1 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 .707.707z"/>
										</svg>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-4">
					<a href="{{ route('register', 'dewasa') }}" class="kt-portlet kt-iconbox kt-iconbox--animate-fast">
						<div class="kt-portlet__body">
							<div class="kt-iconbox__body">
								<div class="kt-iconbox__icon">
								<img src="{{ asset('assets/images/tahsin dewasa.png') }}" width="100px" />
								</div>
								<div class="kt-iconbox__desc">
									<h3 class="kt-iconbox__title">
										Tahsin Dewasa
									</h3>
									<div class="kt-iconbox__content">
										Ikhwan dan Akhwat
									</div>
									<p class="mt-2 font-weight-bold">
										Pendaftaran Tahsin Dewasa
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-square-fill" viewBox="0 0 16 16">
										<path d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12zM5.904 10.803 10 6.707v2.768a.5.5 0 0 0 1 0V5.5a.5.5 0 0 0-.5-.5H6.525a.5.5 0 1 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 .707.707z"/>
										</svg>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<!-- end:: iconbox -->

		<!-- begin:: Section -->
		<div class="kt-container ">
			<div class="kt-portlet">
				<div class="kt-portlet__body">
					<div class="kt-infobox">
						<div class="kt-infobox__header">
							<h2 class="kt-infobox__title">{{ config('app.name') }}</h2>
						</div>
						<div class="kt-infobox__body">
							<img src="{{ asset('assets/images/wakaf.png') }}" width="100%" class="mx-auto mb-2" />
							{!! $settings->value('about') !!}
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Section -->

		<!-- begin:: Section -->
		<div class="kt-container ">
			<div class="row">
				<div class="col-lg-6">
					<div class="kt-portlet kt-callout">
						<div class="kt-portlet__body">
							<div class="kt-callout__body">
								<div class="kt-callout__content">
									<h3 class="kt-callout__title">Instagram</h3>
									<p class="kt-callout__desc">
										Ikut informasi seputar {{ config('app.name') }} melalui instagram kami
									</p>
								</div>
								<div class="kt-callout__action">
									<a href="{{ $settings->value('instagram') }}" target="_blank" class="btn btn-custom btn-bold btn-upper btn-font-sm btn-primary">@lang('Follow Us')</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="kt-portlet kt-callout">
						<div class="kt-portlet__body">
							<div class="kt-callout__body">
								<div class="kt-callout__content">
									<h3 class="kt-callout__title">Whatsapp</h3>
									<p class="kt-callout__desc">
										Sampaikan pertanyaan seputar {{ config('app.name') }} melalui whatsapp
									</p>
								</div>
								<div class="kt-callout__action">
									<a href="https://wa.me/{{ $settings->value('whatsapp') }}" target="_blank" class="btn btn-custom btn-bold btn-upper btn-font-sm  btn-success">
										{{ $settings->value('whatsapp') }}
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Section -->

		<!-- end:: Content -->
		</div>
@endsection
@push('styles')
<link href="{{ asset('assets/css/pages/support-center/home-1.css') }}" rel="stylesheet" type="text/css">
@endpush