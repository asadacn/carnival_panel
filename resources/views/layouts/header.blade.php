<div class="navbar-inner d-flex align-items-center justify-content-between px-3">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg text-white">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav">
        @if(\Illuminate\Support\Facades\Auth::user())
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center text-white">
                    <img alt="image" src="{{ asset('img/logo.png') }}" class="rounded-circle mr-2 user-thumbnail" style="width: 28px; height: 28px; object-fit: cover;">
                    <span class="d-none d-lg-inline font-weight-medium">{{ \Illuminate\Support\Facades\Auth::user()->first_name }}</span>
                </a>
                <div class="dropdown-menu shadow border-0">
                    <div class="dropdown-header font-weight-bold text-primary mb-1">
                        Welcome, {{ \Illuminate\Support\Facades\Auth::user()->name }}
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item has-icon edit-profile" href="#" data-id="{{ \Auth::id() }}">
                        <i class="fa fa-user mr-2 text-muted"></i>Edit Profile
                    </a>
                    <a class="dropdown-item has-icon" data-toggle="modal" data-target="#changePasswordModal" href="#" data-id="{{ \Auth::id() }}">
                        <i class="fa fa-lock mr-2 text-muted"></i>Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="px-3 py-2">
                        <div class="fw-semibold text-muted small mb-2">{{ __('messages.language') }}</div>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('locale.switch') }}" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="locale" value="en">
                                <button type="submit" class="btn btn-sm w-100 {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    English
                                </button>
                            </form>
                            <form method="POST" action="{{ route('locale.switch') }}" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="locale" value="bn">
                                <button type="submit" class="btn btn-sm w-100 {{ app()->getLocale() === 'bn' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    বাংলা
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        @else
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user text-white">
                    <div class="d-none d-lg-inline">{{ __('messages.common.hello') }}</div>
                </a>
                <div class="dropdown-menu shadow border-0">
                    <div class="dropdown-header font-weight-bold text-primary mb-1">{{ __('messages.common.login') }} / {{ __('messages.common.register') }}</div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('login') }}" class="dropdown-item has-icon">
                        <i class="fas fa-sign-in-alt mr-2 text-muted"></i> {{ __('messages.common.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="dropdown-item has-icon">
                        <i class="fas fa-user-plus mr-2 text-muted"></i> {{ __('messages.common.register') }}
                    </a>
                </div>
            </li>
        @endif
    </ul>
</div>
