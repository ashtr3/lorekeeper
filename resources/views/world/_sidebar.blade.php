<ul>
    <li class="sidebar-header"><a href="{{ route('browse.world.index') }}" class="card-link">Encyclopedia</a></li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Characters</div>
        <div class="sidebar-item"><a href="{{ route('browse.world.species') }}" class="{{ set_active('world/species*') }}">Species</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.subtypes') }}" class="{{ set_active('world/subtypes*') }}">Subtypes</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.rarities') }}" class="{{ set_active('world/rarities*') }}">Rarities</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.trait-categories') }}" class="{{ set_active('world/trait-categories*') }}">Trait Categories</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.traits') }}" class="{{ set_active('world/traits*') }}">All Traits</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.character-categories') }}" class="{{ set_active('world/character-categories*') }}">Character Categories</a></div>
    </li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Items</div>
        <div class="sidebar-item"><a href="{{ route('browse.world.item-categories') }}" class="{{ set_active('world/item-categories*') }}">Item Categories</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.items') }}" class="{{ set_active('world/items*') }}">All Items</a></div>
        <div class="sidebar-item"><a href="{{ route('browse.world.currencies') }}" class="{{ set_active('world/currencies*') }}">Currencies</a></div>
    </li>
</ul>
