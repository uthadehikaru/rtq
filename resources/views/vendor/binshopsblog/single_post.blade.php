@extends("layouts.blog",['title'=>$post->gen_seo_title()])

@push('styles')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">
@endpush

@section("blog")

    @if(config("binshopsblog.reading_progress_bar"))
        <div id="scrollbar">
            <div id="scrollbar-bg"></div>
        </div>
    @endif

    {{--https://github.com/binshops/laravel-blog--}}

    <div class='row'>
        <div class='col-sm-12 col-md-12 col-lg-12'>

            @include("binshopsblog::partials.show_errors")
            @include("binshopsblog::partials.full_post_details")


            @if(config("binshopsblog.comments.type_of_comments_to_show","built_in") !== 'disabled')
                <div class="" id='maincommentscontainer'>
                    <h2 class='text-center' id='binshopsblogcomments'>Komentar</h2>
                    @include("binshopsblog::partials.show_comments")
                </div>
            @else
                {{--Comments are disabled--}}
            @endif


        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('binshops-blog.js')}}"></script>
@endpush
