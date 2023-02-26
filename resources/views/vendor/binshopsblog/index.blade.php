@extends("layouts.blog",['title'=>$title])

@push('styles')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endpush

@section("blog")

    <div class='col-sm-12 binshopsblog_container'>
        <div class="row">
            <div class="col-md-9">

                @if($category_chain)
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                @forelse($category_chain as $cat)
                                    / <a href="{{$cat->categoryTranslations[0]->url($locale)}}">
                                        <span class="cat1">{{$cat->categoryTranslations[0]['category_name']}}</span>
                                    </a>
                                @empty @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($binshopsblog_category) && $binshopsblog_category)
                    <h2 class='text-center'> {{$binshopsblog_category->category_name}}</h2>

                    @if($binshopsblog_category->category_description)
                        <p class='text-center'>{{$binshopsblog_category->category_description}}</p>
                    @endif

                @endif

                <div class="container">
                    <div class="row">
                        @forelse($posts as $post)
                            @include("binshopsblog::partials.index_loop")
                        @empty
                            <div class="col-md-12">
                                <div class='alert alert-danger'>Tidak ada artikel!</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @if(\Auth::check() && \Auth::user()->canManageBinshopsBlogPosts())
                    <div class="text-center">
                        <p class='mb-1'>Kamu login sebagai admin blog
                            <br>
                            <a href='{{route("binshopsblog.admin.index")}}'
                            class='btn border  btn-outline-primary btn-sm '>
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                Blog Admin</a>
                        </p>
                    </div>
                @endif
                <h6>Kategori</h6>
                <ul class="binshops-cat-hierarchy">
                    @if($categories)
                        @include("binshopsblog::partials._category_partial", [
    'category_tree' => $categories,
    'name_chain' => $nameChain = "",
    'routeWithoutLocale' => $routeWithoutLocale
    ])
                    @else
                        <span>Tidak ada Kategori</span>
                    @endif
                </ul>

                @include('binshopsblog::sitewide.recent_posts')

                @if (config('binshopsblog.search.search_enabled') )
                    @include('binshopsblog::sitewide.search_form')
                @endif
            </div>
        </div>
    </div>

@endsection
