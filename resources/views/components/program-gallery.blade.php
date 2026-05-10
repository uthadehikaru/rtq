@props([])
<div
	x-data="{
		lightboxOpen: false,
		lightboxSrc: '',
		lightboxAlt: '',
		openLightbox(src, alt) {
			this.lightboxSrc = src;
			this.lightboxAlt = alt;
			this.lightboxOpen = true;
		}
	}"
	@keydown.escape.window="lightboxOpen = false"
>
	{{ $slot }}
	<template x-teleport="body">
		<div
			x-show="lightboxOpen"
			x-transition:enter="ease-out duration-200"
			x-transition:enter-start="opacity-0"
			x-transition:enter-end="opacity-100"
			x-transition:leave="ease-in duration-150"
			x-transition:leave-start="opacity-100"
			x-transition:leave-end="opacity-0"
			class="fixed inset-0 z-[100] flex items-center justify-center p-4"
			style="display: none;"
			role="dialog"
			aria-modal="true"
			aria-label="Pratinjau gambar program"
		>
			<div class="absolute inset-0 bg-black/80" @click="lightboxOpen = false" aria-hidden="true"></div>
			<button
				type="button"
				class="absolute top-4 right-4 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-3xl font-light leading-none text-white hover:bg-white/20"
				@click="lightboxOpen = false"
				aria-label="Tutup"
			>
				×
			</button>
			<img
				:src="lightboxSrc"
				:alt="lightboxAlt"
				class="relative z-10 max-h-[90vh] max-w-full object-contain shadow-2xl"
				@click.stop
				loading="eager"
			/>
		</div>
	</template>
</div>
