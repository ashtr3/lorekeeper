<ul>
    <li class="sidebar-header"><a href="{{ route('home') }}" class="card-link">Home</a></li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Account</div>
        <div class="sidebar-item"><a href="{{ route('notifications.index') }}" class="{{ set_active('notifications') }}">Notifications</a></div>
        <div class="sidebar-item"><a href="{{ route('account.settings') }}" class="{{ set_active('account/settings') }}">Settings</a></div>
        <div class="sidebar-item"><a href="{{ route('account.aliases') }}" class="{{ set_active('account/aliases') }}">Aliases</a></div>
        <div class="sidebar-item"><a href="{{ route('account.bookmarks.index') }}" class="{{ set_active('account/bookmarks') }}">Bookmarks</a></div>
        <div class="sidebar-item"><a href="{{ route('account.deactivate') }}" class="{{ set_active('account/deactivate') }}">Deactivate</a></div>
    </li>
</ul>
