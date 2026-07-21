@extends('layouts.app')
@section('title') Edit Hotspot Zone @endsection

@section('page_css')
<style>
    :root {
        --hz-primary:   #6777ef; /* Stisla primary */
        --hz-primary-d: #5a67d8;
        --hz-accent:    #3abaf4; /* Stisla info */
        --hz-success:   #47c363; /* Stisla success */
        --hz-danger:    #fc544b; /* Stisla danger */
        --hz-warning:   #ffa426; /* Stisla warning */
        --hz-surface:   #ffffff; /* White surface */
        --hz-surface2:  #fdfdff; /* Secondary input/bg surface */
        --hz-border:    #e9ecef; /* Soft light border */
        --hz-text:      #2d3748; /* Dark body text */
        --hz-muted:     #7a828a; /* Gray muted text */
        --hz-radius:    10px;
    }

    .hz-form-page {
        max-width: 960px;
        margin: 0 auto;
        padding: 24px;
        animation: hzFadeUp .4s ease both;
    }
    @keyframes hzFadeUp {
        from { opacity:0; transform:translateY(18px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .hz-form-header {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:28px; flex-wrap:wrap; gap:12px;
    }
    .hz-form-heading {
        display:flex; align-items:center; gap:14px;
    }
    .hz-form-icon {
        width:48px; height:48px; border-radius:10px;
        background:linear-gradient(135deg,var(--hz-warning),#d97706);
        display:flex; align-items:center; justify-content:center;
        font-size:20px; color:#fff;
        box-shadow:0 4px 14px rgba(245,158,11,.35);
    }
    .hz-form-heading h1 { font-size:1.4rem; font-weight:700; color:var(--hz-text); margin:0; }
    .hz-form-heading p  { color:var(--hz-muted); margin:2px 0 0; font-size:.85rem; }

    .hz-badge-id {
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(103,119,239,.08); color:var(--hz-primary);
        padding:4px 12px; border-radius:20px; font-size:.82rem; font-weight:700; font-family:monospace;
        border:1px solid rgba(103,119,239,.15);
    }

    .hz-back-btn {
        display:inline-flex; align-items:center; gap:7px;
        padding:9px 18px; border-radius:8px;
        background:rgba(103,119,239,.08); border:1px solid rgba(103,119,239,.15);
        color:var(--hz-primary); font-size:.85rem; font-weight:600;
        text-decoration:none; transition:all .18s;
    }
    .hz-back-btn:hover { background:rgba(103,119,239,.16); color:var(--hz-primary-d); text-decoration: none; }

    .hz-form-card {
        background:var(--hz-surface);
        border:1px solid var(--hz-border);
        border-radius:var(--hz-radius);
        box-shadow:0 4px 16px rgba(0,0,0,.03);
        overflow:hidden;
    }

    .hz-section-strip {
        display:flex; align-items:center; gap:10px;
        padding:16px 28px;
        border-bottom:1px solid var(--hz-border);
        background:rgba(103,119,239,.03);
    }
    .hz-section-dot {
        width:10px; height:10px; border-radius:50%;
        background:linear-gradient(135deg,var(--hz-primary),var(--hz-accent));
    }
    .hz-section-strip span { font-size:.82rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--hz-muted); }

    .hz-form-body { padding:28px; }

    .hz-field-grid { display:grid; gap:20px; }
    .hz-field-grid.cols-2 { grid-template-columns:repeat(2,1fr); }
    .hz-field-grid.cols-3 { grid-template-columns:repeat(3,1fr); }
    .hz-field-grid.cols-4 { grid-template-columns:repeat(4,1fr); }
    @media(max-width:700px) {
        .hz-field-grid.cols-2,
        .hz-field-grid.cols-3,
        .hz-field-grid.cols-4 { grid-template-columns:1fr; }
    }

    .hz-field { position:relative; }
    .hz-label {
        display:flex; align-items:center; gap:6px;
        font-size:.78rem; font-weight:700; letter-spacing:.05em;
        text-transform:uppercase; color:var(--hz-muted);
        margin-bottom:7px;
    }
    .hz-label i { font-size:.75rem; color:var(--hz-primary); }
    .hz-label .req { color:var(--hz-danger); margin-left:2px; }

    .hz-input, .hz-select {
        width:100%;
        background:#ffffff;
        border:1px solid #dcdfe6;
        border-radius:8px;
        color:var(--hz-text);
        padding:11px 15px;
        font-size:.9rem;
        transition:border-color .2s, box-shadow .2s, background .2s;
        appearance:none;
    }
    .hz-input:focus, .hz-select:focus {
        outline:none;
        border-color:var(--hz-primary);
        box-shadow:0 0 0 3px rgba(103,119,239,.15);
        background:rgba(103,119,239,.02);
    }
    .hz-input::placeholder { color:var(--hz-muted); }
    .hz-select { background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%237a828a' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; background-size:12px; padding-right:34px; cursor:pointer; }
    .hz-select-wrap { position:relative; }

    .hz-submit-row { display:flex; gap:12px; align-items:center; margin-top:28px; flex-wrap:wrap; }
    .hz-btn-submit {
        display:inline-flex; align-items:center; gap:8px;
        padding:12px 28px; border-radius:8px;
        background:linear-gradient(135deg,var(--hz-warning),#d97706);
        color:#fff; font-size:.9rem; font-weight:700; border:none;
        cursor:pointer; transition:transform .18s, box-shadow .18s;
        box-shadow:0 4px 14px rgba(245,158,11,.35);
    }
    .hz-btn-submit:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(245,158,11,.5); }
    .hz-btn-cancel {
        display:inline-flex; align-items:center; gap:8px;
        padding:12px 22px; border-radius:8px;
        background:rgba(120,130,140,.08); border:1px solid rgba(120,130,140,.15);
        color:var(--hz-text); font-size:.9rem; font-weight:600;
        text-decoration:none; transition:all .18s;
    }
    .hz-btn-cancel:hover { background:rgba(120,130,140,.16); color:var(--hz-text); text-decoration: none; }
</style>
@endsection

@section('content')
<div class="hz-form-page">

    {{-- Header --}}
    <div class="hz-form-header">
        <div class="hz-form-heading">
            <div class="hz-form-icon"><i class="fas fa-pen"></i></div>
            <div>
                <h1 style="display:flex;align-items:center;gap:10px;">
                    Edit Zone
                    <span class="hz-badge-id">
                        <i class="fas fa-hashtag"></i>{{ $hotspotZone->zone_id }}
                    </span>
                </h1>
                <p>{{ $hotspotZone->zone_title }} — Update device and zone details below</p>
            </div>
        </div>
        <a href="{{ route('hotspotZones.index') }}" class="hz-back-btn">
            <i class="fas fa-arrow-left"></i> Back to Zones
        </a>
    </div>

    @include('stisla-templates::common.errors')

    <div class="hz-form-card">
        {!! Form::model($hotspotZone, ['route' => ['hotspotZones.update', $hotspotZone->id], 'method' => 'patch']) !!}

        {{-- Zone Identity --}}
        <div class="hz-section-strip">
            <div class="hz-section-dot"></div>
            <span><i class="fas fa-fingerprint" style="margin-right:6px;color:var(--hz-primary);"></i>Zone Identity</span>
        </div>
        <div class="hz-form-body" style="padding-bottom:0;">
            <div class="hz-field-grid cols-3">
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-hashtag"></i> Zone ID <span class="req">*</span></label>
                    {!! Form::text('zone_id', null, ['class'=>'hz-input','placeholder'=>'e.g. HZ-001']) !!}
                </div>
                <div class="hz-field" style="grid-column:span 2;">
                    <label class="hz-label"><i class="fas fa-map-marker-alt"></i> Zone Title <span class="req">*</span></label>
                    {!! Form::text('zone_title', null, ['class'=>'hz-input','placeholder'=>'Zone display name']) !!}
                </div>
            </div>
        </div>

        {{-- Device Info --}}
        <div class="hz-section-strip" style="margin-top:24px;">
            <div class="hz-section-dot" style="background:linear-gradient(135deg,var(--hz-accent),#0891b2);"></div>
            <span><i class="fas fa-microchip" style="margin-right:6px;color:var(--hz-accent);"></i>Device Information</span>
        </div>
        <div class="hz-form-body" style="padding-top:20px;padding-bottom:0;">
            <div class="hz-field-grid cols-2">
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-tag"></i> Device Brand</label>
                    {!! Form::text('device_brand', null, ['class'=>'hz-input','placeholder'=>'e.g. Mikrotik, TP-Link']) !!}
                </div>
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-barcode"></i> Device MAC Address</label>
                    {!! Form::text('device_mac', null, ['class'=>'hz-input','placeholder'=>'AA:BB:CC:DD:EE:FF']) !!}
                </div>
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-fingerprint"></i> Device Serial Number</label>
                    {!! Form::text('device_serial', null, ['class'=>'hz-input','placeholder'=>'Serial number']) !!}
                </div>
            </div>
        </div>

        {{-- ONU Info --}}
        <div class="hz-section-strip" style="margin-top:24px;">
            <div class="hz-section-dot" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);"></div>
            <span><i class="fas fa-broadcast-tower" style="margin-right:6px;color:#8b5cf6;"></i>ONU Information</span>
        </div>
        <div class="hz-form-body" style="padding-top:20px;padding-bottom:0;">
            <div class="hz-field-grid cols-2">
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-tag"></i> ONU Brand</label>
                    {!! Form::text('onu_brand', null, ['class'=>'hz-input','placeholder'=>'e.g. Huawei, ZTE']) !!}
                </div>
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-barcode"></i> ONU MAC Address</label>
                    {!! Form::text('onu_mac', null, ['class'=>'hz-input','placeholder'=>'AA:BB:CC:DD:EE:FF']) !!}
                </div>
            </div>
        </div>

        {{-- Configuration --}}
        <div class="hz-section-strip" style="margin-top:24px;">
            <div class="hz-section-dot" style="background:linear-gradient(135deg,var(--hz-warning),#d97706);"></div>
            <span><i class="fas fa-cogs" style="margin-right:6px;color:var(--hz-warning);"></i>Configuration</span>
        </div>
        <div class="hz-form-body" style="padding-top:20px;">
            <div class="hz-field-grid cols-4">
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-user"></i> Card Seller</label>
                    <div class="hz-select-wrap">
                        {!! Form::select('card_seller', $card_sellerItems, null, ['class'=>'hz-select']) !!}
                    </div>
                </div>
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-power-off"></i> Status <span class="req">*</span></label>
                    <div class="hz-select-wrap">
                        {!! Form::select('status', ['Enable'=>'Enable','Disable'=>'Disable'], null, ['class'=>'hz-select']) !!}
                    </div>
                </div>
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-bolt"></i> Has UPS</label>
                    <div class="hz-select-wrap">
                        {!! Form::select('has_ups', ['Disable'=>'NO','Enable'=>'YES'], null, ['class'=>'hz-select']) !!}
                    </div>
                </div>
                <div class="hz-field">
                    <label class="hz-label"><i class="fas fa-plug"></i> UPS Adapter</label>
                    {!! Form::text('usp_adapter', null, ['class'=>'hz-input','placeholder'=>'Adapter model']) !!}
                </div>
            </div>

            <div class="hz-submit-row">
                <button type="submit" class="hz-btn-submit">
                    <i class="fas fa-save"></i> Update Zone
                </button>
                <a href="{{ route('hotspotZones.index') }}" class="hz-btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>

</div>
@endsection
