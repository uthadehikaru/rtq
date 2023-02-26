<h5>Artikel Terbaru</h5>
<ul class="">
    @foreach(\BinshopsBlog\Models\BinshopsPostTranslation::latest()->limit(5)->get() as $post)
        <li class="nav-item">
            <a class='nav-link' href='{{$post->url('id')}}'>{{$post->title}}</a>
        </li>
    @endforeach
</ul>