@if($configData["mainLayoutType"] === 'horizontal' && isset($configData["mainLayoutType"]))

<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center {{ $configData['navbarColor'] }}" data-nav="brand-center">
  <div class="navbar-header d-xl-block d-none">
    <ul class="nav navbar-nav">
      <li class="nav-item">
        <a class="navbar-brand" href="{{url('/home')}}">
          <span class="brand-logo">
        <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
          <defs>
            <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
              <stop stop-color="#000000" offset="0%"></stop>
              <stop stop-color="#FFFFFF" offset="100%"></stop>
            </lineargradient>
            <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
              <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
              <stop stop-color="#FFFFFF" offset="100%"></stop>
            </lineargradient>
          </defs>
          <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="Artboard" transform="translate(-400.000000, -178.000000)">
              <g id="Group" transform="translate(400.000000, 178.000000)">
                <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
              </g>
            </g>
          </g>
        </svg></span>
        <h2 class="brand-text mb-0">Coverage Wizard</h2>
        </a>
      </li>
    </ul>
  </div>
  @else
      <nav class="header-navbar navbar navbar-expand-lg align-items-center {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }}">
  @endif
  @php $sid = 0; $teamName = ""; $iAname = ""; $uname = ""; @endphp

  @if(Session::get('USER_TYPE') == 'S')
    @php 
    $sid    = Session::get('super_admin_id');
    $iAname = $iname;
    @endphp 
  @elseif(Session::get('USER_TYPE') == 'A')
    @php 
    $sid    = Session::get('sub_org_id');
    if($sid == Auth::id() || $sid =="" ){
      $sid    = Session::get('org_id');
    }
    $iAname = $iname;
    $uname = $usname;
    @endphp    
  @elseif(Session::get('USER_TYPE') == 'T')
    @php 
    $sid      = Session::get('sub_org_id');
    if($sid == ""){
        $sid = Session::get('org_id');
    }
    $iAname   = $iname;
    $teamName = $tname;
    $uname = Auth::user()->user_fname." ".Auth::user()->user_lname;
    @endphp    
  @elseif(Session::get('USER_TYPE') == 'U')
      @php $sid = Session::get('team_id');
      if($sid == ""){
        $sid = Session::get('sub_org_id');
      }
      if($sid == ""){
        $sid = Session::get('org_id');
      }
      $iAname = $iname;
      $teamName = $tname;
      $uname = Auth::user()->user_fname." ".Auth::user()->user_lname;
      @endphp
    
  @endif

    <div class="navbar-container d-flex content" style="border:2px solid #8aeed4;">
        <ul class="nav navbar-nav d-xl-none" style="margin-top: 3%">
            <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
          </ul>
        <div class="bookmark-wrapper d-flex align-items-center">
          @if(Request::path() == 'users/profile')
            <h5 class="content-header-title float-left mb-0">Coverage Wizard Account Settings</h5>
          @else
            <h5 class="content-header-title float-left mb-0">@if($iAname!=''){{$iAname}} - @endif @if($teamName!='') {{$teamName}} - @endif @if($uname!='') {{$uname}} - @endif @yield('title')</h5> 
          @endif
        </div>

        <ul class="nav navbar-nav align-items-center ml-auto">

        {{-- @if($sid!= '')
          <a>
              <div class="input-group-text cursor-pointer existAccess" data-logoutuserid={{ $sid }} style="color: #d33333;margin-right: 0;float: right;font-size: 12px"  ><i data-feather="power"> </i>
              <b> Exit Access </b>
          </div></a>
        @endif --}}

        @if(Session::get('SHOW_MENU') == 'Yes')
          &nbsp;<a>
              <div class="input-group-text cursor-pointer changeAgency" style="color: green;margin-right: 0;float: right;font-size: 12px" ><i data-feather="power"> </i>
              <b>&nbsp;Change Agency </b>
          </div></a>
        @endif

        <li class="nav-item dropdown dropdown-user">
          <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="user-nav d-sm-flex d-none">

              <span class="user-name font-weight-bolder">{{Auth::user()->user_fname}} {{Auth::user()->user_lname}}</span>
              <span class="user-status">
              @if(Auth::user()->getRole() == 'S') 
                (Super Admin) 
              @elseif(Auth::user()->getRole() == 'A')
                (Admin)
              @elseif(Auth::user()->getRole() == 'U')
                (User)
              
              @endif</span>
            </div>
            <span class="avatar">
              @if((Auth::user()->user_role == 'S'))
              <img class="round" src="{{asset('images/portrait/small/user9595.jpg')}}" alt="avatar" height="40" width="40">
              <span class="avatar-status-online"></span>
              @endif

              @if((Auth::user()->user_role == 'A'))
              <img class="round" src="{{asset('images/portrait/small/8.png')}}" alt="avatar" height="40" width="40">
              <span class="avatar-status-online"></span>
              @endif

              @if((Auth::user()->user_role == 'U'))
              <img class="round" src="{{asset('images/portrait/small/8.png')}}" alt="avatar" height="40" width="40">
              <span class="avatar-status-online"></span>
              @endif
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
            <a class="dropdown-item" href="{{url('users/profile')}}">
              <i class="mr-50" data-feather="user"></i> Account Settings
            </a>
            <a class="dropdown-item logout"  >
              <i class="mr-50" data-feather="power" ></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>


  {{-- if main search not found! --}}
  <ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion justify-content-between">
      <a class="d-flex align-items-center justify-content-between w-100 py-50">
        <div class="d-flex justify-content-start">
          <span class="mr-75" data-feather="alert-circle"></span>
          <span>No results found.</span>
        </div>
      </a>
    </li>
  </ul>
  {{-- Search Ends --}}
  <!-- END: Header-->
