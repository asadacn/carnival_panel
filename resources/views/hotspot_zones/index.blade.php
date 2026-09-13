@extends('layouts.app')
@section('title') Hotspot Zones @endsection

@section('page_css')
<style>
    :root {
        --hz-primary:    #6777ef; /* Stisla primary */
        --hz-primary-d:  #5a67d8;
        --hz-accent:     #3abaf4; /* Stisla info */
        --hz-success:    #47c363; /* Stisla success */
        --hz-danger:     #fc544b; /* Stisla danger */
        --hz-warning:    #ffa426; /* Stisla warning */
        --hz-bg:         #f4f6f9;
        --hz-surface:    #ffffff; /* White card surface */
        --hz-surface2:   #fcfdff; /* Secondary light container */
        --hz-border:     #e9ecef; /* Soft light border */
        --hz-text:       #2d3748; /* Dark text */
        --hz-muted:      #7a828a; /* Muted gray */
        --hz-radius:     10px;
        --hz-shadow:     0 4px 12px rgba(0, 0, 0, 0.04);
    }

    /* ── page wrapper ── */
    .hz-page { padding: 24px; animation: hzFadeUp .45s ease both; }
    @keyframes hzFadeUp {
        from { opacity:0; transform:translateY(18px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── hero header ── */
    .hz-hero {
        background: linear-gradient(135deg, var(--hz-primary) 0%, var(--hz-accent) 100%);
        border-radius: var(--hz-radius);
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
        box-shadow: 0 6px 22px rgba(103, 119, 239, 0.25);
    }
    .hz-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(255,255,255,.2) 0%, transparent 70%);
        pointer-events: none;
    }
    .hz-hero-icon {
        width: 52px; height: 52px; border-radius: 12px;
        background: rgba(255, 255, 255, 0.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff;
        backdrop-filter: blur(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
        flex-shrink: 0;
    }
    .hz-hero-text h1 { font-size: 1.55rem; font-weight: 700; color:#fff; margin:0; letter-spacing:-.3px; }
    .hz-hero-text p  { color: rgba(255,255,255,.85); margin:4px 0 0; font-size:.9rem; }
    .hz-hero-actions { display:flex; gap:10px; flex-wrap:wrap; }

    /* ── buttons ── */
    .hz-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 8px; font-size: .85rem;
        font-weight: 600; border: none; cursor: pointer;
        transition: transform .18s, box-shadow .18s, background .18s;
        text-decoration: none; white-space: nowrap;
    }
    .hz-btn:hover { transform: translateY(-2px); text-decoration: none; }
    .hz-btn-primary {
        background: #ffffff;
        color: var(--hz-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .hz-btn-primary:hover { box-shadow: 0 6px 16px rgba(0,0,0,0.12); color: var(--hz-primary-d); }
    .hz-btn-outline {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        color: #ffffff;
    }
    .hz-btn-outline:hover { background:rgba(255,255,255,.25); color:#ffffff; }
    .hz-btn-danger {
        background: #fc544b;
        color: #fff;
        box-shadow: 0 4px 12px rgba(252,84,75,.3);
    }
    .hz-btn-danger:hover { box-shadow:0 6px 16px rgba(252,84,75,.45); color:#fff; }
    .hz-btn-sm { padding:6px 12px; font-size:.78rem; }

    /* ── stat cards ── */
    .hz-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:24px; }
    .hz-stat {
        background: var(--hz-surface);
        border: 1px solid var(--hz-border);
        border-radius: var(--hz-radius);
        padding: 20px 22px;
        display: flex; align-items: center; gap: 14px;
        transition: transform .2s, box-shadow .2s;
        box-shadow: var(--hz-shadow);
    }
    .hz-stat:hover { transform:translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.06); }
    .hz-stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink:0;
    }
    .hz-stat-icon.indigo { background:rgba(103,119,239,.1); color:var(--hz-primary); }
    .hz-stat-icon.cyan   { background:rgba(58,186,244,.1);  color:var(--hz-accent); }
    .hz-stat-icon.green  { background:rgba(71,195,99,.1); color:var(--hz-success); }
    .hz-stat-icon.red    { background:rgba(252,84,75,.1);  color:var(--hz-danger); }
    .hz-stat-val  { font-size:1.6rem; font-weight:700; color:var(--hz-text); line-height:1; }
    .hz-stat-lbl  { font-size:.78rem; color:var(--hz-muted); margin-top:3px; }

    /* ── main card ── */
    .hz-card {
        background: var(--hz-surface);
        border: 1px solid var(--hz-border);
        border-radius: var(--hz-radius);
        box-shadow: var(--hz-shadow);
        overflow: hidden;
    }
    .hz-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--hz-border);
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        background: rgba(103,119,239,.03);
    }
    .hz-card-title { font-size:1rem; font-weight:700; color:var(--hz-text); margin:0; }
    .hz-card-body { padding: 24px; }

    /* ── search box ── */
    .hz-search-wrap { position:relative; }
    .hz-search-wrap i {
        position: absolute; left:12px; top:50%; transform:translateY(-50%);
        color:var(--hz-muted); font-size:.9rem; pointer-events:none;
    }
    .hz-search-input {
        background: #fdfdff;
        border: 1px solid var(--hz-border);
        border-radius: 8px;
        color: var(--hz-text);
        padding: 9px 14px 9px 36px;
        font-size: .86rem; width: 240px;
        transition: border-color .2s, box-shadow .2s;
    }
    .hz-search-input:focus {
        outline: none;
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(103,119,239,.15);
    }
    .hz-search-input::placeholder { color:var(--hz-muted); }

    /* ── table ── */
    .hz-table-wrap { overflow-x:auto; }
    #hotspotZonesTable { width:100%; border-collapse:collapse; }
    #hotspotZonesTable thead tr {
        background: #f8fafc;
        border-bottom: 1px solid var(--hz-border);
    }
    #hotspotZonesTable th {
        padding:13px 16px; font-size:.78rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.06em;
        color:var(--hz-muted); white-space:nowrap;
        cursor: pointer; user-select:none;
    }
    #hotspotZonesTable th:hover { color:var(--hz-primary); }
    #hotspotZonesTable th .sort-icon { opacity:.4; margin-left:4px; transition:opacity .2s; }
    #hotspotZonesTable th:hover .sort-icon { opacity:1; }
    #hotspotZonesTable tbody tr {
        border-bottom: 1px solid var(--hz-border);
        transition: background .18s;
    }
    #hotspotZonesTable tbody tr:hover { background: rgba(103,119,239,.03); }
    #hotspotZonesTable td {
        padding:13px 16px; font-size:.86rem;
        color:var(--hz-text); vertical-align:middle;
    }
    .hz-zone-id {
        display:inline-flex; align-items:center;
        background: rgba(103,119,239,.08);
        color: var(--hz-primary);
        padding: 3px 10px; border-radius: 6px;
        font-weight: 700; font-size:.82rem; font-family:monospace;
    }
    .hz-badge {
        display: inline-flex; align-items: center; gap:5px;
        padding: 4px 11px; border-radius: 20px;
        font-size: .75rem; font-weight:600; white-space:nowrap;
    }
    .hz-badge-success { background:rgba(71,195,99,.12); color:#28a745; }
    .hz-badge-danger  { background:rgba(252,84,75,.12);  color:#dc3545; }
    .hz-badge-dot {
        width:7px; height:7px; border-radius:50%;
        animation: pulse-dot 2s ease infinite;
    }
    .hz-badge-success .hz-badge-dot { background:#28a745; }
    .hz-badge-danger  .hz-badge-dot { background:#dc3545; animation:none; }
    @keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.35;} }

    .hz-device-chip {
        display:inline-flex; align-items:center; gap:5px;
        background: rgba(58,186,244,.08); color:#17a2b8;
        padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600;
    }
    .hz-mac-container {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .hz-mac { font-family:monospace; font-size:.82rem; color:var(--hz-text); font-weight: 600; }
    .hz-table-copy-btn {
        background: transparent;
        border: none;
        color: var(--hz-primary);
        cursor: pointer;
        padding: 2px 6px;
        font-size: 0.78rem;
        border-radius: 4px;
        transition: background 0.18s, color 0.18s, opacity 0.18s;
        opacity: 0.35;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .hz-mac-container:hover .hz-table-copy-btn {
        opacity: 1;
    }
    .hz-table-copy-btn:hover {
        background: rgba(103, 119, 239, 0.1);
        color: var(--hz-primary-d);
    }

    /* action buttons */
    .hz-actions { display:flex; gap:6px; align-items:center; }
    .hz-action-btn {
        width:32px; height:32px; border-radius:6px;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:.78rem; border:none; cursor:pointer;
        transition: transform .18s, box-shadow .18s;
        text-decoration:none;
    }
    .hz-action-btn:hover { transform:scale(1.12); text-decoration: none; }
    .hz-action-btn.view { background:rgba(58,186,244,.12); color:#17a2b8; }
    .hz-action-btn.edit { background:rgba(255,164,38,.12); color:#ffc107; }
    .hz-action-btn.del  { background:rgba(252,84,75,.12);  color:#dc3545; }
    .hz-action-btn.view:hover { background:rgba(58,186,244,.25);  box-shadow:0 2px 8px rgba(58,186,244,.25); color:#17a2b8; }
    .hz-action-btn.edit:hover { background:rgba(255,164,38,.25); box-shadow:0 2px 8px rgba(255,164,38,.25); color:#d39e00; }
    .hz-action-btn.del:hover  { background:rgba(252,84,75,.25);  box-shadow:0 2px 8px rgba(252,84,75,.25); color:#dc3545; }

    /* ── pagination / length ── */
    .hz-footer {
        padding:16px 24px;
        border-top:1px solid var(--hz-border);
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:12px;
    }
    .hz-info { font-size:.82rem; color:var(--hz-muted); }
    .hz-pagination { display:flex; gap:4px; }
    .hz-pg-btn {
        padding:6px 11px; border-radius:6px; border:1px solid var(--hz-border);
        background:#ffffff; color:var(--hz-muted);
        font-size:.82rem; cursor:pointer; transition:all .18s;
    }
    .hz-pg-btn:hover, .hz-pg-btn.active {
        background:var(--hz-primary); color:#fff;
        border-color:var(--hz-primary);
    }
    .hz-pg-btn:disabled { opacity:.35; cursor:not-allowed; }

    /* ── skeleton loader ── */
    .hz-skeleton {
        background: linear-gradient(90deg, #e9ecef 25%, #f1f3f5 50%, #e9ecef 75%);
        background-size:400% 100%;
        animation:hz-shimmer 1.4s ease infinite;
        border-radius:4px; display:inline-block;
    }
    @keyframes hz-shimmer { 0%{background-position:100% 0;} 100%{background-position:-100% 0;} }

    /* ── empty state ── */
    .hz-empty {
        text-align:center; padding:60px 24px;
    }
    .hz-empty-icon {
        font-size:3.5rem; color:var(--hz-muted);
        margin-bottom:16px; opacity:.4;
    }
    .hz-empty h3 { color:var(--hz-text); margin-bottom:6px; }
    .hz-empty p  { color:var(--hz-muted); font-size:.9rem; }

    /* ── delete confirm modal ── */
    .hz-overlay {
        position:fixed; inset:0; background:rgba(0,0,0,.45);
        display:none; align-items:center; justify-content:center;
        z-index:9999; backdrop-filter:blur(3px);
        animation:hzFadeIn .25s ease;
    }
    .hz-overlay.open { display:flex; }
    @keyframes hzFadeIn { from{opacity:0;} to{opacity:1;} }
    .hz-modal {
        background:#ffffff;
        border:1px solid var(--hz-border);
        border-radius:12px;
        padding:32px;
        max-width:400px; width:90%;
        text-align:center;
        animation:hzScaleIn .25s ease;
        box-shadow:0 10px 30px rgba(0,0,0,.1);
    }
    @keyframes hzScaleIn { from{transform:scale(.92);} to{transform:scale(1);} }
    .hz-modal-icon { font-size:2.5rem; margin-bottom:12px; }
    .hz-modal h3 { color:var(--hz-text); margin-bottom:8px; }
    .hz-modal p  { color:var(--hz-muted); font-size:.9rem; margin-bottom:24px; }
    .hz-modal-actions { display:flex; gap:10px; justify-content:center; }

    /* ── responsive ── */
    @media(max-width:640px) {
        .hz-hero { padding:20px; }
        .hz-hero-text h1 { font-size:1.2rem; }
        .hz-card-body { padding:16px; }
        .hz-search-input { width:100%; }
        .hz-stats { grid-template-columns:1fr 1fr; }
    }
</style>
@endsection

@section('content')
<div class="hz-page">

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <h1 class="page-title-heading">
            <span class="page-title-icon"><i class="fas fa-wifi"></i></span>
            Hotspot Zones
        </h1>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('hotspotZones.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>Add Zone
            </a>
            <a href="{{ route('hotspots.export') }}" class="btn btn-outline-primary">
                <i class="fas fa-file-export me-2"></i>Export
            </a>
            <a href="{{ route('hotspots.import.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-file-import me-2"></i>Import
            </a>
            <form action="{{ route('hotspots.erase') }}" method="POST" class="d-inline" onsubmit="return confirm('WARNING: This will erase ALL hotspot zone records. Continue?')">
                @csrf
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="fas fa-trash-alt me-2"></i>Erase All
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4" id="hz-stats-row">
        <div class="col-lg-3 col-md-6">
            <div class="db-kpi-card" style="--kpi-accent:#4f46e5; --kpi-soft:rgba(79,70,229,.10);">
                <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(79,70,229,.10)); color: var(--kpi-accent, #4f46e5);">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="db-kpi-body">
                    <span class="db-kpi-label">Total Zones</span>
                    <span class="db-kpi-value" id="stat-total"><span class="hz-skeleton" style="width:36px;height:24px;">&nbsp;</span></span>
                </div>
                <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="db-kpi-card" style="--kpi-accent:#10b981; --kpi-soft:rgba(16,185,129,.10);">
                <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(16,185,129,.10)); color: var(--kpi-accent, #10b981);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="db-kpi-body">
                    <span class="db-kpi-label">Enabled</span>
                    <span class="db-kpi-value" id="stat-enabled"><span class="hz-skeleton" style="width:30px;height:24px;">&nbsp;</span></span>
                </div>
                <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="db-kpi-card" style="--kpi-accent:#ef4444; --kpi-soft:rgba(239,68,68,.10);">
                <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(239,68,68,.10)); color: var(--kpi-accent, #ef4444);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="db-kpi-body">
                    <span class="db-kpi-label">Disabled</span>
                    <span class="db-kpi-value" id="stat-disabled"><span class="hz-skeleton" style="width:30px;height:24px;">&nbsp;</span></span>
                </div>
                <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="db-kpi-card" style="--kpi-accent:#06b6d4; --kpi-soft:rgba(6,182,212,.10);">
                <div class="db-kpi-icon" style="background: var(--kpi-soft, rgba(6,182,212,.10)); color: var(--kpi-accent, #06b6d4);">
                    <i class="fas fa-plug"></i>
                </div>
                <div class="db-kpi-body">
                    <span class="db-kpi-label">With UPS</span>
                    <span class="db-kpi-value" id="stat-ups"><span class="hz-skeleton" style="width:30px;height:24px;">&nbsp;</span></span>
                </div>
                <span class="db-kpi-arrow"><i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    </div>

    {{-- ── Main Table Card ── --}}
    <div class="hz-card">
        <div class="hz-card-header">
            <span class="hz-card-title"><i class="fas fa-table" style="color:var(--hz-primary);margin-right:8px;"></i>Zone Directory</span>
            <div class="hz-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="hz-search" class="hz-search-input" placeholder="Search zones…">
            </div>
        </div>

        <div class="hz-card-body" style="padding:0;">
            <div class="hz-table-wrap">
                <table id="hotspotZonesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Zone ID <i class="fas fa-sort sort-icon"></i></th>
                            <th>Zone Title <i class="fas fa-sort sort-icon"></i></th>
                            <th>Device Brand</th>
                            <th>ONU Brand</th>
                            <th>ONU MAC</th>
                            <th>Status <i class="fas fa-sort sort-icon"></i></th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="hz-tbody">
                        {{-- populated via DataTables AJAX --}}
                    </tbody>
                </table>

                <div id="hz-empty" class="hz-empty" style="display:none;">
                    <div class="hz-empty-icon"><i class="fas fa-wifi-slash"></i></div>
                    <h3>No Zones Found</h3>
                    <p>Try a different search term or add your first hotspot zone.</p>
                    <a href="{{ route('hotspotZones.create') }}" class="hz-btn hz-btn-primary" style="margin-top:14px;">
                        <i class="fas fa-plus"></i> Add First Zone
                    </a>
                </div>
            </div>
        </div>

        <div class="hz-footer">
            <div class="hz-info" id="hz-dt-info">Loading…</div>
            <div id="hz-dt-pagination" class="hz-pagination"></div>
        </div>
    </div>

</div>

{{-- ── Delete Confirm Modal ── --}}
<div class="hz-overlay" id="hz-del-overlay">
    <div class="hz-modal">
        <div class="hz-modal-icon" style="color: var(--hz-danger)"><i class="fas fa-exclamation-triangle"></i></div>
        <h3 style="margin-top: 12px;">Delete Zone?</h3>
        <p>This action cannot be undone. The zone record will be permanently removed.</p>
        <div class="hz-modal-actions">
            <button class="hz-btn" style="background: #f4f6f9; color: var(--hz-text);" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="hz-btn hz-btn-danger" onclick="submitDeleteForm()">
                <i class="fas fa-trash"></i> Yes, Delete
            </button>
        </div>
    </div>
</div>

<form id="hz-delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {

    var dt = $('#hotspotZonesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        dom: 'rt',               // only table + pagination via custom UI
        pageLength: 20,
        ajax: "{{ route('hotspotZones.index') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'zone_id',     name: 'zone_id' },
            { data: 'zone_title',  name: 'zone_title' },
            { data: 'device_brand',name: 'device_brand' },
            { data: 'onu_brand',   name: 'onu_brand' },
            { data: 'onu_mac',     name: 'onu_mac' },
            { data: 'status',      name: 'status' },
            { data: 'action',      name: 'action', orderable: false, searchable: false },
        ],
        order: [[1, 'asc']],
        createdRow: function (row) {
            $(row).addClass('hz-dt-row');
        },
        // Custom cell rendering
        columnDefs: [
            {
                targets: 1, // zone_id
                render: function (data) {
                    if (!data) return '<span class="hz-muted">—</span>';
                    return '<span class="hz-zone-id">' + data + '</span>';
                }
            },
            {
                targets: 2, // zone_title
                render: function (data) {
                    if (!data) return '<span style="color:var(--hz-muted)">—</span>';
                    return '<span style="font-weight:600;color:var(--hz-text);">' + data + '</span>';
                }
            },
            {
                targets: 3, // device_brand
                render: function (data) {
                    if (!data) return '<span style="color:var(--hz-muted)">—</span>';
                    return '<span class="hz-device-chip"><i class="fas fa-microchip"></i>' + data + '</span>';
                }
            },
            {
                targets: 4, // onu_brand
                render: function (data) {
                    if (!data) return '<span style="color:var(--hz-muted)">—</span>';
                    return '<span class="hz-device-chip"><i class="fas fa-broadcast-tower"></i>' + data + '</span>';
                }
            },
            {
                targets: 5, // onu_mac (MAC Address is Copiable)
                render: function (data) {
                    if (!data) return '<span style="color:var(--hz-muted)">—</span>';
                    return '<span class="hz-mac-container">' +
                           '<span class="hz-mac">' + data + '</span>' +
                           '<button class="hz-table-copy-btn" onclick="hzCopy(\'' + data + '\')" title="Copy MAC Address">' +
                           '<i class="far fa-copy"></i>' +
                           '</button>' +
                           '</span>';
                }
            },
            {
                targets: 6, // status
                render: function (data) {
                    if (!data) return '';
                    var isEnabled = data === 'Enable';
                    return isEnabled
                        ? '<span class="hz-badge hz-badge-success"><span class="hz-badge-dot"></span>Enabled</span>'
                        : '<span class="hz-badge hz-badge-danger"><span class="hz-badge-dot"></span>Disabled</span>';
                }
            },
            {
                targets: 7, // action — keep raw HTML from server
                render: function (data) { return data; }
            },
        ],
        initComplete: function () {
            updateStats();
        },
        drawCallback: function (settings) {
            var info = this.api().page.info();
            var total = info.recordsDisplay;

            // Info text
            var from = info.start + 1;
            var to   = info.end;
            $('#hz-dt-info').text('Showing ' + from + '–' + to + ' of ' + total + ' zones');

            // Empty state
            if (total === 0) {
                $('#hz-empty').show();
                $('.hz-table-wrap table').hide();
            } else {
                $('#hz-empty').hide();
                $('.hz-table-wrap table').show();
            }

            buildPagination(settings);

            // Style action buttons rendered by server
            styleActionButtons();
        }
    });

    // ── Custom search ──
    var searchTimer;
    $('#hz-search').on('input', function () {
        clearTimeout(searchTimer);
        var q = $(this).val();
        searchTimer = setTimeout(function () { dt.search(q).draw(); }, 350);
    });

    // ── Pagination builder ──
    function buildPagination(settings) {
        var api  = dt.page.info();
        var $pg  = $('#hz-dt-pagination').empty();
        var cur  = api.page;
        var total = api.pages;

        if (total <= 1) return;

        // Prev
        var prevBtn = $('<button class="hz-pg-btn">').html('<i class="fas fa-chevron-left"></i>');
        if (cur === 0) prevBtn.prop('disabled', true);
        prevBtn.on('click', function () { dt.page('previous').draw('page'); });
        $pg.append(prevBtn);

        // Pages
        var start = Math.max(0, cur - 2);
        var end   = Math.min(total - 1, cur + 2);
        for (var i = start; i <= end; i++) {
            (function (page) {
                var btn = $('<button class="hz-pg-btn' + (page === cur ? ' active' : '') + '">').text(page + 1);
                btn.on('click', function () { dt.page(page).draw('page'); });
                $pg.append(btn);
            })(i);
        }

        // Next
        var nextBtn = $('<button class="hz-pg-btn">').html('<i class="fas fa-chevron-right"></i>');
        if (cur === total - 1) nextBtn.prop('disabled', true);
        nextBtn.on('click', function () { dt.page('next').draw('page'); });
        $pg.append(nextBtn);
    }

    // ── Stats update ──
    function updateStats() {
        $.get("{{ route('hotspotZones.index') }}", {draw:1, start:0, length:9999}, function (res) {
            if (!res.data) return;
            var d = res.data;
            var total    = d.length;
            var enabled  = d.filter(function(z){ return z.status === 'Enable'; }).length;
            var disabled = total - enabled;
            var ups      = d.filter(function(z){ return z.has_ups === 'Enable'; }).length;

            animateCount('#stat-total',   total);
            animateCount('#stat-enabled', enabled);
            animateCount('#stat-disabled',disabled);
            animateCount('#stat-ups',     ups);
        });
    }

    function animateCount(selector, target) {
        var $el = $(selector);
        $el.html('');
        var start = 0;
        var step  = Math.max(1, Math.ceil(target / 25));
        var iv = setInterval(function () {
            start = Math.min(start + step, target);
            $el.text(start);
            if (start >= target) clearInterval(iv);
        }, 30);
    }

    // ── Style server-rendered action buttons ──
    function styleActionButtons() {
        // Convert raw server buttons to our design
        $('#hotspotZonesTable .btn-group').each(function () {
            var $g = $(this);
            var $wrap = $('<div class="hz-actions"></div>');
            $g.find('a, button').each(function () {
                var $orig = $(this);
                var href  = $orig.attr('href') || '#';
                var cls   = $orig.hasClass('btn-light')   ? 'view'
                           : $orig.hasClass('btn-warning') ? 'edit'
                           : 'del';
                var icon  = cls === 'view' ? 'fa-eye' : cls === 'edit' ? 'fa-pen' : 'fa-trash';
                var title = cls === 'view' ? 'View'  : cls === 'edit' ? 'Edit'    : 'Delete';

                if (cls === 'del') {
                    var $btn = $('<button class="hz-action-btn del" title="Delete"><i class="fas ' + icon + '"></i></button>');
                    $btn.on('click', function () { openDeleteModal(href); });
                    $wrap.append($btn);
                } else {
                    $wrap.append('<a href="' + href + '" class="hz-action-btn ' + cls + '" title="' + title + '"><i class="fas ' + icon + '"></i></a>');
                }
            });
            $g.replaceWith($wrap);
        });
    }

    // ── Delete Modal ──
    window.openDeleteModal = function (url) {
        $('#hz-delete-form').attr('action', url);
        $('#hz-del-overlay').addClass('open');
    };
    window.closeDeleteModal = function () {
        $('#hz-del-overlay').removeClass('open');
    };
    window.submitDeleteForm = function () {
        $('#hz-delete-form').submit();
    };
    $('#hz-del-overlay').on('click', function (e) {
        if ($(e.target).is('#hz-del-overlay')) closeDeleteModal();
    });

    // ── Copy to Clipboard ──
    window.hzCopy = function(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(showSuccessToast).catch(fallbackCopy);
        } else {
            fallbackCopy();
        }

        function showSuccessToast() {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: text,
                    showConfirmButton: false,
                    timer: 1400,
                    position: 'top-end',
                    toast: true,
                });
            } else {
                // simple fallback toast if Swal is missing
                var $toast = $('<div style="position:fixed;top:20px;right:20px;background:#28a745;color:#fff;padding:12px 20px;border-radius:5px;z-index:99999;box-shadow:0 3px 10px rgba(0,0,0,0.2)">Copied: ' + text + '</div>');
                $('body').append($toast);
                setTimeout(function(){ $toast.fadeOut(function(){ $(this).remove(); }); }, 1500);
            }
        }

        function fallbackCopy() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
            showSuccessToast();
        }
    };

});
</script>
@endsection
