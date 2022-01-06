<li class="side-menus {{ Request::is('home*') ? 'active' : '' }}">
    <a class="nav-link" href="{{route('dashboard')}}">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
</li>

<li class="{{ Request::is('clients*') ? 'active' : '' }}">
    <a href="{{ route('clients.index') }}"><i class="fa fa-users"></i><span>@lang('models/clients.plural')</span></a>
</li>

<li class="{{ Request::is('packages*') ? 'active' : '' }}">
    <a href="{{ route('packages.index') }}"><i class="fa fa-box-open"></i><span>@lang('models/packages.plural')</span></a>
</li>

<li class="{{ Request::is('investments*') ? 'active' : '' }}">
    <a href="{{ route('investments.index') }}"><i class="fa fa-money-check-alt"></i><span>@lang('models/investments.plural')</span></a>
</li>

<li class="{{ Request::is('hotspotZones*') ? 'active' : '' }}">
    <a href="{{ route('hotspotZones.index') }}"><i class="fa fa-wifi"></i><span>@lang('models/hotspotZones.plural')</span></a>
</li>

<li class="{{ Request::is('cardSellers*') ? 'active' : '' }}">
    <a href="{{ route('cardSellers.index') }}"><i class="fa fa-credit-card"></i><span>@lang('models/cardSellers.plural')</span></a>
</li>

<li class="{{ Request::is('areas*') ? 'active' : '' }}">
    <a href="{{ route('areas.index') }}"><i class="fa fa-edit"></i><span>@lang('models/areas.plural')</span></a>
</li>

<li class="{{ Request::is('collectors*') ? 'active' : '' }}">
    <a href="{{ route('collectors.index') }}"><i class="fa fa-edit"></i><span>@lang('models/collectors.plural')</span></a>
</li>

