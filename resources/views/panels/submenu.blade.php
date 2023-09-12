{{-- For submenu --}}
<ul class="menu-content dropdown dropdown-menu" aria-labelledby="dropdownMenuButton1">
  @if(isset($menu))
  @foreach($menu as $submenu)
  <li class="nav-item @if(Request::is($submenu->slug.'/*') || Request::is($submenu->slug)) active  @endif" {{ '' }}">
    <a href="{{isset($submenu->url) ? url($submenu->url):'javascript:void(0)'}}" class="nav-link d-flex align-items-center" target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}">
      @if(isset($submenu->icon))
      <i class="menu-icon" data-feather="{{$submenu->icon}}"></i>
      @endif
      <span class="menu-title text-truncate">{{ $submenu->name }}</span>
    </a>
    @if (isset($submenu->submenu))
    @include('panels/submenu', ['menu' => $submenu->submenu])
    @endif
  </li>
  @endforeach
  @endif
</ul>
