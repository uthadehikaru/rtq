@php
$posts = \BinshopsBlog\Models\BinshopsPostTranslation::whereRelation('post','is_published',true)->latest()->limit(5)->get();
@endphp
@if($posts)
<div id="carouselExampleCaptions" class="carousel slide p-4" data-ride="carousel">
  <div class="carousel-inner">
  @foreach($posts as $post)
    <div class="carousel-item {{ $loop->index==0?'active':'' }}">
      <a href="{{ $post->url('id') }}" class="card text-center py-2">
        <h5>{{ $post->title }}</h5>
        <p>{{ $post->short_description }}</p>
</a>
    </div>
    @endforeach
  </div>
</div>
@endif