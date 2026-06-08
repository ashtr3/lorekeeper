@extends('layouts.app')

@section('title')
    Character{!! View::hasSection('profile-title') ? ' :: ' . trim(View::getSection('profile-title')) : '' !!}
@endsection

@section('sidebar')
    @include('character.' . ($isMyo ? 'myo.' : '') . '_sidebar')
@endsection

@section('content')
    @yield('profile-content')
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.bookmark-button').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                loadModal($this.data('id') ? "{{ route('account.bookmarks.edit', ':id') }}".replace(':id', $this.data('id')) : "{{ route('account.bookmarks.create') }}?character_id=" + $this.data('character-id'), $this.data('id') ? 'Edit Bookmark' :
                    'Bookmark Character');
            });
        });
    </script>
@endsection
