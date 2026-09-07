@extends('layouts.app')
@section('title')
    Bulk Communications
@endsection

@section('css')
<style>
    /* Modern UI Custom Design Tokens */
    .card-custom {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #ffffff;
    }

    .nav-tabs-custom {
        border-bottom: none;
        background: #f4f6f9;
        padding: 6px;
        border-radius: 10px;
        gap: 6px;
    }

    .nav-tabs-custom .nav-link {
        border: none !important;
        border-radius: 8px !important;
        color: #6c757d;
        font-weight: 600;
        padding: 12px 24px;
        transition: all 0.25s ease;
    }

    .nav-tabs-custom .nav-link:hover {
        color: #34395e;
        background: rgba(255, 255, 255, 0.6);
    }

    .nav-tabs-custom .nav-link.active#sms-tab {
        background: linear-gradient(135deg, #6777ef 0%, #3549e6 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(103, 119, 239, 0.35);
    }

    .nav-tabs-custom .nav-link.active#voice-tab {
        background: linear-gradient(135deg, #11cdef 0%, #11988d 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(17, 205, 239, 0.35);
    }

    .form-group label {
        font-weight: 600;
        color: #34395e;
        margin-bottom: 6px;
        font-size: 0.9rem;
    }

    .form-control-custom {
        border-radius: 8px;
        border: 1.5px solid #e4e6fc;
        padding: 10px 14px;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control-custom:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.15);
    }

    .input-icon-group {
        position: relative;
    }

    .input-icon-group i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #a5a8ad;
        z-index: 10;
    }

    .input-icon-group select, .input-icon-group input {
        padding-left: 40px !important;
    }

    .badge-preview {
        padding: 8px 14px;
        font-size: 0.88rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .tracker-card {
        border-radius: 12px;
        border-top: 4px solid #11cdef;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .stat-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        transition: all 0.2s;
    }

    .stat-box:hover {
        background: #f1f3f5;
    }

    .stat-number {
        font-size: 1.4rem;
        font-weight: 700;
    }

    .pulse-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .pulse-processing {
        background-color: #11cdef;
        box-shadow: 0 0 0 0 rgba(17, 205, 239, 0.7);
        animation: pulse 1.6s infinite;
    }

    .pulse-complete {
        background-color: #2dce89;
    }

    .pulse-failed {
        background-color: #f5365c;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(17, 205, 239, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(17, 205, 239, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(17, 205, 239, 0); }
    }
</style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="page__heading m-0 text-dark fw-bold">Bulk Communications</h3>
                <small class="text-muted">Broadcast SMS and Voice Campaign Portal</small>
            </div>
            <div class="filter-container">
                <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left mr-1"></i> @lang('crud.back')
                </a>
            </div>
        </div>

        <div class="content">
            @include('stisla-templates::common.errors')

            <div class="section-body">
                <div class="row">
                    <!-- Left Column: Bulk Forms -->
                    <div class="col-lg-8 mb-4">
                        <div class="card card-custom">
                            <div class="card-body p-4">

                                <!-- Custom Nav Tabs -->
                                <ul class="nav nav-pills nav-tabs-custom mb-4" id="bulkCommsTab" role="tablist">
                                    <li class="nav-item flex-fill text-center" role="presentation">
                                        <a class="nav-link active" id="sms-tab" data-toggle="tab" href="#bulkSms" role="tab" aria-controls="bulkSms" aria-selected="true">
                                            <i class="fas fa-comment-alt mr-2"></i> Bulk SMS
                                        </a>
                                    </li>
                                    <li class="nav-item flex-fill text-center" role="presentation">
                                        <a class="nav-link" id="voice-tab" data-toggle="tab" href="#bulkVoice" role="tab" aria-controls="bulkVoice" aria-selected="false">
                                            <i class="fas fa-phone-alt mr-2"></i> Bulk Voice Broadcast 🗣️
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="bulkCommsTabContent">

                                    <!-- TAB 1: BULK SMS -->
                                    <div class="tab-pane fade show active" id="bulkSms" role="tabpanel" aria-labelledby="sms-tab">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h5 class="m-0 text-primary font-weight-bold">
                                                <i class="fas fa-paper-plane mr-2"></i> Send Bulk Text SMS
                                            </h5>
                                            <span class="badge badge-light border text-muted">Direct SMS</span>
                                        </div>
                                        <hr class="mt-2 mb-4">

                                        <form id="sms_form" action="{{ route('bulk_sms') }}" method="POST">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="sms_client_status">Select Clients Group:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-users"></i>
                                                    <select name="client_status" id="sms_client_status" class="form-control form-control-custom" required>
                                                        <option value="">Choose target client group...</option>
                                                        <option value="custom">Custom Numbers List</option>
                                                        <option value="expired">Expired Clients</option>
                                                        <option value="expired_this_month">Expired This Month ({{ $expired_this_month }} Clients)</option>
                                                        <option value="expired_today">Expired Today ({{ $expired_today }} Clients)</option>
                                                        <option value="expiring">Expiring Tomorrow ({{ $expiring_soon }} Clients)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="sms_isp_code">Filter by ISP Provider:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-globe"></i>
                                                    <select name="isp_code" id="sms_isp_code" class="form-control form-control-custom">
                                                        <option value="">🌐 All ISPs</option>
                                                        <option value="carnival">🎪 Carnival</option>
                                                        <option value="bijoy">⚡ Bijoy</option>
                                                        <option value="icc">📡 ICC</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3" id="sms_custom_numbers_div" style="display:none;">
                                                <label for="sms_custom_numbers">Custom Mobile Numbers (One per line):</label>
                                                <textarea name="custom_contacts" id="sms_custom_numbers" class="form-control form-control-custom" rows="4"
                                                    placeholder="e.g.&#10;01712345678&#10;01812345678"></textarea>
                                                <small class="form-text text-muted">Enter numbers without '88' prefix. One contact per line.</small>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="temp">Load Predefined Template:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-file-alt"></i>
                                                    <select id="temp" class="form-control form-control-custom">
                                                        <option value="">Select a message template...</option>
                                                        @foreach ($templates as $template)
                                                            <option value="{{ $template->sms_template }}">{{ $template->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label for="sms-body" class="m-0">SMS Body Content:</label>
                                                    <span id="sms-counter" class="badge badge-light border px-2 py-1">
                                                        Messages: <span class="messages text-primary fw-bold">1</span> |
                                                        Remaining: <span class="remaining text-success fw-bold">160</span>
                                                    </span>
                                                </div>
                                                <textarea name="sms_body" id="sms-body" style="min-height: 120px;" class="form-control form-control-custom"
                                                    placeholder="Type your SMS message content here..." required></textarea>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                                                <div>
                                                    <button type="button" onclick="previewContacts('sms')" class="btn btn-outline-info rounded-pill px-3 mr-2">
                                                        <i class="fas fa-eye mr-1"></i> Preview Contacts
                                                    </button>
                                                    <button type="button" onclick="resetText()" class="btn btn-outline-secondary rounded-pill px-3">
                                                        <i class="fas fa-redo mr-1"></i> Reset
                                                    </button>
                                                </div>
                                                <button type="submit" onclick="Swal.showLoading();" class="btn btn-success rounded-pill px-4 shadow-sm">
                                                    <i class="fas fa-paper-plane mr-1"></i> Send Bulk SMS
                                                </button>
                                            </div>
                                            <div class="mt-2 text-center">
                                                <span id="sms_preview_result" class="badge badge-preview bg-light border text-dark" style="display:none;"></span>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- TAB 2: BULK VOICE MESSAGE -->
                                    <div class="tab-pane fade" id="bulkVoice" role="tabpanel" aria-labelledby="voice-tab">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h5 class="m-0 text-info font-weight-bold">
                                                <i class="fas fa-broadcast-tower mr-2"></i> Elit Call Voice Broadcast
                                            </h5>
                                            <span class="badge badge-info px-3 py-1 rounded-pill">Elit Call API Integration</span>
                                        </div>
                                        <hr class="mt-2 mb-4">

                                        <form action="{{ route('bulk_voice_campaign') }}" method="POST" id="voice_campaign_form">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="voice_client_status">Select Target Audience Group:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-users"></i>
                                                    <select name="client_status" id="voice_client_status" class="form-control form-control-custom" required>
                                                        <option value="">Choose target client group...</option>
                                                        <option value="custom">Custom Numbers List</option>
                                                        <option value="expired">Expired Clients</option>
                                                        <option value="registered">Registered Clients</option>
                                                        <option value="expired_this_month">Expired This Month ({{ $expired_this_month }} Clients)</option>
                                                        <option value="expired_today">Expired Today ({{ $expired_today }} Clients)</option>
                                                        <option value="expiring">Expiring Tomorrow ({{ $expiring_soon }} Clients)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="voice_isp_code">Filter by ISP Provider:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-globe"></i>
                                                    <select name="isp_code" id="voice_isp_code" class="form-control form-control-custom">
                                                        <option value="">🌐 All ISPs</option>
                                                        <option value="carnival">🎪 Carnival</option>
                                                        <option value="bijoy">⚡ Bijoy</option>
                                                        <option value="icc">📡 ICC</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3" id="voice_custom_numbers_div" style="display:none;">
                                                <label for="voice_custom_numbers">Custom Mobile Numbers (One per line):</label>
                                                <textarea name="custom_contacts" id="voice_custom_numbers" class="form-control form-control-custom" rows="4"
                                                    placeholder="e.g.&#10;01712345678&#10;01812345678"></textarea>
                                                <small class="form-text text-muted">Numbers will be auto-formatted to '8801XXXXXXXXX' for Elit Call API.</small>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="broadcast_id">Select Voice Audio Message:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-microphone-alt"></i>
                                                    <select name="broadcast_id" id="broadcast_id" class="form-control form-control-custom" required>
                                                        <option value="">Select pre-recorded voice audio...</option>
                                                        <option value="1679">Broadcast #1679 — Carnival Expiring Tomorrow Audio</option>
                                                        <option value="1678">Broadcast #1678 — Carnival Expired Today Audio</option>
                                                        <option value="custom_id">-- Input Custom Approved Broadcast ID --</option>
                                                    </select>
                                                </div>
                                                <div id="custom_broadcast_div" class="mt-2" style="display:none;">
                                                    <input type="number" id="custom_broadcast_input" class="form-control form-control-custom border-warning" placeholder="Enter Approved Voice Broadcast ID (e.g. 12345)">
                                                </div>
                                                <small class="form-text text-muted">Refers to the approved voice broadcast audio ID on Elit Call dashboard.</small>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="campaign_title">Campaign Identifier Title:</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-tag"></i>
                                                    <input type="text" name="campaign_title" id="campaign_title" class="form-control form-control-custom"
                                                        placeholder="E.g. Nov Campaign - Expiring Tomorrow"
                                                        value="Voice Campaign - {{ now()->format('M d, Y H:i:s') }}" required>
                                                </div>
                                                <small class="form-text text-muted">Supports Bengali and Alphanumeric text. Automatically appended with unique timestamp.</small>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="sender">Caller ID (Sender):</label>
                                                <div class="input-icon-group">
                                                    <i class="fas fa-phone-square"></i>
                                                    <input type="text" name="sender" id="sender" class="form-control form-control-custom bg-light"
                                                        value="9610990410" required readonly>
                                                </div>
                                                <small class="form-text text-muted">Your registered Elit Call virtual number.</small>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                                                <div>
                                                    <button type="button" onclick="previewContacts('voice')" class="btn btn-outline-info rounded-pill px-3 mr-2">
                                                        <i class="fas fa-eye mr-1"></i> Preview Contacts
                                                    </button>
                                                    <button type="reset" class="btn btn-outline-secondary rounded-pill px-3">
                                                        <i class="fas fa-redo mr-1"></i> Reset
                                                    </button>
                                                </div>
                                                <button type="submit" onclick="Swal.showLoading();" class="btn btn-info rounded-pill px-4 shadow-sm text-white">
                                                    <i class="fas fa-bullhorn mr-1"></i> Start Voice Campaign
                                                </button>
                                            </div>
                                            <div class="mt-2 text-center">
                                                <span id="voice_preview_result" class="badge badge-preview bg-light border text-dark" style="display:none;"></span>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Campaign Status Tracker Widget -->
                    <div class="col-lg-4">
                        <div class="card card-custom tracker-card sticky-top" style="top: 20px;">
                            <div class="card-header bg-transparent border-0 pb-0 pt-4">
                                <h5 class="card-title text-dark font-weight-bold m-0">
                                    <i class="fas fa-chart-pie text-info mr-2"></i> Voice Campaign Tracker
                                </h5>
                                <small class="text-muted">Live status query via Elit Call API</small>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="check_campaign_id">Enter Campaign ID:</label>
                                    <div class="input-group">
                                        <input type="number" id="check_campaign_id" class="form-control form-control-custom" placeholder="e.g., 67890">
                                        <div class="input-group-append">
                                            <button class="btn btn-info text-white rounded-right px-3" type="button" id="btn_check_status" onclick="checkCampaignStatus()">
                                                <i class="fas fa-search mr-1"></i> Query
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="campaign_status_result" class="mt-4" style="display:none;">
                                    <div class="p-3 bg-light rounded-lg border mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Campaign Title</span>
                                            <span id="res_campaign_status" class="badge rounded-pill px-3 py-1"></span>
                                        </div>
                                        <h6 id="res_campaign_title" class="text-dark font-weight-bold mb-0 text-truncate"></h6>
                                    </div>

                                    <!-- Visual Progress Bar -->
                                    <div class="mb-3">
                                        <small class="text-muted font-weight-bold">Call Delivery Progress:</small>
                                        <div class="progress mt-1" style="height: 10px; border-radius: 6px;">
                                            <div id="prog_answered" class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                            <div id="prog_no_answer" class="progress-bar bg-warning" role="progressbar" style="width: 0%"></div>
                                            <div id="prog_failed" class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                        </div>
                                    </div>

                                    <div class="row g-2 text-center">
                                        <div class="col-6 mb-2">
                                            <div class="stat-box">
                                                <small class="text-muted d-block">Total Scheduled</small>
                                                <span id="res_total_calls" class="stat-number text-primary">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="stat-box">
                                                <small class="text-success d-block">Answered</small>
                                                <span id="res_answered_calls" class="stat-number text-success">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="stat-box">
                                                <small class="text-warning d-block">No Answer</small>
                                                <span id="res_no_answer_calls" class="stat-number text-warning">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="stat-box">
                                                <small class="text-danger d-block">Failed / Rejected</small>
                                                <span id="res_failed_calls" class="stat-number text-danger">0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check form-switch mt-3 d-flex align-items-center">
                                        <input class="form-check-input mr-2" type="checkbox" id="auto_refresh_toggle">
                                        <label class="form-check-label text-muted small" for="auto_refresh_toggle">
                                            Auto-refresh status every 10s
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="{{ asset('js/sms_counter.min.js') }}"></script>
<script>
    var autoRefreshTimer = null;

    $(document).ready(function() {
        // Initialize SMS Counter
        $('#sms-body').countSms('#sms-counter');

        // Dynamic Title Generator for Voice Campaign
        $('#voice_client_status, #broadcast_id').on('change', function() {
            var group = $('#voice_client_status option:selected').text();
            var broadcast = $('#broadcast_id option:selected').text();

            if ($('#voice_client_status').val() && $('#broadcast_id').val()) {
                let status = $('#voice_client_status').val();
                let groupText = status === 'custom' ? 'Custom Numbers' : group;
                let broadcastText = broadcast.includes(' - ') ? broadcast.split(' - ')[1].replace(/\(|\)/g, '').trim() : broadcast;

                var newTitle = 'Voice - ' + groupText + ' - ' + broadcastText;
                $('#campaign_title').val(newTitle);
            }

            // Auto-trigger contact count preview when group changes
            if ($('#voice_client_status').val()) {
                previewContacts('voice');
            }
        });

        $('#sms_client_status, #sms_isp_code').on('change', function() {
            if ($('#sms_client_status').val()) {
                previewContacts('sms');
            }
        });

        // Toggle Custom Numbers Input (SMS)
        $('#sms_client_status').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#sms_custom_numbers_div').slideDown(200);
                $('#sms_custom_numbers').prop('required', true);
            } else {
                $('#sms_custom_numbers_div').slideUp(200);
                $('#sms_custom_numbers').prop('required', false).val('');
            }
        });

        // Toggle Custom Numbers Input (Voice)
        $('#voice_client_status').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#voice_custom_numbers_div').slideDown(200);
                $('#voice_custom_numbers').prop('required', true);
            } else {
                $('#voice_custom_numbers_div').slideUp(200);
                $('#voice_custom_numbers').prop('required', false).val('');
            }
        });

        // Toggle Custom Broadcast ID Input
        $('#broadcast_id').on('change', function() {
            if ($(this).val() === 'custom_id') {
                $('#custom_broadcast_div').slideDown(200);
                $('#custom_broadcast_input').prop('required', true);
            } else {
                $('#custom_broadcast_div').slideUp(200);
                $('#custom_broadcast_input').prop('required', false).val('');
            }
        });

        // Form Submission custom broadcast ID handler
        $('#voice_campaign_form').on('submit', function(e) {
            if ($('#broadcast_id').val() === 'custom_id') {
                var customVal = $('#custom_broadcast_input').val();
                if (!customVal) {
                    e.preventDefault();
                    Swal.close();
                    Swal.fire('Input Required', 'Please enter a valid custom Broadcast ID.', 'warning');
                    return false;
                }
                $(this).append('<input type="hidden" name="broadcast_id" value="' + customVal + '">');
                $('#broadcast_id').prop('disabled', true);
            }
        });

        // Auto Refresh Toggle Listener
        $('#auto_refresh_toggle').on('change', function() {
            if (this.checked) {
                autoRefreshTimer = setInterval(function() {
                    checkCampaignStatus(true);
                }, 10000);
            } else {
                if (autoRefreshTimer) clearInterval(autoRefreshTimer);
            }
        });
    });

    // SMS Template Auto-Fill
    $('#temp').on('change', function() {
        if (this.value) {
            $('#sms-body').val(this.value);
            $('#sms-body').countSms('#sms-counter');
        }
    });

    function resetText() {
        $("#sms-body").val('').countSms('#sms-counter');
        $('#sms_client_status').val('').trigger('change');
        $('#temp').val('');
        $('#sms_preview_result').hide();
    }

    // Tab Navigation
    $('#bulkCommsTab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Contact Preview Function
    function previewContacts(tab) {
        var clientStatus, ispCode, customContacts, resultBadge;

        if (tab === 'sms') {
            clientStatus   = $('#sms_client_status').val();
            ispCode        = $('#sms_isp_code').val();
            customContacts = $('#sms_custom_numbers').val();
            resultBadge    = $('#sms_preview_result');
        } else {
            clientStatus   = $('#voice_client_status').val();
            ispCode        = $('#voice_isp_code').val();
            customContacts = $('#voice_custom_numbers').val();
            resultBadge    = $('#voice_preview_result');
        }

        if (!clientStatus) return;

        resultBadge.html('<i class="fas fa-spinner fa-spin mr-1"></i> Calculating contacts...').removeClass().addClass('badge badge-preview bg-light border text-primary').show();

        $.ajax({
            url: '{{ route("bulk_sms.preview") }}',
            method: 'GET',
            data: {
                client_status:   clientStatus,
                isp_code:        ispCode,
                custom_contacts: customContacts,
                _token:          '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.error) {
                    resultBadge.html('<i class="fas fa-exclamation-triangle mr-1"></i> Error: ' + res.error).removeClass().addClass('badge badge-preview bg-danger text-white').show();
                    return;
                }
                var label = res.isp + ' — ' + res.label;
                if (res.count === 0) {
                    resultBadge.html('<i class="fas fa-exclamation-circle mr-1"></i> 0 contacts matched (' + label + ')').removeClass().addClass('badge badge-warning text-dark').show();
                } else {
                    resultBadge.html('<i class="fas fa-check-circle mr-1"></i> ' + res.count + ' contact(s) queued (' + label + ')').removeClass().addClass('badge badge-success text-white').show();
                }
            },
            error: function() {
                resultBadge.html('<i class="fas fa-times-circle mr-1"></i> Request failed').removeClass().addClass('badge badge-danger text-white').show();
            }
        });
    }

    // Query Campaign Status via Elit Call API
    function checkCampaignStatus(silent) {
        var campaignId = $('#check_campaign_id').val();
        if (!campaignId) {
            if (!silent) Swal.fire('Input Required', 'Please enter a Campaign ID to check status.', 'warning');
            return;
        }

        var btn = $('#btn_check_status');
        if (!silent) {
            btn.html('<i class="fas fa-spinner fa-spin"></i>');
        }

        $.ajax({
            url: '{{ url("bulk-voice-campaign") }}/' + campaignId + '/status',
            method: 'GET',
            success: function(res) {
                btn.html('<i class="fas fa-search mr-1"></i> Query');
                if (res.success && res.data) {
                    var data = res.data;
                    var campaign = data.campaign || {};
                    var stats    = data.stats || {};

                    $('#res_campaign_title').text(campaign.title || ('Campaign #' + campaignId));

                    var st = (campaign.status || 'processing').toLowerCase();
                    var badgeHtml = '';
                    if (st === 'complete') {
                        badgeHtml = '<span class="pulse-indicator pulse-complete"></span><span class="badge bg-success text-white">Complete</span>';
                    } else if (st === 'failed') {
                        badgeHtml = '<span class="pulse-indicator pulse-failed"></span><span class="badge bg-danger text-white">Failed</span>';
                    } else {
                        badgeHtml = '<span class="pulse-indicator pulse-processing"></span><span class="badge bg-info text-white">Processing</span>';
                    }
                    $('#res_campaign_status').html(badgeHtml);

                    var total = parseInt(stats.total_calls || campaign.total || 0);
                    var ans   = parseInt(stats.answered_calls || 0);
                    var noAns = parseInt(stats.no_answer_calls || 0);
                    var fail  = parseInt(stats.failed_calls || 0) + parseInt(stats.rejected_calls || 0);

                    $('#res_total_calls').text(total);
                    $('#res_answered_calls').text(ans);
                    $('#res_no_answer_calls').text(noAns);
                    $('#res_failed_calls').text(fail);

                    if (total > 0) {
                        $('#prog_answered').css('width', (ans / total * 100) + '%');
                        $('#prog_no_answer').css('width', (noAns / total * 100) + '%');
                        $('#prog_failed').css('width', (fail / total * 100) + '%');
                    } else {
                        $('#prog_answered, #prog_no_answer, #prog_failed').css('width', '0%');
                    }

                    $('#campaign_status_result').slideDown(250);
                } else {
                    if (!silent) Swal.fire('Error', res.error ? JSON.stringify(res.error) : 'Unable to fetch status.', 'error');
                }
            },
            error: function(err) {
                btn.html('<i class="fas fa-search mr-1"></i> Query');
                if (!silent) {
                    var msg = err.responseJSON && err.responseJSON.error ? (err.responseJSON.error.detail || JSON.stringify(err.responseJSON.error)) : 'Failed to query campaign status.';
                    Swal.fire('Failed', msg, 'error');
                }
            }
        });
    }
</script>

@if(session('voice_campaign_id'))
<script>
    $(document).ready(function() {
        var autoCampaignId = "{{ session('voice_campaign_id') }}";
        if (autoCampaignId) {
            // Activate Voice tab
            $('#voice-tab').tab('show');
            // Populate Campaign Tracker with the newly created Campaign ID
            $('#check_campaign_id').val(autoCampaignId);
            // Enable auto-refresh toggle (fetches status every 10s)
            $('#auto_refresh_toggle').prop('checked', true).trigger('change');
            // Fetch initial status immediately
            checkCampaignStatus();
            // Smooth scroll to the tracker widget
            $('html, body').animate({
                scrollTop: $('.tracker-card').offset().top - 30
            }, 400);
        }
    });
</script>
@endif
@endsection
