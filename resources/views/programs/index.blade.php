@inject('settings', 'App\Services\SettingService')
@extends('layouts.kubik')

@section('title', 'Program Kami — ' . config('app.name'))

@section('content')

<section class="py-8 md:py-16">

	<div class="container max-w-screen-xl mx-auto px-4">

		<header class="text-center mb-12 md:mb-20">
			<h1 class="font-semibold text-gray-700 text-3xl md:text-4xl mb-5">Program Kami</h1>
			<p class="font-normal text-gray-500 text-md md:text-lg max-w-3xl mx-auto">Jadilah bagian dari perjuangan dakwah kami</p>
		</header>

		<x-program-gallery>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 xl:gap-10">
			@foreach ($programs as $program)
			<div class="space-y-2 xl:space-y-4">
				<x-program-thumbnail :program="$program" />
				<span class="block font-semibold text-gray-700 text-2xl md:text-4xl hover:text-green-500 transition ease-in-out duration-300">{{ $program->title }}</span>
				@if($program->qty > 0)
				<div class="flex w-full justify-between">
					<p class="font-normal text-green-800 text-xl">Terkumpul<br/><span class="font-bold">@money($program->amount)</span></p>
					<p class="font-normal text-gray-800 text-sm text-right">Diperbaharui pada<br/>{{ $program->updated_at->format('d M y') }}</p>
				</div>
				@endif
				<div class="flex w-full">
					<a href="https://wa.me/{{ $settings->value('whatsapp') }}?text=saya+ingin+donasi+untuk+program+{{ $program->title }}" target="{{ $program->slug }}" class="p-2 w-full bg-green-800 rounded text-white mt-2 text-center hover:opacity-75">Dukung Program Ini</a>
				</div>
			</div>
			@endforeach
		</div>
		</x-program-gallery>

		@if($programs->hasPages())
		<div class="mt-12 md:mt-16">
			{{ $programs->links('vendor.pagination.tailwind') }}
		</div>
		@endif

		<div class="text-center mt-10">
			<a href="{{ route('home') }}" class="inline-block px-8 py-3 font-medium text-green-800 border-2 border-green-800 rounded-md hover:bg-green-800 hover:text-white transition ease-in-out duration-300">&larr; Kembali ke beranda</a>
		</div>
	</div>

</section>

@endsection
