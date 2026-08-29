<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}" class="d-flex align-items-center justify-content-center gap-2 text-decoration-none">
            <img class="navbar-brand-full app-header-logo" src="{{ isp_logo() }}" style="max-height: 40px; max-width: 130px; object-fit: contain;" alt="{{ isp_name() }}">
        </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{ isp_logo() }}" style="max-height: 35px; max-width: 35px; object-fit: contain;" alt="{{ isp_name() }}"/>
        </a>
    </div>
    <ul class="sidebar-menu">
        @include('layouts.menu')
    </ul>
</aside>
