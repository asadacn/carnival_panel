<style>
    .sidebar-menu .menu-item-featured > a {
        border-radius: 12px;
        margin: 0.25rem 0.45rem 0.55rem;
        padding: 0.78rem 0.95rem;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .sidebar-menu .menu-item-featured > a i {
        margin-right: 0.6rem;
        color: #64748b;
    }

    .sidebar-menu .menu-item-featured > a span {
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    .sidebar-menu .menu-item-featured.active > a {
        background: linear-gradient(90deg, rgba(37, 99, 235, 0.12), rgba(14, 165, 233, 0.10));
        border: 1px solid rgba(37, 99, 235, 0.18);
        box-shadow: 0 8px 18px -10px rgba(37, 99, 235, 0.3);
    }

    .sidebar-menu .menu-item-featured.active > a i {
        color: #2563eb;
    }

    .sidebar-menu .menu-item-featured.active > a span {
        font-weight: 700;
    }

    .sidebar-menu .menu-item-featured .dropdown-menu {
        margin: 0.2rem 0.5rem 0.5rem;
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 10px 20px -12px rgba(15, 23, 42, 0.25);
    }

    .sidebar-menu .menu-item-featured .dropdown-menu li a {
        border-radius: 8px;
        margin: 0.2rem;
    }

    .sidebar-menu li.dropdown.menu-item-featured > .dropdown-menu {
        display: none;
    }

    .sidebar-menu li.dropdown.menu-item-featured.active > .dropdown-menu,
    .sidebar-menu li.dropdown.menu-item-featured:has(.dropdown-menu li.active) > .dropdown-menu {
        display: block;
    }
</style>

<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">{{ isp_name() }}</a>
    </div>

    <ul class="sidebar-menu">

        <li class="menu-header">@lang('menu.core_operations')</li>

        <li class="side-menus menu-item-featured {{ Request::is('home*') || Request::is('/') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="fas fa-chart-line"></i><span>@lang('menu.dashboard')</span>
            </a>
        </li>

        <li class="menu-item-featured {{ Request::is('clients') || Request::is('clients/*') && !Request::is('clients/closed*') ? 'active' : '' }}">
            <a href="{{ route('clients.index') }}"><i class="fa fa-users"></i><span>@lang('models/clients.plural')</span></a>
        </li>

        <li class="menu-item-featured {{ Request::is('clients/closed*') ? 'active' : '' }}">
            <a href="{{ route('clients.closed') }}"><i class="fas fa-user-slash"></i><span>@lang('menu.closed_clients')</span></a>
        </li>

        <li class="dropdown menu-item-featured {{ Request::is('due-bills*', 'due-bill-payments*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fa fa-file-invoice-dollar"></i><span>@lang('menu.billing_collections')</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ Request::is('due-bills*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('due-bills.index') }}"><i class="fa fa-receipt"></i><span>@lang('menu.due_bills')</span></a>
                </li>
                <li class="{{ Request::is('due-bill-payments*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('due-bill-payments.index') }}"><i class="fa fa-credit-card"></i><span>@lang('menu.payments')</span></a>
                </li>
                <li class="{{ Request::is('due-bills/report*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('due-bills.report') }}"><i class="fa fa-chart-bar"></i><span>@lang('menu.billing_report')</span></a>
                </li>
            </ul>
        </li>

        <li class="dropdown menu-item-featured {{ Request::is('sMSTEMPALTES*', 'sms_log*', 'create_bulk_sms*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fa fa-comments"></i><span>@lang('menu.sms_messaging')</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ Request::is('create_bulk_sms*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('create_bulk_sms') }}"><i class="fa fa-sms"></i><span>@lang('menu.bulk_sms')</span></a>
                </li>
                <li class="{{ Request::is('sMSTEMPALTES*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('sMSTEMPALTES.index') }}"><i class="fa fa-file-alt"></i><span>@lang('models/sMSTEMPALTES.plural')</span></a>
                </li>
                <li class="{{ Request::is('sms_log*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('sms_log') }}"><i class="fa fa-history"></i><span>@lang('models/sMSLOGS.plural')</span></a>
                </li>
            </ul>
        </li>

        <li class="dropdown menu-item-featured {{ Request::is('tickets*') || Request::is('tickets') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fa fa-ticket-alt"></i><span>@lang('menu.tickets')</span></a>
            <ul class="dropdown-menu">
                <li class="{{ Request::is('tickets') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tickets.live') }}"><i class="fa fa-list"></i><span>@lang('menu.active_tickets')</span></a>
                </li>
                <li class="{{ Request::is('tickets/analytics') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tickets.analytics') }}"><i class="fa fa-chart-pie"></i><span>@lang('menu.ticket_analytics')</span></a>
                </li>
            </ul>
        </li>

        <li class="menu-item-featured {{ Request::is('investments*') ? 'active' : '' }}">
            <a href="{{ route('investments.index') }}"><i class="fa fa-money-bill-wave"></i><span>@lang('models/investments.plural')</span></a>
        </li>

        <li class="menu-header">@lang('menu.network_infrastructure')</li>

        <li class="menu-item-featured {{ Request::is('packages*') ? 'active' : '' }}">
            <a href="{{ route('packages.index') }}"><i class="fa fa-box-open"></i><span>@lang('models/packages.plural')</span></a>
        </li>

        <li class="menu-item-featured {{ Request::is('hotspotZones*') ? 'active' : '' }}">
            <a href="{{ route('hotspotZones.index') }}"><i class="fa fa-wifi"></i><span>@lang('models/hotspotZones.plural')</span></a>
        </li>

        <li class="menu-item-featured {{ Request::is('hotspotClients*') ? 'active' : '' }}">
            <a href="{{ route('hotspotClients.index') }}"><i class="fa fa-globe"></i><span>@lang('models/hotspotClients.plural')</span></a>
        </li>

        <li class="menu-item-featured {{ Request::is('areas*') ? 'active' : '' }}">
            <a href="{{ route('areas.index') }}"><i class="fa fa-map-marked-alt"></i><span>@lang('models/areas.plural')</span></a>
        </li>


        <li class="menu-header">@lang('menu.staff_collection')</li>

        <li class="menu-item-featured {{ Request::is('collectors*') ? 'active' : '' }}">
            <a href="{{ route('collectors.index') }}"><i class="fa fa-user-tie"></i><span>@lang('models/collectors.plural')</span></a>
        </li>

        <li class="menu-item-featured {{ Request::is('technicians*') ? 'active' : '' }}">
            <a href="{{ route('technicians') }}"><i class="fa fa-tools"></i><span>@lang('menu.technicians')</span></a>
        </li>

        <li class="menu-item-featured {{ Request::is('cardSellers*') ? 'active' : '' }}">
            <a href="{{ route('cardSellers.index') }}"><i class="fa fa-credit-card"></i><span>@lang('models/cardSellers.plural')</span></a>
        </li>

        <li class="menu-header">@lang('menu.settings')</li>

        <li class="menu-item-featured {{ Request::is('isp-settings*') ? 'active' : '' }}">
            <a href="{{ route('isp-settings.index') }}"><i class="fa fa-sliders-h"></i><span>@lang('menu.isp_settings')</span></a>
        </li>

    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const activeDropdown = document.querySelector('.sidebar-menu li.dropdown.menu-item-featured.active');
        if (activeDropdown) {
            const menu = activeDropdown.querySelector(':scope > .dropdown-menu');
            if (menu) {
                menu.style.display = 'block';
            }
        }

        document.querySelectorAll('.sidebar-menu li.dropdown.menu-item-featured').forEach(function (item) {
            const childActive = item.querySelector('.dropdown-menu li.active');
            if (childActive) {
                const menu = item.querySelector(':scope > .dropdown-menu');
                if (menu) {
                    menu.style.display = 'block';
                }
            }
        });
    });
</script>
