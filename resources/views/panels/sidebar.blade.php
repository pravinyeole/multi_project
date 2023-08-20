@php
$configData = Helper::applClasses();
@endphp

<div class="main-menu menu-fixed {{($configData['theme'] === 'dark') ? 'menu-dark' : 'menu-light'}} menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto" style="margin-top: 40px">
                <a class="navbar-brand" href="{{url('/home')}}">
                    <img src="{{asset('images/logo/logo.png')}}" width="100%" />
                </a>
            </li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content" style="margin-top: 60px;background:#62b9dc;">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        
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
                        <a href="{{isset($menu->url)? url($menu->url):'javascript:void(0)'}}" class="d-flex align-items-center" target="{{isset($menu->newTab) ? '_blank':'_self'}}">
                            <i data-feather="{{ $menu->icon }}"></i>
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
        </ul>

        {{-- @if (Session::get('USER_TYPE') == "O" || Session::get('USER_TYPE') == "U" || Session::get('USER_TYPE') == "T" )
        <div class="custom-left" >
            Copyright © <?php echo date('Y')?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp  <a class="" href="https://www.coveragewizard.com/" target="_blank">Coverage Wizard</a>
            <div class="text-dark-f order-2 order-md-1">
            Version:  <span>{{ config('constants.portal_version') }}</span>
            </div>
        </div>
        @endif --}}

        
    </div>
    @if($groupLog != '')
    <div class="custom-left" style="position: fixed;bottom: 0;z-index: 99999;padding: 10px;height: 120px;margin-bottom: 20px">
        Powered By: <br/>
        <div class="text-dark-f order-2 order-md-1" style="height: 100%;">
            <div class="item-img" style="height: 100%;">
                <img src="{{ $groupLog }}" alt="{{$iname}}" class="">
            </div>            
        </div>
    </div>
    @endif
</div>
<!-- END: Main Menu-->
