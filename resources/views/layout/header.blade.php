<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i
                            class="ficon" data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
            @if(request()->user()->hasRole(['it', 'digital_marketing', 'sales_supervisor', 'sales', 'sales_manager', 'direktur', 'cco', 'cco_manager']))
                <li class="nav-item dropdown dropdown-notification mr-25">
                    <a href="javascript:void(0)" data-toggle="dropdown" class="nav-link">
                        <i class="ficon" data-feather="users"></i>
                        <span class="badge badger-pill badge-danger badge-up" id="badge-leads">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 mr-auto">
                                    @if (request()->user()->hasRole('it'))
                                    Leads dan Booking Service
                                    @elseif(request()->user()->hasRole(['cco', 'cco_manager']))
                                    Booking Service
                                    @else
                                    Leads                                        
                                    @endif
                                </h4>
                            </div>
                        </li>
                        <li class="scrollable-container media-list ps" id="list-leads"></li>
                    </ul>
                </li>
            @endif
            <li class="nav-item dropdown dropdown-notification mr-25">
                <a class="nav-link" href="javascript:void(0);" data-toggle="dropdown">
                    <i class="ficon" data-feather="bell"></i>
                    <span class="badge badge-pill badge-danger badge-up" id="badge-notification">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                        </div>
                    </li>
                    <li class="scrollable-container media-list ps" id="list-notification">
                        {{-- <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-15.jpg" alt="avatar" width="32" height="32"></div>
                                </div>
                                <div class="media-body">
                                    <p class="media-heading"><span class="font-weight-bolder">Congratulation Sam 🎉</span>winner!</p><small class="notification-text"> Won the monthly best seller badge.</small>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-3.jpg" alt="avatar" width="32" height="32"></div>
                                </div>
                                <div class="media-body">
                                    <p class="media-heading"><span class="font-weight-bolder">New message</span>&nbsp;received</p><small class="notification-text"> You have 10 unread messages</small>
                                </div>
                            </div>
                        </a>
                        <a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <div class="avatar bg-light-danger">
                                        <div class="avatar-content">MD</div>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <p class="media-heading"><span class="font-weight-bolder">Revised Order 👋</span>&nbsp;checkout</p><small class="notification-text"> MD Inc. order updated</small>
                                </div>
                            </div>
                        </a> --}}

                        {{-- <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div> --}}
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name font-weight-bolder">{{ auth()->user()->name }}</span><span
                            class="user-status">{{ strtoupper(auth()->user()->getRoleNames()[0]) }}</span></div>
                    <span class="avatar"><img class="round"
                            src="{{ asset('') }}storage/images/profile/small_{{ auth()->user()->foto }}"
                            alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="mr-50" data-feather="user"></i> Profile
                    </a>
                    @if (session('isAdmin'))
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item login-as">
                            <i class="mr-50" data-feather="user"></i> Login as
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout').submit()">
                        <i class="mr-50" data-feather="power"></i> Logout
                    </a>
                </div>

            </li>
        </ul>
    </div>
</nav>
