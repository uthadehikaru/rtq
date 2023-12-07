<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		@hasSection('title')
		<title>@yield('title')</title>
		@else
		<title>{{ config('app.name') }} {{ $title??''}}</title>
		@endif
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" integrity="sha512-7x3zila4t2qNycrtZ31HO0NnJr8kg2VI67YLoRSyi9hGhRN66FHYWr7Axa9Y1J9tGYHVBPqIjSE1ogHrJTz51g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

		<!--begin::Page Custom Styles(used by this page) -->
		@stack('styles')
		<!--end::Page Custom Styles -->

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
	</head>

	<!-- end::Head -->

	<body class="bg-white">
        
        <!-- home section -->
        <section class="py-8 md:py-16">

            <div class="container max-w-screen-xl mx-auto px-4">

                <nav class="flex-wrap lg:flex items-center justify-between" x-data="{navbarOpen:false}">
                    <div class="flex items-center justify-between mb-10 lg:mb-0">
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/rtq maisuro.png') }}" class="w-20" alt="Logo"></a>

                        <button class="flex items-center justify-center border border-green-500 w-10 h-10 text-green-500 rounded-md outline-none lg:hidden ml-auto" @click="navbarOpen = !navbarOpen">
                            <i data-feather="menu"></i>
                        </button>
                    </div>

                    <ul class="hidden lg:block lg:flex flex-col lg:flex-row lg:items-center lg:space-x-20" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                        <li class="font-medium text-green-500 text-lg hover:text-green-300 transition ease-in-out duration-300 mb-5 lg:mb-0">
                            <a href="{{ route('payment') }}">Konfirmasi Pembayaran</a>
                        </li>

						@auth
						<li class="px-8 py-3 font-medium text-green-500 text-lg text-center border-2 border-green-500 rounded-md hover:bg-green-500 hover:text-white transition ease-linear duration-300">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                       	</li>
						@else
                       	<li class="px-8 py-3 font-medium text-green-500 text-lg text-center border-2 border-green-500 rounded-md hover:bg-green-500 hover:text-white transition ease-linear duration-300">
                            <a href="{{ route('login') }}">Masuk</a>
                       </li>
						@endauth
                    </ul>
                </nav>

			</div>

		</section>

		@yield('content')

        <footer class="bg-green-50 py-2 md:py-4">

            <div class="container max-w-screen-xl mx-auto px-4">

                <p class="font-normal text-gray-400 text-md text-center">&copy; {{ date('Y') }} RTQ Maisuro. All rights reserved.</p>

            </div> <!-- container.// -->

        </footer>

        <script>
            feather.replace()
        </script>

		@if(config('app.analytic_id'))
			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.analytic_id') }}"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '{{ config('app.analytic_id') }}');
			</script>
		@endif
		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts(used by this page) -->
		@stack('scripts')
		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>