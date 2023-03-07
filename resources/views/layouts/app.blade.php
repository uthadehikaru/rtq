<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="{{ url('') }}">
		<meta charset="utf-8" />
		<title>{{ config('app.name') }} | {{ $title??'Dasboard' }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="{{ url('') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{ asset('assets/css/skins/header/base/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/skins/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/skins/brand/light.css') }}" rel="stylesheet" type="text/css" />

		@livewireStyles
		@stack('styles')

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-page--loading">

		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="{{ url('') }}" class="h2">
					<!-- <img alt="Logo" src="{{ asset('assets/media/logos/logo-dark.png') }}" /> -->
					{{ config('app.name') }}
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

						<!-- begin:: Header Menu -->

						<!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
-->
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
							<div class="kt-header-logo">
								<a href="{{ url('') }}" class="h2">
									<!-- <img alt="Logo" src="{{ asset('assets/media/logos/logo-dark.png') }}" /> -->
									{{ config('app.name') }}
								</a>
							</div>
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
								<ul class="kt-menu__nav ">
                                    <li class="kt-menu__item kt-menu__item--rel {{ \Request::is('dashboard')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('dashboard') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Dashboard')</span>
                                        </a>
                                    </li>
									@role('administrator')
									<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel  {{ \Request::is('educations*')?'kt-menu__item--active':'' }} kt-menu__item--open-dropdown" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="javascript:;" class="kt-menu__link kt-menu__toggle">
											<span class="kt-menu__link-text">Pendidikan</span><i class="kt-menu__ver-arrow la la-angle-right"></i>
										</a>
										<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
											<ul class="kt-menu__subnav">
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('teachers.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Pengajar</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('members.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Anggota</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('courses.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Kelas</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('schedules.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Jadwal</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('registrations.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Pendaftaran</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('biodata.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Verifikasi Biodata</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel  {{ \Request::is('finances*')?'kt-menu__item--active':'' }} kt-menu__item--open-dropdown" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="javascript:;" class="kt-menu__link kt-menu__toggle">
											<span class="kt-menu__link-text">Keuangan</span><i class="kt-menu__ver-arrow la la-angle-right"></i>
										</a>
										<div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
											<ul class="kt-menu__subnav">
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('payments.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Pembayaran</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('salaries.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Bisyaroh</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('violations.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Pelanggaran</span>
													</a>
												</li>
												<li class="kt-menu__item " aria-haspopup="true">
													<a href="{{ route('transactions.index') }}" class="kt-menu__link ">
														<span class="kt-menu__link-text">Uang Kas</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('blog*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ url('blog_admin') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Artikel')</span>
                                        </a>
                                    </li>
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('setting*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('settings.index') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Pengaturan')</span>
                                        </a>
                                    </li>
									@endrole
									@role('teacher')
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('teacher/schedules*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('teacher.schedules.index') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Schedules')</span>
                                        </a>
                                    </li>
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('teacher/presents*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('teacher.presents.index') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Kehadiran')</span>
                                        </a>
                                    </li>
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('teacher/salaries*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('teacher.salaries.index') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Salaries')</span>
                                        </a>
                                    </li>
									@endrole
									@role('member')
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('member/presents*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('member.presents.index') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Kehadiran')</span>
                                        </a>
                                    </li>
									<li class="kt-menu__item kt-menu__item--rel {{ \Request::is('member/iqob*')?'kt-menu__item--active':'' }}">
                                        <a href="{{ route('member.iqob.index') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Iqob')</span>
                                        </a>
                                    </li>
									@endrole
									<li class="kt-menu__item kt-menu__item--rel d-block d-md-none">
                                        <a href="{{ route('logout') }}" class="kt-menu__link">
                                            <span class="kt-menu__link-text">@lang('Sign Out')</span>
                                        </a>
                                    </li>
								</ul>
							</div>
						</div>

						<!-- end:: Header Menu -->

						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">

							<!--begin: User Bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
									<div class="kt-header__topbar-user">
										<span class="kt-header__topbar-username">{{ Auth::user()->name }}</span>
										<img alt="Pic" src="{{ asset('assets/images/default.jpg') }}" />
									</div>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

									<!--begin: Head -->
									<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(assets/media/misc/bg-1.jpg)">
										<div class="kt-user-card__avatar">
											<img alt="Pic" src="{{ asset('assets/images/default.jpg') }}" />

										</div>
										<div class="kt-user-card__name">
											{{ Auth::user()->name }}
										</div>
									</div>

									<!--end: Head -->

									<!--begin: Navigation -->
									<div class="kt-notification">
										<div class="kt-notification__custom kt-space-between">
											<a href="{{ route('logout') }}" class="btn btn-label btn-label-brand btn-sm btn-bold">@lang('Sign Out')</a>
										</div>
									</div>

									<!--end: Navigation -->
								</div>
							</div>

							<!--end: User Bar -->
						</div>

						<!-- end:: Header Topbar -->
					</div>

					<!-- end:: Header -->
					<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

						<!-- begin:: Subheader -->
						<div class="kt-subheader   kt-grid__item" id="kt_subheader">
							<div class="kt-container  kt-container--fluid ">
								<div class="kt-subheader__main">
                                    @hasSection('breadcrumbs')
									<div class="kt-subheader__breadcrumbs">
                                        <a href="{{ route('dashboard') }}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                        <span class="kt-subheader__breadcrumbs-separator"></span>
                                        <a href="{{ route('dashboard') }}" class="kt-subheader__breadcrumbs-link">
										@lang("Dashboard") </a>
                                        <span class="kt-subheader__breadcrumbs-separator"></span>
										@yield('breadcrumbs')
									</div>
                                    @else
									<h3 class="kt-subheader__title">
										{{ $title }} </h3>
									<span class="kt-subheader__separator kt-hidden"></span>
                                    @endif
								</div>
								@hasSection('toolbar')
								<div class="kt-subheader__toolbar">
									<div class="kt-subheader__wrapper">
										@yield('toolbar')	
									</div>
								</div>
								@endif
							</div>
						</div>

						<!-- end:: Subheader -->

						<!-- begin:: Content -->
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                            @yield('content')
						</div>

						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
						<div class="kt-container  kt-container--fluid ">
							<div class="kt-footer__copyright">
								2022&nbsp;&copy;&nbsp;<a href="{{ url('/') }}" target="_blank" class="kt-link">{{ config('app.name') }}</a>
							</div>
							<div class="kt-footer__menu">
								@role('administrator')
								<a href="{{ route('activities') }}" target="_blank" class="kt-footer__menu-link kt-link">Activities</a>
								<a href="{{ route('logs') }}" target="_blank" class="kt-footer__menu-link kt-link">Logs</a>
								@endrole
							</div>
						</div>
					</div>

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

		<!-- end::Scrolltop -->

		

		

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->
        
		@livewireScripts
		
		<!--begin::Page Scripts(used by this page) -->
        @stack('scripts')
		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>