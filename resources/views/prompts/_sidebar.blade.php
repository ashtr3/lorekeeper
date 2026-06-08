<ul>
    <li class="sidebar-header">
        <a href="{{ route('browse.prompts.index') }}" class="card-link">Prompts</a>
    </li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Prompts</div>
        <div class="sidebar-item"><a href="{{ route('browse.prompts.categories') }}" class="{{ set_active('prompts/prompt-categories*') }}">Prompt Categories</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.prompts.list') }}" class="{{ set_active('prompts/prompts*') }}">All Prompts</a></div>
    </li>
</ul>
