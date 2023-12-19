<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <a class="navbar-brand brand-logo navbar-logo" href="{{url('/home')}}">
        <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo" />
    </a>
    <a href="javascript:void(0)" class="close" data-bs-toggle="minimize">+</a>
    <div class="user-item">
        <div class="user-info">
            <p class="mb-0 mt-0 font-weight-semibold">{{Auth::User()->user_fname}} {{Auth::User()->user_lname}}</p>
            <p class="fw-light text-muted mb-0">{{Auth::User()->mobile_number}}</p>
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
                <img class="menu-icon" src="{{asset('menu_icon/').'/'.$menu->img_icon}}" width="20" height="20"></img>
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
                <img class="menu-icon" src="{{asset('menu_icon/profile.jpeg')}}" width="20" height="20"></img>
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