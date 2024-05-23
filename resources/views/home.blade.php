@inject('settings', 'App\Services\SettingService')
@extends('layouts.kubik')
@section('content')

<section class="py-8 md:py-16">

	<div class="container max-w-screen-xl mx-auto px-4">

		<header class="flex-col xl:flex-row flex justify-between">

			<div class="mx-auto text-center xl:text-left xl:mx-0 mb-20 xl:mb-0">
				<h1 class="font-bold text-gray-700 text-3xl md:text-6xl leading-tight mb-10">{{ config('app.name') }}</h1>

				<p class="font-normal text-gray-500 text-sm md:text-lg mb-10">Hidup indah penuh berkah bersama Al-Qur'an, Alhamdulillah!</p>

				<div class="flex items-center justify-center lg:justify-start">
					<a href="https://instagram.com/rumahtartilquran_maisuro" target="instagram" class="px-8 py-3 bg-green-800 font-medium text-white text-md md:text-lg rounded-md hover:opacity-75 transition ease-in-out duration-300 mr-14">Follow Us</a>

					<a href="https://www.youtube.com/@RumahTartilQuranMaisuro" target="youtube" class="font-normal text-gray-500 text-lg mr-8">Watch Us</a>

					<a href="https://www.youtube.com/watch?v=W-vb62xcWqs" class="px-4 py-4 text-gray-300 border-2 border-gray-200 rounded-full">
						<i data-feather="play"></i>
					</a>
				</div>
			</div>

			<div class="w-full md:w-1/2">
			<iframe width="100%" height="315" src="https://www.youtube.com/embed/W-vb62xcWqs?si=fbhkfromDhCPCJuP" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
			</div>

		</header>

	</div> <!-- container.// -->

</section>
<!-- feature section -->
<section class="py-8 md:py-16">

<div class="container max-w-screen-xl mx-auto px-4">

	<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
		<div class="text-center mb-10 xl:mb-0">
			<div class="flex items-center justify-center">
				<div class="w-1/3 py-7 flex justify-center bg-purple-50 text-purple-500 rounded-md mb-5 md:mb-10">
					<img src="{{ asset('assets/images/talita.png') }}" />
				</div>
			</div>

			<h2 class="font-semibold text-gray-700 text-xl md:text-3xl mb-5">Talita</h2>

			<p class="font-normal text-gray-400 text-sm md:text-lg mb-5">Tahsin Balita</p>

			<a href="{{ route('register', 'balita') }}" class="font-normal bg-green-800 text-white text-sm border border-green-800 w-full p-2 hover:opacity-75 rounded">Daftar Sekarang</a>
		</div>

		<div class="text-center mb-10 md:mb-0">
			<div class="flex items-center justify-center">
				<div class="w-1/3 py-7 flex justify-center bg-red-50 text-red-500 rounded-md mb-5 md:mb-10">
					<img src="{{ asset('assets/images/tahsin anak.png') }}" />
				</div>
			</div>

			<h2 class="font-semibold text-gray-700 text-xl md:text-3xl mb-5">Tahsin Anak</h2>

			<p class="font-normal text-gray-400 text-sm md:text-lg mb-5">SD, SMP, SMA sederajat</p>

			<a href="{{ route('register', 'anak') }}" class="font-normal bg-green-800 text-white text-sm border border-green-800 w-full p-2 hover:opacity-75 rounded">Daftar Sekarang</a>
		</div>

		<div class="text-center">
			<div class="flex items-center justify-center">
				<div class="w-1/3 py-7 flex justify-center bg-blue-50 text-blue-500 rounded-md mb-5 md:mb-10">
					<img src="{{ asset('assets/images/tahsin dewasa.png') }}" />
				</div>
			</div>

			<h2 class="font-semibold text-gray-700 text-xl md:text-3xl mb-5">Tahsin Dewasa</h2>

			<p class="font-normal text-gray-400 text-sm md:text-lg mb-5">Ikhwan dan Akhwat</p>

			<a href="{{ route('register', 'dewasa') }}" class="font-normal bg-green-800 text-white text-sm border border-green-800 w-full p-2 hover:opacity-75 rounded">Daftar Sekarang</a>
		</div>
	</div>

</div> <!-- container.// -->

</section>
<!-- feature section //end -->

<section class="py-8 md:py-16">

<div class="container max-w-screen-xl mx-auto px-4">

	<h1 class="font-semibold text-gray-700 text-3xl md:text-4xl text-center mb-5">WAKAF PEMBEBASAN LAHAN & BANGUNAN RTQ MAISŪRŌ</h1>

	<p class="font-normal text-gray-500 text-md md:text-lg text-center mb-10 md:mb-20">Mari Bersama Kita Wujudkan Harapan Ummat Untuk Menjadikan Bangunan Ini Sebagai Pusat Pendidikan Al-Qur'an Melalui Program Wakaf Pembebasan Lahan & Bangunan Untuk Rumah Tartil Al-Qur'an (RTQ) Maisūrō</p>

	<div class="flex flex-col xl:flex-row items-center justify-between">
		<div class="mx-auto xl:mx-0 mb-20 xl:mb-0">
			<img src="{{ asset('assets/images/wakaf.png') }}" alt="Image">
		</div>

		<div class="mx-auto xl:mx-0 text-center xl:text-left">
			<h1 class="font-bold text-gray-700 text-3xl md:text-4xl mb-10">BSI 777.999.6861<br/>
			An. Yayasan Al Muzzammil Quranic Reading Organization</h1>

			<p class="font-normal text-gray-400 text-sm md:text-lg mb-5">Mari Bersama Kita Wujudkan Harapan Ummat Untuk Menjadikan Bangunan Ini Sebagai Pusat Pendidikan Al-Qur'an Melalui Program Wakaf Pembebasan Lahan & Bangunan Untuk Rumah Tartil Al-Qur'an (RTQ) Maisūrō</p>

			<a href="https://wa.me/{{ $settings->value('whatsapp') }}" target="whatsapp" class="flex items-center justify-center xl:justify-start font-semibold text-green-500 text-lg gap-3 hover:text-green-700 transition ease-in-out duration-300">
				More Info 081293297936 (WA)
			</a>
		</div>
	</div>

</div> <!-- container.// -->

</section>

<section class="py-8 md:py-16">

<div class="container max-w-screen-xl mx-auto px-4">

	<h1 class="font-semibold text-gray-700 text-3xl md:text-4xl text-center mb-5">Program Kami</h1>

	<p class="font-normal text-gray-500 text-md md:text-lg text-center mb-20">Jadilah bagian dari perjuangan dakwah kami</p>

	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 xl:gap-10">
		@foreach ($programs as $program)
		<div class="space-y-2 xl:space-y-4">
			<img src="{{ $program->imageUrl('thumbnail') }}" alt="{{ $program->title }}" class="w-full" />
			<span class="block font-semibold text-gray-700 text-2xl md:text-4xl hover:text-green-500 transition ease-in-out duration-300">{{ $program->title }}</span>
			<div class="flex w-full justify-between">
			<p class="font-normal text-green-800 text-xl">Terkumpul<br/><span class="font-bold">@money($program->amount)</span></p>
			<p class="font-normal text-gray-800 text-sm text-right">Diperbaharui pada<br/>{{ $program->updated_at->format('d M y') }}</p>
			</div>
			<div class="flex w-full">
			<a href="https://wa.me/{{ $settings->value('whatsapp') }}?text=saya+ingin+donasi+{{ $program->title }}" target="{{ $program->slug }}" class="p-2 w-full bg-green-800 rounded text-white mt-2 text-center hover:opacity-75">Tunaikan Sekarang</a> 
			</div>
		</div>
		@endforeach
	</div>
</div> <!-- container.// -->

</section>
@endsection