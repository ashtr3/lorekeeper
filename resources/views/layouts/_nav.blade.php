<nav class="navbar navbar-expand-md navbar-dark bg-dark" id="headerNav">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('lorekeeper.settings.site_name', 'Lorekeeper') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    @if (Auth::check() && Auth::user()->is_news_unread && config('lorekeeper.extensions.navbar_news_notif'))
                        <a class="nav-link d-flex text-warning" href="{{ url('news') }}"><strong>{{ __('nav.news') }}</strong><i class="fas fa-bell"></i></a>
                    @else
                        <a class="nav-link" href="{{ url('news') }}">{{ __('nav.news') }}</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Auth::check() && Auth::user()->is_sales_unread && config('lorekeeper.extensions.navbar_news_notif'))
                        <a class="nav-link d-flex text-warning" href="{{ url('sales') }}"><strong>{{ __('nav.sales') }}</strong><i class="fas fa-bell"></i></a>
                    @else
                        <a class="nav-link" href="{{ url('sales') }}">{{ __('nav.sales') }}</a>
                    @endif
                </li>
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a id="inventoryDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ __('nav.home') }}
                        </a>

                        <div class="dropdown-menu" aria-labelledby="inventoryDropdown">
                            <a class="dropdown-item" href="{{ url('characters') }}">
                                {{ __('nav.character_list') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('characters/myos') }}">
                                {{ __('nav.myo_list') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('inventory') }}">
                                {{ __('nav.inventory') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('bank') }}">
                                {{ __('nav.bank') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('comments/liked') }}">
                                {{ __('nav.comments') }}
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="queueDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ __('nav.activity') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="queueDropdown">
                            <a class="dropdown-item" href="{{ url('submissions') }}">
                                {{ __('nav.submissions') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('claims') }}">
                                {{ __('nav.claims') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('reports') }}">
                                {{ __('nav.reports') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('designs') }}">
                                {{ __('nav.designs') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('characters/transfers/incoming') }}">
                                {{ __('nav.transfers') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('trades/open') }}">
                                {{ __('nav.trades') }}
                            </a>
                        </div>
                    </li>
                @endif
                <li class="nav-item dropdown">
                    <a id="browseDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ __('nav.browse') }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="browseDropdown">
                        <a class="dropdown-item" href="{{ url('users') }}">
                            {{ __('nav.users') }}
                        </a>
                        <a class="dropdown-item" href="{{ url('masterlist') }}">
                            {{ __('nav.character_masterlist') }}
                        </a>
                        <a class="dropdown-item" href="{{ url('myos') }}">
                            {{ __('nav.myo_masterlist') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('raffles') }}">
                            {{ __('nav.raffles') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('reports/bug-reports') }}">
                            {{ __('nav.bugs') }}
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a id="loreDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ __('nav.world') }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="loreDropdown">
                        <a class="dropdown-item" href="{{ url('world') }}">
                            {{ __('nav.encyclopedia') }}
                        </a>
                        <a class="dropdown-item" href="{{ url('prompts/prompts') }}">
                            {{ __('nav.prompts') }}
                        </a>
                        <a class="dropdown-item" href="{{ url('shops') }}">
                            {{ __('nav.shops') }}
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('gallery') }}">{{ __('nav.gallery') }}</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    @if (Auth::user()->isStaff)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin') }}"><i class="fas fa-crown"></i></a>
                        </li>
                    @endif
                    @if (Auth::user()->notifications_unread)
                        <li class="nav-item">
                            <a class="nav-link btn btn-secondary btn-sm" href="{{ url('notifications') }}"><span class="fas fa-envelope"></span> {{ Auth::user()->notifications_unread }}</a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a id="browseDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Submit
                        </a>

                        <div class="dropdown-menu" aria-labelledby="browseDropdown">
                            <a class="dropdown-item" href="{{ url('submissions/new') }}">
                                Submit Prompt
                            </a>
                            <a class="dropdown-item" href="{{ url('claims/new') }}">
                                Submit Claim
                            </a>
                            <a class="dropdown-item" href="{{ url('reports/new') }}">
                                Submit Report
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{ Auth::user()->url }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ Auth::user()->url }}">
                                Profile
                            </a>
                            <a class="dropdown-item" href="{{ url('notifications') }}">
                                Notifications
                            </a>
                            <a class="dropdown-item" href="{{ url('account/bookmarks') }}">
                                Bookmarks
                            </a>
                            <a class="dropdown-item" href="{{ url('account/settings') }}">
                                Settings
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
