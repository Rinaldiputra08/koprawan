<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="/dashboard"><span class="brand-logo">
                        {{-- <svg viewbox="0 0 180 180" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="#766bf0" fill-rule="evenodd">
                                <g id="Artboard" transform="translate(0.000000,200.000000) scale(0.100000,-0.100000)">
                                    <path class="text-primary" id="Path" d="M838 1800 c-306 -52 -554 -276 -629 -569 -98 -382 88 -782 448 -964
                                105 -54 217 -79 348 -80 66 -1 147 5 180 12 191 42 370 161 473 315 111 167
                                163 384 133 556 l-8 45 -2 -43 c-2 -69 -31 -181 -67 -260 -115 -251 -336 -414
                                -611 -452 -135 -18 -289 11 -402 77 -53 30 -150 105 -151 116 0 4 28 7 63 7
                                l62 1 75 172 75 172 87 3 87 3 -38 -88 c-21 -48 -57 -127 -79 -175 l-41 -88
                                148 0 147 0 76 173 c42 94 100 224 129 287 129 287 123 337 -47 373 -79 17
                                -236 37 -284 37 -18 -1 -10 -7 30 -23 63 -25 100 -71 100 -124 0 -36 -35 -149
                                -52 -170 -10 -12 -168 -20 -168 -8 0 2 24 60 54 127 39 86 51 125 43 130 -26
                                14 -136 37 -195 40 l-62 3 -156 -345 c-85 -190 -159 -349 -164 -353 -13 -14
                                -68 142 -80 230 -18 135 9 291 72 418 120 238 333 392 591 427 l92 13 -50 7
                                c-66 8 -169 8 -227 -2z" />
                                    <path id="Path1" d="M1560 1408 c0 -125 3 -145 29 -225 50 -153 49 -284 -4 -436 -13 -39
                                -23 -72 -21 -74 5 -5 48 54 79 107 14 25 38 81 53 125 24 69 28 96 28 200 1
                                99 -3 133 -22 195 -26 83 -65 157 -111 210 l-30 35 -1 -137z" />
                                </g>
                            </g>

                        </svg> --}}
                        <img src="{{ asset('assets/images/logo/koprawan.png') }}" alt="logo">
                    </span>
                    <h2 class="brand-text">KOPRAWAN</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }} nav-item">
                <a class="d-flex align-items-center" href="/dashboard">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>

            @role('user')
            <li class="{{ Request::segment(1) === 'orders' ? 'active' : null }} nav-item">
                <a class="d-flex align-items-center" href="{{ url('/orders') }}">
                    <i data-feather="shopping-cart"></i>
                    <span class="menu-title text-truncate">Order List</span>
                </a>
            </li>
            @endrole

            @foreach (getNavigations() as $jb => $navs)
                @php
                    $header = '';
                @endphp
                @foreach ($navs as $nav)
                    @can('read ' . $nav->url)
                        @php
                            $header = $header === '' ? true : false;
                        @endphp
                        @if ($header and $jb != '')
                            <li class="navigation-header mt-1">
                                <span data-i18n="Apps &amp; Pages">{{ $jb }}</span>
                                <i data-feather="more-horizontal"></i>
                            </li>
                            @php
                                $header = false;
                            @endphp
                        @endif

                        <li class="{{ Request::segment(1) === $nav->url ? 'open' : null }} nav-item">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="{{ $nav->icon }}"></i>
                                <span class="menu-title" data-i18n="{{ $nav->nama_menu }}">{{ $nav->nama_menu }}</span>
                            </a>

                            <ul class="menu-content">
                                @foreach ($nav->subMenus as $sm)
                                    @can('read ' . $sm->url)
                                        <li class="{{ Request::segment(1) . '/' . Request::segment(2) === $sm->url ? 'active' : null }}">
                                            <a class="d-flex align-items-center" href="{{ url($sm->url) }}">
                                                <i data-feather="{{ $sm->icon }}"></i>
                                                <span class="menu-item" data-i18n="{{ $sm->nama_menu }}">{{ $sm->nama_menu }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endforeach
                            </ul>
                        </li>
                    @endcan
                @endforeach
            @endforeach

            <li>
                <a class="d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout').submit()
                    "><i data-feather="log-out"></i><span class="menu-item text-truncate"
                        data-i18n="Logout">Logout</span>
                    <form id="logout" method="POST" action="{{ route('logout') }}">
                        @csrf
                    </form>
                </a>
            </li>
        </ul>
    </div>
</div>