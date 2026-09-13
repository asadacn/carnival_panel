<div class="navbar-inner d-flex align-items-center justify-content-between px-3">
    <div class="d-flex align-items-center gap-2">
        <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg navbar-icon-btn" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </a>
        @if(\Illuminate\Support\Facades\Auth::user())
            <div class="d-none d-md-flex align-items-center navbar-page-title">
                <span>@yield('title', __('messages.dashboard_title'))</span>
            </div>
        @endif
    </div>

    <ul class="navbar-nav flex-row align-items-center gap-1">
        <li class="nav-item d-none d-sm-flex">
            <a href="#" class="nav-link nav-link-lg navbar-icon-btn" id="fullscreenBtn" title="Toggle Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        @if(\Illuminate\Support\Facades\Auth::user())
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center">
                    <span class="user-avatar me-2 d-flex align-items-center justify-content-center text-uppercase fw-bold"
                        style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        {{ strtoupper(substr(\Illuminate\Support\Facades\Auth::user()->name ?? 'U', 0, 1)) }}
                    </span>
                    <span class="d-none d-lg-inline fw-medium text-dark">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down ms-2" style="font-size: 10px; color: #9ca3af;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 navbar-dropdown">
                    <div class="dropdown-header text-center pb-2 mb-1">
                        <div class="d-flex flex-column align-items-center">
                            <span class="user-avatar-lg mb-1 text-uppercase fw-bold"
                                style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                {{ strtoupper(substr(\Illuminate\Support\Facades\Auth::user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <span class="fw-semibold text-dark d-block">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                            <span class="text-muted small">{{ \Illuminate\Support\Facades\Auth::user()->email }}</span>
                        </div>
                    </div>
                    <div class="px-3 py-2 border-top border-bottom mb-2 lang-switch-wrapper">
                        <div class="fw-semibold text-muted small mb-2">{{ __('messages.language') }}</div>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('locale.switch') }}" class="flex-grow-1 m-0">
                                @csrf
                                <input type="hidden" name="locale" value="en">
                                <button type="submit"
                                    class="btn btn-sm w-100 lang-switch-btn {{ app()->getLocale() === 'en' ? 'lang-switch-btn-active' : 'lang-switch-btn-inactive' }}">
                                    English
                                </button>
                            </form>
                            <form method="POST" action="{{ route('locale.switch') }}" class="flex-grow-1 m-0">
                                @csrf
                                <input type="hidden" name="locale" value="bn">
                                <button type="submit"
                                    class="btn btn-sm w-100 lang-switch-btn {{ app()->getLocale() === 'bn' ? 'lang-switch-btn-active' : 'lang-switch-btn-inactive' }}">
                                    বাংলা
                                </button>
                            </form>
                        </div>
                    </div>
                    <a class="dropdown-item has-icon edit-profile" href="#" data-id="{{ \Auth::id() }}">
                        <i class="fa fa-user mr-2 text-muted"></i> {{ __('messages.edit_profile') }}
                    </a>
                    <a class="dropdown-item has-icon" data-toggle="modal"
                        data-target="#changePasswordModal" href="#" data-id="{{ \Auth::id() }}">
                        <i class="fa fa-lock mr-2 text-muted"></i> {{ __('messages.change_password') }}
                    </a>
                    <div class="dropdown-divider my-2"></div>
                    <a href="{{ url('logout') }}"
                        class="dropdown-item has-icon text-danger fw-medium"
                        onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        @else
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center">
                    <span class="d-none d-lg-inline">{{ __('messages.common.hello') }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 navbar-dropdown">
                    <div class="dropdown-header font-weight-bold mb-1">
                        {{ __('messages.common.login') }} / {{ __('messages.common.register') }}
                    </div>
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
