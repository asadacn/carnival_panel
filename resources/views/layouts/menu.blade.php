<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">Carnival Internet</a>
    </div>

    <ul class="sidebar-menu">

        <li class="menu-header">Core Operations</li>

        <li class="side-menus {{ Request::is('home*') || Request::is('/') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="fas fa-chart-line"></i><span>Dashboard</span>
            </a>
        </li>

        <li class="{{ Request::is('clients*') ? 'active' : '' }}">
            <a href="{{ route('clients.index') }}"><i class="fa fa-users"></i><span>@lang('models/clients.plural')</span></a>
        </li>

        <li class="dropdown {{ Request::is('tickets*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fa fa-ticket-alt"></i><span>Tickets</span></a>
            <ul class="dropdown-menu">
                <li class="{{ Request::is('tickets') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tickets.live') }}"><i class="fa fa-list"></i><span>Active Tickets</span></a>
                </li>
                <li class="{{ Request::is('tickets/analytics') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tickets.analytics') }}"><i class="fa fa-chart-pie"></i><span>Ticket Analytics</span></a>
                </li>
            </ul>
        </li>

        <li class="{{ Request::is('investments*') ? 'active' : '' }}">
            <a href="{{ route('investments.index') }}"><i class="fa fa-money-bill-wave"></i><span>@lang('models/investments.plural')</span></a>
        </li>

        <li class="menu-header">Network & Infrastructure</li>

        <li class="{{ Request::is('packages*') ? 'active' : '' }}">
            <a href="{{ route('packages.index') }}"><i class="fa fa-box-open"></i><span>@lang('models/packages.plural')</span></a>
        </li>

        <li class="{{ Request::is('hotspotZones*') ? 'active' : '' }}">
            <a href="{{ route('hotspotZones.index') }}"><i class="fa fa-wifi"></i><span>@lang('models/hotspotZones.plural')</span></a>
        </li>

        <li class="{{ Request::is('hotspotClients*') ? 'active' : '' }}">
            <a href="{{ route('hotspotClients.index') }}"><i class="fa fa-globe"></i><span>@lang('models/hotspotClients.plural')</span></a>
        </li>

        <li class="{{ Request::is('areas*') ? 'active' : '' }}">
            <a href="{{ route('areas.index') }}"><i class="fa fa-map-marked-alt"></i><span>@lang('models/areas.plural')</span></a>
        </li>


        <li class="menu-header">Staff & Collection</li>

        <li class="{{ Request::is('collectors*') ? 'active' : '' }}">
            <a href="{{ route('collectors.index') }}"><i class="fa fa-user-tie"></i><span>@lang('models/collectors.plural')</span></a>
        </li>

        <li class="{{ Request::is('technicians*') ? 'active' : '' }}">
            <a href="{{ route('technicians') }}"><i class="fa fa-tools"></i><span>Technicians</span></a>
        </li>

        <li class="{{ Request::is('cardSellers*') ? 'active' : '' }}">
            <a href="{{ route('cardSellers.index') }}"><i class="fa fa-credit-card"></i><span>@lang('models/cardSellers.plural')</span></a>
        </li>

        <li class="menu-header">Communication</li>

        {{-- Parent link for SMS with sub-menu (requires front-end JS for collapse/expand) --}}
        <li class="dropdown {{ Request::is('sMSTEMPALTES*', 'sms_log*', 'create_bulk_sms*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fa fa-comments"></i><span>SMS/Messaging</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ Request::is('create_bulk_sms*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('create_bulk_sms') }}"><i class="fa fa-sms"></i><span>Bulk SMS</span></a>
                </li>
                <li class="{{ Request::is('sMSTEMPALTES*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('sMSTEMPALTES.index') }}"><i class="fa fa-file-alt"></i><span>@lang('models/sMSTEMPALTES.plural')</span></a>
                </li>
                <li class="{{ Request::is('sms_log*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('sms_log') }}"><i class="fa fa-history"></i><span>@lang('models/sMSLOGS.plural')</span></a>
                </li>
            </ul>
        </li>

    </ul>
</aside>
