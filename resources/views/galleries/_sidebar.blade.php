<ul>
    <li class="sidebar-header"><a href="{{ route('browse.gallery.index') }}" class="card-link">Gallery</a></li>

    @auth
        <li class="sidebar-section">
            <div class="sidebar-section-header">My Submissions</div>
            <div class="sidebar-item"><a href="{{ route('gallery.submissions.index', 'pending') }}" class="{{ set_active('gallery/submissions*') }}">My Submission Queue</a></div>
            <div class="sidebar-item"><a href="{{ route('browse.user.gallery', Auth::user()->name) }}" class="{{ set_active('user/' . Auth::user()->name . '/gallery') }}">My Gallery</a></div>
            <div class="sidebar-item"><a href="{{ route('browse.user.favorites', Auth::user()->name) }}" class="{{ set_active('user/' . Auth::user()->name . '/favorites') }}">My Favorites</a></div>
        </li>
    @endauth

    @if (config('lorekeeper.extensions.show_all_recent_submissions.enable') && config('lorekeeper.extensions.show_all_recent_submissions.links.sidebar'))
        <li class="sidebar-section">
            <div class="sidebar-item"><a href="{{ route('browse.gallery.all') }}" class="{{ set_active('gallery/all') }}">All Recent Submissions</a></div>
        </li>
    @endif

    @if ($galleryPage && $sideGallery->children->count())
        <li class="sidebar-section">
            <div class="sidebar-section-header">{{ $sideGallery->name }}: Sub-Galleries</div>
            @foreach ($sideGallery->children()->visible()->get() as $child)
                <div class="sidebar-item"><a href="{{ route('browse.gallery.show', $child->id) }}" class="{{ set_active('gallery/' . $child->id) }}">{{ $child->name }}</a></div>
            @endforeach
        </li>
    @endif

    @if ($galleryPage && $sideGallery->siblings() && $sideGallery->siblings->count())
        <li class="sidebar-section">
            <div class="sidebar-section-header">{{ $sideGallery->parent->name }}: Sub-Galleries</div>
            @foreach ($sideGallery->siblings()->visible()->get() as $sibling)
                <div class="sidebar-item"><a href="{{ route('browse.gallery.show', $sibling->id) }}" class="{{ set_active('gallery/' . $sibling->id) }}">{{ $sibling->name }}</a></div>
            @endforeach
        </li>
    @endif

    @if ($galleryPage && $sideGallery->avunculi() && $sideGallery->avunculi->count())
        <li class="sidebar-section">
            <div class="sidebar-section-header">{{ $sideGallery->parent->parent->name }}: Sub-Galleries</div>
            @foreach ($sideGallery->avunculi()->visible()->get() as $avunculus)
                <div class="sidebar-item"><a href="{{ route('browse.gallery.show', $avunculus->id) }}" class="{{ set_active('gallery/' . $avunculus->id) }}">{{ $avunculus->name }}</a></div>
            @endforeach
        </li>
    @endif

    <li class="sidebar-section">
        <div class="sidebar-section-header">Galleries</div>
        @foreach ($sidebarGalleries as $gallery)
            <div class="sidebar-item"><a href="{{ route('browse.gallery.show', $gallery->id) }}" class="{{ set_active('gallery/' . $gallery->id) }}">{{ $gallery->name }}</a></div>
        @endforeach
    </li>
</ul>
