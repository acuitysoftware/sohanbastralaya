
<div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid">
        @php
        $siteSettings = App\Models\Setting::first();
        @endphp
        <!-- LOGO -->
        <a href="{{route('login')}}" class="topnav-logo">
            <span class="topnav-logo-lg">
                <img src="{{asset('storage/app/public/'.@$siteSettings->site_logo) }}" alt="">
            </span>
            <span class="topnav-logo-sm">
                <img src="{{asset('storage/app/public/'.@$siteSettings->site_logo) }}" alt="" height="16">
            </span>
        </a>

        <ul class="list-unstyled topbar-menu float-end mb-0">


            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="account-user-avatar"> 
                        <img src="{{asset('public/assets/images/avatar-10.jpg')}}" alt="user-image" class="rounded-circle">
                    </span>
                    <span>
                        <span class="account-user-name">Sohan Bastralaya</span><span class="account-position">Founder</span>
                        
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span>My Account</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-edit me-1"></i>
                        <span>Settings</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-lifebuoy me-1"></i>
                        <span>Support</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-lock-outline me-1"></i>
                        <span>Lock Screen</span>
                    </a> -->

                    <!-- item-->
                    <a href="javascript:void(0);" onclick="event.preventDefault();
                                document.getElementById('user-logout-form').submit();" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout me-1"></i>
                        <span>Logout</span>
                    </a>
                    <form id="user-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                       @csrf
                    </form>
                </div>
            </li>

        </ul>
        <a class="button-menu-mobile disable-btn">
            <div class="lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </a>
    </div>
</div>
<!-- end Topbar