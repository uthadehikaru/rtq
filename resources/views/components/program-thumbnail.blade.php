@props(['program'])
<button
	type="button"
	class="group block w-full border-0 bg-transparent p-0 text-left"
	@click="openLightbox(@js($program->imageUrl('thumbnail')), @js($program->title))"
	aria-label="Perbesar gambar: {{ $program->title }}"
>
	<img
		src="{{ $program->imageUrl('thumbnail') }}"
		alt="{{ $program->title }}"
		class="w-full transition duration-300 ease-in-out group-hover:opacity-90"
	/>
</button>
