<!-- begin:: Hero -->
<div class="kt-sc" style="background: white">
    <div class="kt-container ">
        <div class="kt-sc__top">
			<a href="{{ url('/') }}">
            <h3 class="kt-sc__title">
                <img src="{{ asset('assets/images/favicon.png') }}" />
                {{ config('app.name') }}
            </h3>
			</a>
            <div class="kt-sc__nav">
                <div class="d-sm-only d-md-none">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-clean btn-icon" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="flaticon-more text-info"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__item">
                                    @guest
                                        <a href="{{ route('login') }}" class="kt-nav__link">
                                            <span class="kt-nav__link-text">@lang('Login')</span>
                                        </a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="kt-nav__link">
                                            <span class="kt-nav__link-text">@lang('Dashboard')</span>
                                        </a>
                                    @endif
                                    <a href="{{ url('blog') }}" class="kt-nav__link">
                                        <span class="kt-nav__link-text">@lang('Informasi')</span>
                                    </a>
                                    <a href="{{ route('payment') }}" class="kt-nav__link">
                                        <span class="kt-nav__link-text">@lang('Payment Confirm')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-block">
                    @guest
                        <a href="{{ route('login') }}"
                            class="kt-link kt-font-bold text-info">@lang('Login')</a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="kt-link kt-font-bold text-info">@lang('Dashboard')</a>
                    @endif
                    <a href="{{ url('blog') }}"
                        class="kt-link kt-font-bold text-info">@lang('Informasi')</a>
                    <a href="{{ route('payment') }}"
                        class="kt-link kt-font-bold text-info">@lang('Payment Confirm')</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end:: Hero -->