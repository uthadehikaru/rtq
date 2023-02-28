<ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('binshopsblog.admin.index') }}">
            {{ \BinshopsBlog\Models\BinshopsPost::count() }} Posts
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="comments" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Comments
        </a>
        <div class="dropdown-menu" aria-labelledby="comments">
            <a class="dropdown-item" href="{{ route('binshopsblog.admin.comments.index') }}">
                {{ \BinshopsBlog\Models\BinshopsComment::withoutGlobalScopes()->count() }} Comments
            </a>
            <a class="dropdown-item" href="{{ route('binshopsblog.admin.comments.index') }}?waiting_for_approval=true">
                {{ \BinshopsBlog\Models\BinshopsComment::withoutGlobalScopes()->where("approved", false)->count() }} Waiting Comments
            </a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="categories" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="categories">
            <a class="dropdown-item" href="{{ route('binshopsblog.admin.categories.index') }}">
                {{ \BinshopsBlog\Models\BinshopsCategory::count() }} Categories
            </a>
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
</ul>
