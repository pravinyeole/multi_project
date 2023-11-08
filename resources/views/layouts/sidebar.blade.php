<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <a class="navbar-brand brand-logo navbar-logo" href="{{url('/home')}}">
        <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo" />
    </a>
    <a href="javascript:void(0)" class="close" data-bs-toggle="minimize">+</a>
    <div class="user-item">
        <div class="user-img">
            <img class="img-xs rounded-circle" src="{{asset('images/faces/face8.jpg')}}" alt="Profile image">
        </div>
        <div class="user-info">
            <p class="mb-0 mt-0 font-weight-semibold">{{Auth::User()->mobile_number}}</p>
            <p class="fw-light text-muted mb-0">{{Auth::User()->email}}</p>
        </div>
    </div>
    <ul class="nav">
        @if(Auth::user()->getRole() == 'A')
        @php $menuData = $menuData[0]; @endphp
        @elseif(Auth::user()->getRole() == 'U')
        @php $menuData = $menuData[1]; @endphp
        @elseif(Session::get('USER_TYPE') == 'O' || Session::get('USER_TYPE') == 'OA')
        @php $menuData = $menuData[3]; @endphp
        @elseif(Session::get('USER_TYPE') == 'T')
        @php $menuData = $menuData[5]; @endphp
        @else
        @php $menuData = $menuData[4]; @endphp
        @endif

        @if(isset($menuData))
        @foreach($menuData->menu as $menu)
        @if(isset($menu->navheader))
        <li class="navigation-header">
            <span>{{ __('locale.'.$menu->navheader) }}</span>
            <i data-feather="more-horizontal"></i>
        </li>
        @else
        {{-- Add Custom Class with nav-item --}}
        @php
        $custom_classes = "";
        if(isset($menu->classlist)) {
        $custom_classes = $menu->classlist;
        }
        @endphp
        <li class="nav-item @if(Request::is($menu->slug.'/*') || Request::is($menu->slug)) active  @endif" {{ $custom_classes }}">
            <a href="{{isset($menu->url)? url($menu->url):'javascript:void(0)'}}" class="nav-link collapsed @if(isset($menu->submenu)) submenu @endif" target="{{isset($menu->newTab) ? '_blank':'_self'}}">
                @if(isset($menu->img_icon) && $menu->img_icon != null)
                <img class="menu-icon" src="{{url('menu_icon/').'/'.$menu->img_icon}}" width="20" height="20"></img>
                @else
                <i class="menu-icon" data-feather="{{$menu->icon}}"></i>
                @endif
                <span class="menu-title text-truncate">{{ __('locale.'.$menu->name) }}</span>
                @if (isset($menu->badge))
                <?php $badgeClasses = "badge badge-pill badge-light-primary ml-auto mr-1" ?>
                <span class="{{ isset($menu->badgeClass) ? $menu->badgeClass : $badgeClasses }} ">{{$menu->badge}}</span>
                @endif
            </a>

            @if(isset($menu->submenu))
            <span class="submenu-achor dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"></span>
            @include('panels/submenu', ['menu' => $menu->submenu])
            @endif
        </li>
        @endif
        @endforeach
        <li class="nav-item ">
            <a href="{{ url('two-fact-auth/updateProfile') }}" class="nav-link collapsed " target="_self">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key menu-icon">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                </svg>
                <span class="menu-title text-truncate">My Profile</span>
            </a>

        </li>
        @php
        $custom_classes = "";
        if(isset($submenuData->classlist)) {
        $custom_classes = $submenuData->classlist;
        }
        @endphp


        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{('/logout')}}" onclick="return confirm('Are you sure to logout?');">
                <i class="menu-icon" data-feather="power"></i>
                <span class="menu-title">Sign Out</span>
            </a>
        </li>

        </li>
    </ul>
</nav>