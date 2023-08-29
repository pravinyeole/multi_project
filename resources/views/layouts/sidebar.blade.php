<nav class="sidebar sidebar-offcanvas" id="sidebar">
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
            <a href="{{isset($menu->url)? url($menu->url):'javascript:void(0)'}}" class="nav-link collapsed" target="{{isset($menu->newTab) ? '_blank':'_self'}}">
                <i class="{{$menu->icon}} menu-icon"></i>
                <span class="menu-title text-truncate">{{ __('locale.'.$menu->name) }}</span>
                @if (isset($menu->badge))
                <?php $badgeClasses = "badge badge-pill badge-light-primary ml-auto mr-1" ?>
                <span class="{{ isset($menu->badgeClass) ? $menu->badgeClass : $badgeClasses }} ">{{$menu->badge}}</span>
                @endif
            </a>

            @if(isset($menu->submenu))
            @include('panels/submenu', ['menu' => $menu->submenu])
            @endif
        </li>
        @endif
        @endforeach

        @php
        $custom_classes = "";
        if(isset($submenuData->classlist)) {
        $custom_classes = $submenuData->classlist;
        }
        @endphp


        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{('/logout')}}">
              <i class="menu-icon mdi mdi-file-document"></i>
              <span class="menu-title">Sign Out</span>
            </a>
          </li>
       
        </li>
    </ul>
</nav>