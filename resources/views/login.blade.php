@extends('layouts.guest')
@section('content')
		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(assets/media/bg/bg-2.jpg);">
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
						<div class="kt-login__container">
							<div class="kt-login__logo">
								<a href="#">
									<img src="{{ asset('assets/media/logos/logo-5.png') }}">
								</a>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title">@lang('login.subtitle')</h3>
								</div>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
								<form class="kt-form" action="{{ route('login') }}" method="post">
                                    @csrf
									<div class="input-group">
										<input class="form-control" type="text" placeholder="@lang('login.email')" name="email" autocomplete="off">
									</div>
									<div class="input-group">
										<input class="form-control" type="password" placeholder="@lang('login.password')" name="password">
									</div>
									<div class="row kt-login__extra">
										<div class="col">
											<label class="kt-checkbox">
												<input type="checkbox" name="remember"> @lang('login.rememberme')
												<span></span>
											</label>
										</div>
									</div>
									<div class="kt-login__actions">
										<button id="" class="btn btn-brand btn-pill kt-login__btn-primary">@lang('login.signin')</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->
@endsection
@push('styles')
<link href="{{ asset('assets/css/pages/login/login-4.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
<script src="{{ asset('assets/js/pages/custom/login/login-general.js') }}" type="text/javascript"></script>
@endpush