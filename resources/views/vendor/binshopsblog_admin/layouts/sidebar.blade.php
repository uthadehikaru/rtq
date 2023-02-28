<ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="posts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Posts
        </a>
        <div class="dropdown-menu" aria-labelledby="posts">
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.index') }}">All Posts</a>
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.create_post') }}">Add Post</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="comments" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Comments
        </a>
        <div class="dropdown-menu" aria-labelledby="comments">
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.comments.index') }}">All Comments</a>
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.comments.index') }}?waiting_for_approval=true">Waiting Comments</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="categories" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="categories">
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.categories.index') }}">All Categories</a>
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.categories.create_category') }}">Create Category</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="languages" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Languages
        </a>
        <div class="dropdown-menu" aria-labelledby="languages">
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.languages.index') }}">All Languages</a>
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.languages.create_language') }}">Add Language</a>
        </div>
    </li>
    @if(config("binshopsblog.image_upload_enabled"))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="images" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Images
        </a>
        <div class="dropdown-menu" aria-labelledby="images">
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.images.all') }}">All Images</a>
          <a class="dropdown-item" href="{{ route('binshopsblog.admin.images.upload') }}">Upload</a>
        </div>
    </li>
    @endif
    <!-- <li class="list-group-item list-group-color justify-content-between lh-condensed">
        <div>
            <h6 class="my-0"><a href="{{ route('binshopsblog.admin.index') }}">Dashboard</a>
                <span class="text-muted">(<?php
                    $categoryCount = \BinshopsBlog\Models\BinshopsPost::count();

                    echo $categoryCount . " " . str_plural("Post", $categoryCount);

                    ?>)</span>
            </h6>
            <small class="text-muted">Overview of your posts</small>

            <div class="list-group ">

                <a href='{{ route('binshopsblog.admin.index') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action @if(\Request::route()->getName() === 'binshopsblog.admin.index') active @endif  '><i
                            class="fa fa-th fa-fw"
                            aria-hidden="true"></i>
                    All Posts</a>
                <a href='{{ route('binshopsblog.admin.create_post') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.create_post') active @endif  '><i
                            class="fa fa-plus fa-fw" aria-hidden="true"></i>
                    Add Post</a>
            </div>
        </div>
    </li>


    <li class="list-group-item list-group-color justify-content-between lh-condensed">
        <div>
            <h6 class="my-0"><a href="{{ route('binshopsblog.admin.comments.index') }}">Comments</a>

                <span class="text-muted">(<?php
                    $commentCount = \BinshopsBlog\Models\BinshopsComment::withoutGlobalScopes()->count();

                    echo $commentCount . " " . str_plural("Comment", $commentCount);

                    ?>)</span>
            </h6>
            <small class="text-muted">Manage your comments</small>

            <div class="list-group ">
                <a href='{{ route('binshopsblog.admin.comments.index') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.comments.index' && !\Request::get("waiting_for_approval")) active @endif   '><i
                            class="fa  fa-fw fa-comments" aria-hidden="true"></i>
                    All Comments</a>


                <?php $comment_approval_count = \BinshopsBlog\Models\BinshopsComment::withoutGlobalScopes()->where("approved", false)->count(); ?>

                <a href='{{ route('binshopsblog.admin.comments.index') }}?waiting_for_approval=true'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.comments.index' && \Request::get("waiting_for_approval")) active @elseif($comment_approval_count>0) list-group-item list-group-color-warning @endif  '><i
                            class="fa  fa-fw fa-comments" aria-hidden="true"></i>
                    {{ $comment_approval_count }}
                    Waiting for approval </a>

            </div>
        </div>
    </li>


    <li class="list-group-item list-group-color  justify-content-between lh-condensed">
        <div>
            <h6 class="my-0"><a href="{{ route('binshopsblog.admin.categories.index') }}">Categories</a>
                <span class="text-muted">(<?php
                    $postCount = \BinshopsBlog\Models\BinshopsCategory::count();
                    echo $postCount . " " . str_plural("Category", $postCount);
                    ?>)</span>
            </h6>


            <small class="text-muted">Blog post categories</small>

            <div class="list-group ">
                <a href='{{ route('binshopsblog.admin.categories.index') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.categories.index') active @endif  '><i
                            class="fa fa-object-group fa-fw" aria-hidden="true"></i>
                    All Categories</a>
                <a href='{{ route('binshopsblog.admin.categories.create_category') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.categories.create_category') active @endif  '><i
                            class="fa fa-plus fa-fw" aria-hidden="true"></i>
                    Add Category</a>
            </div>
        </div>

    </li>


    <li class="list-group-item list-group-color  justify-content-between lh-condensed">
        <div>
            <h6 class="my-0"><a href="{{ route('binshopsblog.admin.images.upload') }}">Languages</a></h6>

            <div class="list-group ">

                <a href='{{ route('binshopsblog.admin.languages.index') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.languages.index') active @endif  '><i
                            class="fa fa-language fa-fw" aria-hidden="true"></i>
                    All Languages</a>

                <a href='{{ route('binshopsblog.admin.languages.create_language') }}'
                   class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.languages.create_language') active @endif  '><i
                            class="fa fa-plus fa-fw" aria-hidden="true"></i>
                    Add new Language</a>
            </div>
        </div>
    </li>


    @if(config("binshopsblog.image_upload_enabled"))
        <li class="list-group-item list-group-color  justify-content-between lh-condensed">
            <div>
                <h6 class="my-0"><a href="{{ route('binshopsblog.admin.images.upload') }}">Upload images</a></h6>

                <div class="list-group ">

                    <a href='{{ route('binshopsblog.admin.images.all') }}'
                       class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.images.all') active @endif  '><i
                                class="fa fa-picture-o fa-fw" aria-hidden="true"></i>
                        View All</a>

                    <a href='{{ route('binshopsblog.admin.images.upload') }}'
                       class='list-group-item list-group-color list-group-item list-group-color-action  @if(\Request::route()->getName() === 'binshopsblog.admin.images.upload') active @endif  '><i
                                class="fa fa-upload fa-fw" aria-hidden="true"></i>
                        Upload</a>
                </div>
            </div>
        </li>
    @endif -->
</ul>
