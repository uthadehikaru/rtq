@extends('layouts.kubik')
@section('content')
<section class="text-gray-600 body-font relative">
  <div class="absolute inset-0 bg-gray-300">
    <iframe width="100%" height="100%" frameborder="0" marginheight="0" marginwidth="0" title="map" scrolling="no" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.3209447545482!2d106.77103707434705!3d-6.221342660931115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f150d4465443%3A0x745f8b5d38fdab43!2sRTQ%20MAISURO!5e0!3m2!1sen!2sid!4v1700129547624!5m2!1sen!2sid" style="filter: grayscale(1) contrast(1.2) opacity(0.4);"></iframe>
  </div>
  <div class="container px-5 py-24 mx-auto flex">
    <div class="lg:w-1/3 md:w-1/2 bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 relative z-10 shadow-md">
      <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Selamat Datang!</h2>
	  @if ($errors->any())
	<div class="leading-relaxed mb-5 text-red-500">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	@env('local')
		<div class="text-center">
			<p><a href="{{ route('login.as', 'admin@rtqmaisuro.id') }}">Login as Admin</a></p>
			<p><a href="{{ route('login.as', 'ibnumahmud7941@gmail.com') }}">Login as Ust. Muslim</a></p>
			<p><a href="{{ route('login.as', 'zuhriutama@gmail.com') }}">Login as Zuhri</a></p>
		</div>
	@endenv
	<form action="{{ route('login') }}" method="post">
		@csrf
      <div class="relative mb-4">
        <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
        <input type="text" id="email" name="email"  placeholder="Email atau No anggota"
		class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
		value="{{ old('email',$username) }}">
      </div>
      <div class="relative mb-4">
        <label for="password" class="leading-7 text-sm text-gray-600">Password</label>
        <input type="password" id="password" name="password"  placeholder="@lang('login.password')"
		class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
		>
      </div>
      <button type="submit" class="w-full text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Masuk</button>
    </div>
  </div>
</section>
@endsection