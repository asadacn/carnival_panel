@extends('layouts.app')

@section('title')
    @lang('models/packages.plural')
@endsection

@section('css')
<style>
    .packages-page { background: #f6f8fc; min-height: calc(100vh - 64px); padding: 1.5rem 0 3rem; }
    .packages-hero { position: relative; overflow: hidden; background: linear-gradient(120deg, #172554, #1e3a8a 58%, #2563eb); border-radius: 18px; padding: 1.65rem 1.8rem; color: #fff; box-shadow: 0 14px 30px rgba(30, 58, 138, .18); }
    .packages-hero::after { content: ''; position: absolute; width: 220px; height: 220px; right: -65px; top: -105px; border: 35px solid rgba(255,255,255,.08); border-radius: 50%; }
    .packages-hero-content { position: relative; z-index: 1; }
    .packages-eyebrow { font-size: .72rem; letter-spacing: .12em; text-transform: uppercase; font-weight: 700; color: #bfdbfe; margin-bottom: .45rem; }
    .packages-hero h1 { font-size: clamp(1.35rem, 2vw, 1.8rem); font-weight: 800; margin: 0 0 .35rem; }
    .packages-hero p { color: #dbeafe; margin: 0; max-width: 580px; font-size: .9rem; }
    .packages-add { background: #fff; color: #1d4ed8; border: 0; border-radius: 10px; padding: .62rem .95rem; font-size: .84rem; font-weight: 700; display: inline-flex; align-items: center; gap: .45rem; text-decoration: none; white-space: nowrap; transition: transform .2s, box-shadow .2s; }
    .packages-add:hover { color: #1d4ed8; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,.16); }
    .package-stat { background: #fff; border: 1px solid #e5eaf2; border-radius: 14px; padding: 1rem 1.1rem; height: 100%; display: flex; align-items: center; gap: .8rem; }
    .package-stat-icon { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 11px; flex-shrink: 0; }
    .package-stat-label { display: block; color: #64748b; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
    .package-stat-value { display: block; color: #172033; font-size: 1.2rem; font-weight: 800; line-height: 1.25; }
    .packages-toolbar { background: #fff; border: 1px solid #e5eaf2; border-radius: 14px; padding: .8rem 1rem .65rem; }
    .packages-toolbar-main { display: flex; align-items: center; gap: .85rem; flex-wrap: wrap; margin-bottom: .65rem; }
    .packages-toolbar-label { display: inline-flex; align-items: center; gap: .45rem; color: #172033; font-size: .86rem; font-weight: 800; white-space: nowrap; }
    .packages-toolbar-label i { color: #2563eb; width: 17px; height: 17px; }
    .package-search { position: relative; flex: 1 1 260px; }
    .package-search input { width: 100%; border: 1px solid #dbe3ef; border-radius: 9px; padding: .58rem 2.2rem .58rem 2.25rem; font-size: .84rem; background: #fbfcfe; }
    .package-search input:focus { outline: 0; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); background: #fff; }
    .package-search i { position: absolute; left: .75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 16px; height: 16px; }
    .package-search-clear { position: absolute; right: .55rem; top: 50%; transform: translateY(-50%); border: 0; background: transparent; color: #94a3b8; padding: .2rem; display: none; }
    .package-search-clear.visible { display: inline-flex; }
    .packages-result-count { color: #94a3b8; font-size: .75rem; white-space: nowrap; }
    .isp-filter { display: flex; align-items: center; gap: .3rem; flex-wrap: wrap; padding-top: .65rem; border-top: 1px solid #eef2f7; }
    .isp-filter-label { color: #94a3b8; font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; margin-right: .2rem; }
    .isp-chip { border: 1px solid #dbe3ef; color: #64748b; background: #fff; border-radius: 999px; padding: .45rem .72rem; font-size: .76rem; font-weight: 700; cursor: pointer; transition: all .18s; }
    .isp-chip:hover, .isp-chip.active { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
    .package-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }
    .package-card { position: relative; background: #fff; border: 1px solid #e5eaf2; border-radius: 15px; overflow: hidden; display: flex; flex-direction: column; min-height: 238px; transition: transform .2s, box-shadow .2s, border-color .2s; }
    .package-card:hover { transform: translateY(-3px); border-color: #bfdbfe; box-shadow: 0 12px 25px rgba(30,64,175,.1); }
    .package-card > *:not(.package-watermark) { position: relative; z-index: 1; }
    .package-watermark { position: absolute; z-index: 0; right: -14px; top: 54px; width: 126px; height: 126px; object-fit: contain; opacity: .07; filter: grayscale(1); pointer-events: none; }
    .package-card-top { padding: 1rem 1rem .8rem; display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem; }
    .package-mark { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: #2563eb; background: #eff6ff; }
    .package-isp { color: #1d4ed8; background: #eff6ff; border-radius: 999px; padding: .25rem .55rem; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
    .package-card h2 { font-size: 1.02rem; color: #172033; font-weight: 800; margin: 0 0 .22rem; }
    .package-price { font-size: 1.3rem; color: #0f766e; font-weight: 800; white-space: nowrap; }
    .package-price small { font-size: .68rem; color: #64748b; font-weight: 600; }
    .package-description { color: #64748b; font-size: .8rem; line-height: 1.5; padding: 0 1rem; flex: 1; }
    .package-description.is-empty { color: #a8b3c2; font-style: italic; }
    .package-card-footer { border-top: 1px solid #eef2f7; padding: .7rem 1rem; display: flex; justify-content: space-between; align-items: center; }
    .package-actions { display: inline-flex; gap: .35rem; }
    .package-action { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 0; transition: background .18s, color .18s; }
    .package-action.view { color: #2563eb; background: #eff6ff; }
    .package-action.edit { color: #b45309; background: #fffbeb; }
    .package-action.delete { color: #dc2626; background: #fef2f2; }
    .package-action:hover { filter: brightness(.95); }
    .package-empty { display: none; text-align: center; padding: 3rem 1rem; color: #64748b; background: #fff; border: 1px dashed #cbd5e1; border-radius: 15px; }
    .package-empty.visible { display: block; }
    @media (max-width: 575px) { .packages-hero { padding: 1.25rem; } .packages-hero .d-flex { align-items: flex-start !important; flex-direction: column; } .packages-add { margin-top: 1rem; } .packages-toolbar { align-items: stretch; } .isp-filter { justify-content: center; } }
</style>
@endsection

@section('content')
<div class="packages-page">
    <div class="container-fluid">
        @php
            $packageCount = $packages->count();
            $ispCount = $packages->pluck('isp_code')->filter()->unique()->count();
            $averagePrice = $packageCount ? $packages->avg('price') : 0;
            $lowestPrice = $packageCount ? $packages->min('price') : 0;
        @endphp
        <div class="packages-hero mb-4">
            <div class="packages-hero-content d-flex align-items-center justify-content-between gap-3">
                <div><div class="packages-eyebrow">Service catalog</div><h1>Packages</h1><p>Manage internet plans, pricing, and ISP availability from one clear workspace.</p></div>
                <a href="{{ route('packages.create') }}" class="packages-add"><i data-lucide="plus" style="width:16px;height:16px;"></i> Add package</a>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3"><div class="package-stat"><span class="package-stat-icon" style="background:#eff6ff;color:#2563eb;"><i data-lucide="layers" style="width:19px;height:19px;"></i></span><div><span class="package-stat-label">Total plans</span><span class="package-stat-value">{{ number_format($packageCount) }}</span></div></div></div>
            <div class="col-6 col-lg-3"><div class="package-stat"><span class="package-stat-icon" style="background:#ecfdf5;color:#059669;"><i data-lucide="radio-tower" style="width:19px;height:19px;"></i></span><div><span class="package-stat-label">ISPs covered</span><span class="package-stat-value">{{ number_format($ispCount) }}</span></div></div></div>
            <div class="col-6 col-lg-3"><div class="package-stat"><span class="package-stat-icon" style="background:#fff7ed;color:#ea580c;"><i data-lucide="arrow-down-to-line" style="width:19px;height:19px;"></i></span><div><span class="package-stat-label">Starting at</span><span class="package-stat-value">৳ {{ number_format($lowestPrice, 0) }}</span></div></div></div>
            <div class="col-6 col-lg-3"><div class="package-stat"><span class="package-stat-icon" style="background:#f5f3ff;color:#7c3aed;"><i data-lucide="calculator" style="width:19px;height:19px;"></i></span><div><span class="package-stat-label">Average price</span><span class="package-stat-value">৳ {{ number_format($averagePrice, 0) }}</span></div></div></div>
        </div>
        <div class="packages-toolbar mb-3">
            <div class="packages-toolbar-main">
                <span class="packages-toolbar-label"><i data-lucide="list-filter"></i> Browse plans</span>
                <div class="package-search"><i data-lucide="search"></i><input id="packageSearch" type="search" placeholder="Search packages or descriptions..." aria-label="Search packages"><button type="button" id="packageSearchClear" class="package-search-clear" aria-label="Clear package search"><i data-lucide="x" style="width:15px;height:15px;"></i></button></div>
                <span class="packages-result-count"><strong id="packageResultCount">{{ $packageCount }}</strong> plans</span>
            </div>
            <div class="isp-filter" role="group" aria-label="Filter packages by ISP">
                <span class="isp-filter-label">ISP</span>
                <button type="button" class="isp-chip active" data-isp-filter="all">All <span>({{ $packageCount }})</span></button>
                @foreach($packages->pluck('isp_code')->filter()->unique()->sort() as $isp)
                    <button type="button" class="isp-chip" data-isp-filter="{{ strtolower($isp) }}">{{ ucfirst($isp) }} <span>({{ $packages->where('isp_code', $isp)->count() }})</span></button>
                @endforeach
            </div>
        </div>
        <div class="package-grid" id="packageGrid">
            @foreach($packages as $package)
                @php
                    $ispProfile = $ispProfiles->get(strtolower($package->isp_code ?? ''));
                @endphp
                <article class="package-card" data-package-card data-isp="{{ strtolower($package->isp_code ?? '') }}" data-search="{{ strtolower($package->title . ' ' . ($package->descriptoin ?? '') . ' ' . ($package->isp_code ?? '')) }}">
                    <img class="package-watermark" src="{{ optional($ispProfile)->logo_url ?: asset('img/logo.png') }}" alt="">
                    <div class="package-card-top"><span class="package-mark"><i data-lucide="wifi" style="width:19px;height:19px;"></i></span><span class="package-isp">{{ ucfirst($package->isp_code ?? 'N/A') }}</span></div>
                    <div class="px-3"><h2>{{ $package->title }}</h2><div class="package-price">৳ {{ number_format($package->price, 0) }} <small>/ month</small></div></div>
                    <p class="package-description {{ $package->descriptoin ? '' : 'is-empty' }}">{{ $package->descriptoin ?: 'No description added yet.' }}</p>
                    <div class="package-card-footer"><span class="text-muted small">Plan #{{ $package->id }}</span><div class="package-actions">
                        <a href="{{ route('packages.show', [$package->id]) }}" class="package-action view" title="View package" aria-label="View package"><i data-lucide="eye" style="width:15px;height:15px;"></i></a>
                        <a href="{{ route('packages.edit', [$package->id]) }}" class="package-action edit" title="Edit package" aria-label="Edit package"><i data-lucide="pencil" style="width:15px;height:15px;"></i></a>
                        {!! Form::open(['route' => ['packages.destroy', $package->id], 'method' => 'delete', 'class' => 'd-inline']) !!}{!! Form::button('<i data-lucide="trash-2" style="width:15px;height:15px;"></i>', ['type' => 'submit', 'class' => 'package-action delete', 'title' => 'Delete package', 'aria-label' => 'Delete package', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'\")']) !!}{!! Form::close() !!}
                    </div></div>
                </article>
            @endforeach
        </div>
        <div class="package-empty" id="packageEmpty"><i data-lucide="search-x" style="width:34px;height:34px;"></i><h3 class="h6 mt-3 mb-1">No packages found</h3><p class="small mb-0">Try a different search or ISP filter.</p></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    const search = document.getElementById('packageSearch');
    const cards = Array.from(document.querySelectorAll('[data-package-card]'));
    const empty = document.getElementById('packageEmpty');
    const clear = document.getElementById('packageSearchClear');
    const resultCount = document.getElementById('packageResultCount');
    let activeIsp = 'all';
    function filterPackages() {
        const term = (search.value || '').toLowerCase().trim();
        let visible = 0;
        cards.forEach(function (card) {
            const matchesIsp = activeIsp === 'all' || card.dataset.isp === activeIsp;
            const matchesSearch = !term || card.dataset.search.includes(term);
            card.hidden = !(matchesIsp && matchesSearch);
            if (!card.hidden) visible++;
        });
        empty.classList.toggle('visible', visible === 0);
        clear.classList.toggle('visible', term.length > 0);
        resultCount.textContent = visible;
    }
    search.addEventListener('input', filterPackages);
    clear.addEventListener('click', function () { search.value = ''; filterPackages(); search.focus(); });
    document.querySelectorAll('[data-isp-filter]').forEach(function (button) {
        button.addEventListener('click', function () {
            activeIsp = button.dataset.ispFilter;
            document.querySelectorAll('[data-isp-filter]').forEach(function (item) { item.classList.toggle('active', item === button); });
            filterPackages();
        });
    });
});
</script>
@endsection