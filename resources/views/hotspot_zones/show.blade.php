@extends('layouts.app')
@section('title') Zone Details — {{ $hotspotZone->zone_id }} @endsection

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
        --hz-shadow:    0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .hz-detail-page { max-width:960px; margin:0 auto; padding:24px; animation:hzFadeUp .4s ease both; }
    @keyframes hzFadeUp { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }

    /* ── top bar ── */
    .hz-detail-header {
        display:flex; align-items:flex-start; justify-content:space-between;
        flex-wrap:wrap; gap:16px; margin-bottom:24px;
    }
    .hz-detail-hero {
        background: linear-gradient(135deg, var(--hz-primary) 0%, var(--hz-accent) 100%);
        border-radius:var(--hz-radius);
        padding:28px 32px;
        display:flex; align-items:center; gap:20px;
        flex-wrap:wrap;
        box-shadow: 0 6px 22px rgba(103, 119, 239, 0.25);
        position:relative; overflow:hidden;
        margin-bottom:24px;
    }
    .hz-detail-hero::before {
        content:''; position:absolute; inset:0;
        background:radial-gradient(ellipse at 80% 50%,rgba(255,255,255,.2) 0%,transparent 70%);
        pointer-events:none;
    }
    .hz-detail-zone-icon {
        width:64px; height:64px; border-radius:12px;
        background:rgba(255, 255, 255, 0.25);
        display:flex; align-items:center; justify-content:center;
        font-size:28px; color:#fff;
        backdrop-filter: blur(4px);
        box-shadow:0 4px 12px rgba(0,0,0,.08);
        flex-shrink:0;
    }
    .hz-detail-info h1 { font-size:1.6rem; font-weight:800; color:#fff; margin:0 0 4px; }
    .hz-detail-info .hz-id-tag {
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
        color:#ffffff; padding:3px 12px;
        border-radius:20px; font-size:.82rem; font-family:monospace; font-weight:700;
    }
    .hz-detail-status { margin-top:10px; }

    .hz-status-pill {
        display:inline-flex; align-items:center; gap:7px;
        padding:6px 16px; border-radius:20px; font-size:.82rem; font-weight:700;
    }
    .hz-status-pill.on  { background:rgba(255,255,255,.2); color:#ffffff; border:1px solid rgba(255,255,255,.35); }
    .hz-status-pill.off { background:rgba(252,84,75,.25);  color:#ffffff; border:1px solid rgba(252,84,75,.4); }
    .hz-status-dot { width:8px; height:8px; border-radius:50%; }
    .hz-status-pill.on  .hz-status-dot { background:#ffffff; animation:pulse 2s ease infinite; }
    .hz-status-pill.off .hz-status-dot { background:#ffffff; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.3;} }

    .hz-hero-actions { display:flex; gap:10px; flex-wrap:wrap; margin-left:auto; align-self:center; }
    .hz-btn {
        display:inline-flex; align-items:center; gap:7px;
        padding:9px 18px; border-radius:8px; border:none;
        font-size:.85rem; font-weight:600; cursor:pointer;
        text-decoration:none; transition:all .18s; white-space:nowrap;
    }
    .hz-btn:hover { transform:translateY(-2px); text-decoration: none; }
    .hz-btn-edit {
        background:#ffffff;
        color:var(--hz-warning); box-shadow:0 4px 12px rgba(0,0,0,.08);
    }
    .hz-btn-edit:hover { box-shadow:0 6px 16px rgba(0,0,0,.12); color:#d39e00; }
    .hz-btn-back {
        background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); color:#fff;
    }
    .hz-btn-back:hover { background:rgba(255,255,255,.25); color:#fff; }

    /* ── info grid ── */
    .hz-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; margin-bottom:20px; }
    @media(max-width:640px) { .hz-grid { grid-template-columns:1fr; } }

    .hz-info-card {
        background:var(--hz-surface);
        border:1px solid var(--hz-border);
        border-radius:var(--hz-radius);
        padding:20px;
        transition:transform .2s,box-shadow .2s;
        box-shadow:var(--hz-shadow);
    }
    .hz-info-card:hover { transform:translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.06); }

    .hz-info-card-head {
        display:flex; align-items:center; gap:10px;
        margin-bottom:18px; padding-bottom:12px;
        border-bottom:1px solid var(--hz-border);
    }
    .hz-info-card-icon {
        width:36px; height:36px; border-radius:8px;
        display:flex; align-items:center; justify-content:center;
        font-size:15px; flex-shrink:0;
    }
    .hz-info-card-icon.indigo { background:rgba(103,119,239,.1); color:var(--hz-primary); }
    .hz-info-card-icon.cyan   { background:rgba(58,186,244,.1);  color:var(--hz-accent); }
    .hz-info-card-icon.purple { background:rgba(139,92,246,.1); color:#8b5cf6; }
    .hz-info-card-icon.amber  { background:rgba(255,164,38,.1); color:var(--hz-warning); }
    .hz-info-card-title { font-size:.82rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--hz-muted); }

    .hz-field-row { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; gap:10px; }
    .hz-field-row:last-child { margin-bottom:0; }
    .hz-field-label { font-size:.78rem; color:var(--hz-muted); flex-shrink:0; width:130px; }
    .hz-field-value { font-size:.88rem; font-weight:600; color:var(--hz-text); text-align:right; word-break:break-all; }
    .hz-mac-val { font-family:monospace; font-size:.82rem; color:var(--hz-primary); font-weight:700; }

    .hz-copy-btn {
        display:inline-flex; align-items:center;
        background:rgba(103,119,239,.08); color:var(--hz-primary);
        border:none; border-radius:4px; padding:2px 7px;
        font-size:.72rem; cursor:pointer; transition:all .18s;
        margin-left:6px;
    }
    .hz-copy-btn:hover { background:rgba(103,119,239,.18); color:var(--hz-primary-d); }

    .hz-yes-badge { display:inline-flex; align-items:center; gap:5px; background:rgba(71,195,99,.12); color:#28a745; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
    .hz-no-badge  { display:inline-flex; align-items:center; gap:5px; background:rgba(252,84,75,.12);  color:#dc3545; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }

    /* timestamps card */
    .hz-ts-card {
        background:var(--hz-surface);
        border:1px solid var(--hz-border);
        border-radius:var(--hz-radius);
        padding:18px 24px;
        display:flex; gap:32px; flex-wrap:wrap;
        box-shadow:var(--hz-shadow);
    }
    .hz-ts-item { }
    .hz-ts-label { font-size:.72rem; color:var(--hz-muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px; }
    .hz-ts-val   { font-size:.85rem; font-weight:600; color:var(--hz-text); }
</style>
@endsection

@section('content')
<div class="hz-detail-page">

    {{-- ── Hero ── --}}
    <div class="hz-detail-hero">
        <div class="hz-detail-zone-icon"><i class="fas fa-wifi"></i></div>
        <div class="hz-detail-info">
            <h1>{{ $hotspotZone->zone_title }}</h1>
            <div class="hz-id-tag"><i class="fas fa-hashtag"></i>{{ $hotspotZone->zone_id }}</div>
            <div class="hz-detail-status">
                @if($hotspotZone->status === 'Enable')
                    <span class="hz-status-pill on"><span class="hz-status-dot"></span> Active & Enabled</span>
                @else
                    <span class="hz-status-pill off"><span class="hz-status-dot"></span> Disabled</span>
                @endif
            </div>
        </div>
        <div class="hz-hero-actions">
            <a href="{{ route('hotspotZones.edit', $hotspotZone->id) }}" class="hz-btn hz-btn-edit">
                <i class="fas fa-pen"></i> Edit Zone
            </a>
            <a href="{{ route('hotspotZones.index') }}" class="hz-btn hz-btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- ── Info Cards Grid ── --}}
    <div class="hz-grid">

        {{-- Zone Identity --}}
        <div class="hz-info-card">
            <div class="hz-info-card-head">
                <div class="hz-info-card-icon indigo"><i class="fas fa-fingerprint"></i></div>
                <div class="hz-info-card-title">Zone Identity</div>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Zone ID</span>
                <span class="hz-field-value" style="font-family:monospace;color:var(--hz-primary);">{{ $hotspotZone->zone_id }}</span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Zone Title</span>
                <span class="hz-field-value">{{ $hotspotZone->zone_title ?: '—' }}</span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Card Seller</span>
                <span class="hz-field-value">{{ $hotspotZone->card_seller ?: '—' }}</span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Status</span>
                <span class="hz-field-value">
                    @if($hotspotZone->status === 'Enable')
                        <span class="hz-yes-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                    @else
                        <span class="hz-no-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Device Info --}}
        <div class="hz-info-card">
            <div class="hz-info-card-head">
                <div class="hz-info-card-icon cyan"><i class="fas fa-microchip"></i></div>
                <div class="hz-info-card-title">Device Information</div>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Device Brand</span>
                <span class="hz-field-value">{{ $hotspotZone->device_brand ?: '—' }}</span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Device MAC</span>
                <span class="hz-field-value">
                    @if($hotspotZone->device_mac)
                        <span class="hz-mac-val">{{ $hotspotZone->device_mac }}</span>
                        <button class="hz-copy-btn" onclick="hzCopy('{{ $hotspotZone->device_mac }}')">
                            <i class="fas fa-copy"></i>
                        </button>
                    @else —
                    @endif
                </span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Device Serial</span>
                <span class="hz-field-value">{{ $hotspotZone->device_serial ?: '—' }}</span>
            </div>
        </div>

        {{-- ONU Info --}}
        <div class="hz-info-card">
            <div class="hz-info-card-head">
                <div class="hz-info-card-icon purple"><i class="fas fa-broadcast-tower"></i></div>
                <div class="hz-info-card-title">ONU Information</div>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">ONU Brand</span>
                <span class="hz-field-value">{{ $hotspotZone->onu_brand ?: '—' }}</span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">ONU MAC</span>
                <span class="hz-field-value">
                    @if($hotspotZone->onu_mac)
                        <span class="hz-mac-val">{{ $hotspotZone->onu_mac }}</span>
                        <button class="hz-copy-btn" onclick="hzCopy('{{ $hotspotZone->onu_mac }}')">
                            <i class="fas fa-copy"></i>
                        </button>
                    @else —
                    @endif
                </span>
            </div>
        </div>

        {{-- Power / UPS --}}
        <div class="hz-info-card">
            <div class="hz-info-card-head">
                <div class="hz-info-card-icon amber"><i class="fas fa-bolt"></i></div>
                <div class="hz-info-card-title">Power Configuration</div>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">Has UPS</span>
                <span class="hz-field-value">
                    @if($hotspotZone->has_ups === 'Enable')
                        <span class="hz-yes-badge"><i class="fas fa-bolt"></i> YES</span>
                    @else
                        <span class="hz-no-badge"><i class="fas fa-times"></i> NO</span>
                    @endif
                </span>
            </div>
            <div class="hz-field-row">
                <span class="hz-field-label">UPS Adapter</span>
                <span class="hz-field-value">{{ $hotspotZone->usp_adapter ?: '—' }}</span>
            </div>
        </div>
    </div>

    {{-- Timestamps --}}
    <div class="hz-ts-card">
        <div class="hz-ts-item">
            <div class="hz-ts-label"><i class="fas fa-clock" style="margin-right:4px;"></i>Created At</div>
            <div class="hz-ts-val">{{ $hotspotZone->created_at ? $hotspotZone->created_at->format('d M Y, h:i A') : '—' }}</div>
        </div>
        <div class="hz-ts-item">
            <div class="hz-ts-label"><i class="fas fa-history" style="margin-right:4px;"></i>Last Updated</div>
            <div class="hz-ts-val">{{ $hotspotZone->updated_at ? $hotspotZone->updated_at->format('d M Y, h:i A') : '—' }}</div>
        </div>
    </div>

</div>
@endsection

@section('page_js')
<script>
function hzCopy(text) {
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
}
</script>
@endsection
