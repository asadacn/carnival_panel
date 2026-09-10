<div class="container-fluid px-0">
    <!-- ===== Client Summary Cards ===== -->
    <div class="row g-3 mb-4">
        <!-- Status Card -->
        <div class="col-md-4">
            <div class="summary-card shadow-sm h-100" style="border-top: 3px solid #2563eb;">
                <div class="summary-card-body">
                    <div class="summary-icon bg-primary-subtle">
                        <i class="fas fa-info-circle text-primary"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Account Status</span>
                        <span class="badge bg-{{ $client->status == 'Active' ? 'success' : ($client->status == 'Expired' ? 'warning' : 'info') }} summary-status-badge mt-1">
                            {{ ucfirst($client->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Due Card -->
        <div class="col-md-4">
            <div class="summary-card shadow-sm h-100" style="border-top: 3px solid #ef4444;">
                <div class="summary-card-body">
                    <div class="summary-icon bg-danger-subtle">
                        <i class="fas fa-file-invoice-dollar text-danger"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Total Due Amount</span>
                        <span class="stat-number summary-value" data-target="{{ $client->total_due ?? 0 }}" data-currency="true">৳0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Card -->
        <div class="col-md-4">
            <div class="summary-card shadow-sm h-100" style="border-top: 3px solid #059669;">
                <div class="summary-card-body">
                    <div class="summary-icon bg-success-subtle">
                        <i class="fas fa-box-open text-success"></i>
                    </div>
                    <div class="summary-content">
                        <span class="summary-label">Package</span>
                        <span class="summary-value text-truncate">{{ $client->package ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Tabbed Interface ===== -->
    <div class="card shadow-sm border-0 modern-card">
        <div class="card-header bg-white border-0 p-0">
            <ul class="nav nav-tabs nav-fill client-tabs" id="clientTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active tab-btn" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                        <i class="fas fa-user-circle me-2"></i>Overview
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-btn" id="equipment-tab" data-bs-toggle="tab" data-bs-target="#equipment" type="button" role="tab">
                        <i class="fas fa-network-wired me-2"></i>Equipment
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-btn" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Timeline
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-btn" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button" role="tab">
                        <i class="fas fa-comments me-2"></i>Notes
                        @if($client->comments_count ?? 0)
                        <span class="badge bg-primary rounded-pill ms-1">{{ $client->comments_count ?? 0 }}</span>
                        @else
                        <span class="badge bg-secondary rounded-pill ms-1">0</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-btn" id="sms-logs-tab" data-bs-toggle="tab" data-bs-target="#sms-logs" type="button" role="tab">
                        <i class="fas fa-sms me-2"></i>SMS History
                        @if($client->sms_logs_count ?? 0)
                        <span class="badge bg-primary rounded-pill ms-1" id="clientSmsBadgeCount">{{ $client->sms_logs_count ?? 0 }}</span>
                        @else
                        <span class="badge bg-secondary rounded-pill ms-1" id="clientSmsBadgeCount">0</span>
                        @endif
                    </button>
                </li>
            </ul>

        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="clientTabsContent">

                <!-- ===== OVERVIEW TAB ===== -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="p-4">
                        <h6 class="section-title-modern mb-3">
                            <i class="fas fa-id-card me-2"></i>Client Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-user me-1"></i>Full Name</span>
                                    <span class="detail-value">{{ $client->name ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-id-badge me-1"></i>Customer ID</span>
                                    <span class="detail-value">{{ $client->username ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-wifi me-1"></i>ISP</span>
                                    <span class="detail-value">{{ $client->isp_code ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-phone me-1"></i>Primary Contact</span>
                                    <span class="detail-value">{{ $client->contact ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-mobile-alt me-1"></i>Secondary Contact</span>
                                    <span class="detail-value">{{ $client->secondary_contact ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-envelope me-1"></i>Email</span>
                                    <span class="detail-value">{{ $client->email ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-box-open me-1"></i>Package</span>
                                    <span class="detail-value">{{ $client->package ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-route me-1"></i>Billing Type</span>
                                    <span class="detail-value">{{ ucfirst($client->billing_type ?? 'prepaid') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-map-marker-alt me-1"></i>Address</span>
                                    <span class="detail-value">{{ $client->address ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-satellite-disc me-1"></i>GPS Location</span>
                                    <span class="detail-value">{{ $client->gps_location ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-sticky-note me-1"></i>Comment</span>
                                    <span class="detail-value">{{ $client->comment ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== EQUIPMENT TAB ===== -->
                <div class="tab-pane fade" id="equipment" role="tabpanel">
                    <div class="p-4">
                        <!-- ONU Information -->
                        <h6 class="section-title-modern mb-3">
                            <i class="fas fa-network-wired me-2"></i>ONU Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-tag me-1"></i>ONU Serial</span>
                                    <span class="detail-value">{{ $client->onu_serial ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-microchip me-1"></i>ONU Brand</span>
                                    <span class="detail-value">{{ $client->onu_brand ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-ethernet me-1"></i>ONU MAC</span>
                                    <span class="detail-value">{{ $client->Onu_mac ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-gift me-1"></i>ONU Free</span>
                                    <span class="detail-value">
                                        @if($client->onu_free)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Yes</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>No</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-user me-1"></i>ONU Owner</span>
                                    <span class="detail-value">{{ ucfirst($client->onu_owner ?? 'client') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-undo me-1"></i>ONU Returned</span>
                                    <span class="detail-value">
                                        @if($client->onu_returned)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Returned</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Not Returned</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-calendar me-1"></i>Return Date</span>
                                    <span class="detail-value">{{ $client->onu_returned_at ? \Carbon\Carbon::parse($client->onu_returned_at)->setTimezone('Asia/Dhaka')->format('d-m-Y H:i') : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-comment-dots me-1"></i>Return Reason</span>
                                    <span class="detail-value">{{ $client->onu_return_reason ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cable Information -->
                        <h6 class="section-title-modern mt-4 mb-3">
                            <i class="fas fa-plug me-2"></i>Cable Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-hashtag me-1"></i>Cable</span>
                                    <span class="detail-value">{{ $client->cable ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-user me-1"></i>Cable Owner</span>
                                    <span class="detail-value">{{ ucfirst($client->cable_owner ?? 'company') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-undo me-1"></i>Cable Returned</span>
                                    <span class="detail-value">
                                        @if($client->cable_returned)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Returned</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Not Returned</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-calendar me-1"></i>Return Date</span>
                                    <span class="detail-value">{{ $client->cable_returned_at ? \Carbon\Carbon::parse($client->cable_returned_at)->setTimezone('Asia/Dhaka')->format('d-m-Y H:i') : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-comment-dots me-1"></i>Cable Return Reason</span>
                                    <span class="detail-value">{{ $client->cable_return_reason ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== TIMELINE TAB ===== -->
                <div class="tab-pane fade" id="timeline" role="tabpanel">
                    <div class="p-4">
                        <h6 class="section-title-modern mb-3">
                            <i class="fas fa-history me-2"></i>Account Timeline
                        </h6>
                        <div class="timeline">
                            <!-- Created -->
                            <div class="timeline-item">
                                <div class="timeline-icon bg-primary">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Account Created</h6>
                                    <p class="timeline-text text-muted small mb-1">
                                        {{ \Carbon\Carbon::parse($client->created_at)->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                                    </p>
                                    <span class="timeline-badge bg-primary">Initial Registration</span>
                                </div>
                            </div>

                            <!-- Expiration -->
                            @if($client->expiration)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-warning">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Package Expiration</h6>
                                    <p class="timeline-text text-muted small mb-1">
                                        {{ \Carbon\Carbon::parse($client->expiration)->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                                    </p>
                                    @if($client->is_expired)
                                    <span class="timeline-badge bg-danger">Expired</span>
                                    @elseif($client->is_expiring_soon)
                                    <span class="timeline-badge bg-warning">Expiring Soon</span>
                                    @else
                                    <span class="timeline-badge bg-success">Active</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Status Changes -->
                            <div class="timeline-item">
                                <div class="timeline-icon bg-info">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Current Status</h6>
                                    <p class="timeline-text text-muted small mb-1">
                                        {{ \Carbon\Carbon::parse($client->updated_at)->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                                    </p>
                                    <span class="timeline-badge bg-{{ $client->status == 'Active' ? 'success' : ($client->status == 'Expired' ? 'warning' : 'info') }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Closed -->
                            @if($client->closed_at)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-secondary">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Client Closed</h6>
                                    <p class="timeline-text text-muted small mb-1">
                                        {{ \Carbon\Carbon::parse($client->closed_at)->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                                    </p>
                                    <span class="timeline-badge bg-secondary">Closed for Cable Return</span>
                                </div>
                            </div>
                            @endif

                            <!-- ONU Return -->
                            @if($client->onu_returned && $client->onu_returned_at)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success">
                                    <i class="fas fa-undo"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">ONU Returned</h6>
                                    <p class="timeline-text text-muted small mb-1">
                                        {{ \Carbon\Carbon::parse($client->onu_returned_at)->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                                    </p>
                                    <span class="timeline-badge bg-success">Equipment Returned</span>
                                </div>
                            </div>
                            @endif

                            <!-- Cable Return -->
                            @if($client->cable_returned && $client->cable_returned_at)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success">
                                    <i class="fas fa-plug"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Cable Returned</h6>
                                    <p class="timeline-text text-muted small mb-1">
                                        {{ \Carbon\Carbon::parse($client->cable_returned_at)->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                                    </p>
                                    <span class="timeline-badge bg-success">Equipment Returned</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ===== COMMENTS TAB ===== -->
                <div class="tab-pane fade" id="comments" role="tabpanel">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="section-title-modern mb-0">
                                <i class="fas fa-comments me-2"></i>Client Notes & Comments
                            </h6>
                            <button type="button" class="btn btn-sm btn-cta-primary" onclick="openCommentModal({{ $client->id }}, '{{ addslashes($client->name) }}')">
                                <i class="fas fa-plus me-1"></i> Add Note
                            </button>
                        </div>

                        <div class="comment-feed-inline" id="commentFeedInline">
                            <div class="comment-loading-inline">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                Loading comments…
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SMS LOGS TAB ===== -->
                <div class="tab-pane fade" id="sms-logs" role="tabpanel">
                    <div class="p-4">
                        <!-- Top Header & Actions -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <h6 class="section-title-modern mb-1">
                                    <i class="fas fa-sms me-2"></i>SMS History & Delivery Reports
                                </h6>
                                <p class="text-muted small mb-0">Audit trail of all direct and bulk SMS messages sent to this client.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshClientSmsBtn" onclick="reloadClientSmsLogs()">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh
                                </button>
                                <button type="button" class="btn btn-sm btn-cta-primary" onclick="setSmsId({{ $client->id }})" data-bs-toggle="modal" data-bs-target="#smsModal">
                                    <i class="fas fa-paper-plane me-1"></i> Send SMS
                                </button>
                            </div>
                        </div>

                        <!-- Client SMS Metric Mini-Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center shadow-xs">
                                    <div>
                                        <div class="text-muted small fw-semibold">Total SMS Sent</div>
                                        <h4 class="mb-0 fw-bold text-dark mt-1" id="statClientSmsTotal">{{ $clientSmsStats['total'] ?? ($client->sms_logs_count ?? 0) }}</h4>
                                    </div>
                                    <div class="bg-primary-subtle text-primary p-2 rounded-circle">
                                        <i class="fas fa-paper-plane fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center shadow-xs">
                                    <div>
                                        <div class="text-muted small fw-semibold">Delivered / Sent</div>
                                        <h4 class="mb-0 fw-bold text-success mt-1" id="statClientSmsDelivered">{{ $clientSmsStats['delivered'] ?? 0 }}</h4>
                                    </div>
                                    <div class="bg-success-subtle text-success p-2 rounded-circle">
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center shadow-xs">
                                    <div>
                                        <div class="text-muted small fw-semibold">Failed</div>
                                        <h4 class="mb-0 fw-bold text-danger mt-1" id="statClientSmsFailed">{{ $clientSmsStats['failed'] ?? 0 }}</h4>
                                    </div>
                                    <div class="bg-danger-subtle text-danger p-2 rounded-circle">
                                        <i class="fas fa-times-circle fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Bar -->
                        <div class="row g-2 mb-3 align-items-center">
                            <div class="col-md-5 col-sm-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control border-start-0" id="smsSearchInput" placeholder="Search message text or phone..." onkeyup="debounceSmsSearch()">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <select class="form-select form-select-sm" id="smsStatusFilter" onchange="reloadClientSmsLogs()">
                                    <option value="all">All Statuses</option>
                                    <option value="1">Delivered / Sent</option>
                                    <option value="0">Failed</option>
                                </select>
                            </div>
                        </div>

                        <!-- SMS Logs Feed Container -->
                        <div id="clientSmsLogsContainer">
                            @if(isset($clientSmsLogs))
                                @include('clients.tabs.sms_logs_table', ['smsLogs' => $clientSmsLogs, 'client' => $client])
                            @else
                                <div class="text-center py-4">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    Loading SMS records...
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<style>
    /* ===== SUMMARY CARDS ===== */
    .summary-card {
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.04);
        background: #fff;
        transition: all 0.25s ease;
        overflow: hidden;
    }
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
    .summary-card-body {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 22px;
    }
    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .summary-content {
        flex: 1;
        min-width: 0;
    }
    .summary-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .summary-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
    }
    .summary-status-badge {
        font-size: 0.8rem;
        padding: 4px 12px;
    }

    /* ===== TAB INTERFACE ===== */
    .modern-card {
        border-radius: 12px;
        overflow: hidden;
    }
    .client-tabs .nav-link {
        border: none;
        border-radius: 0;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #6474b8;
        background: #f8fafc;
        transition: all 0.25s ease;
        border-bottom: 2px solid transparent;
    }
    .client-tabs .nav-link:hover {
        background: #f1f5f9;
        color: #334159;
        transform: translateY(-1px);
    }
    .client-tabs .nav-link.active {
        color: #2563eb;
        border-bottom: 2.5px solid #2563eb;
        background: #fff;
        box-shadow: 0 -1px 0 rgba(37,99,235,0.1);
    }
    .client-tabs .nav-link i {
        min-width: 18px;
    }
    .tab-content {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* ===== SECTION TITLES ===== */
    .section-title-modern {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6474b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
    }

    /* ===== DETAIL ITEMS ===== */
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .detail-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #94a3b8;
    }
    .detail-value {
        font-size: 0.95rem;
        color: #1e293b;
        font-weight: 500;
    }

    /* ===== TIMELINE ===== */
    .timeline {
        position: relative;
        padding-left: 36px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: repeating-linear-gradient(180deg, #cbd5e1, #cbd5e1 4px, transparent 4px, transparent 8px);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 24px;
        padding: 16px 20px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
        animation: slideInLeft 0.3s ease-out both;
    }
    .timeline-item:nth-child(1) { animation-delay: 0.05s; }
    .timeline-item:nth-child(2) { animation-delay: 0.1s; }
    .timeline-item:nth-child(3) { animation-delay: 0.15s; }
    .timeline-item:nth-child(4) { animation-delay: 0.2s; }
    .timeline-item:nth-child(5) { animation-delay: 0.25s; }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-16px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .timeline-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }
    .timeline-icon {
        position: absolute;
        left: -52px;
        top: 18px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.75rem;
        z-index: 1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }
    .timeline-content h6 {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px;
    }
    .timeline-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #fff;
    }

    /* ===== INLINE COMMENT FEED ===== */
    .comment-feed-inline {
        background: #f8fafc;
        border-radius: 10px;
        padding: 18px 20px;
        min-height: 200px;
        max-height: 400px;
        overflow-y: auto;
    }
    .comment-loading-inline {
        display: flex;
        align-items: center;
        color: #94a3b8;
        font-size: 0.9rem;
        justify-content: center;
        padding: 30px 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .client-tabs .nav-link { padding: 12px 14px; font-size: 0.8rem; }
        .timeline { padding-left: 24px; }
        .timeline-icon { left: -40px; }
        .detail-value { font-size: 0.88rem; }
        .summary-value { font-size: 1.05rem; }
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Load comments in the inline Comments tab when it's shown
        $('#comments-tab').on('click', function() {
            loadInlineComments();
        });

        // Also load if comments tab is the default active tab (it isn't, but safety)
        if ($('#comments').hasClass('show') && $('#comments').hasClass('active')) {
            loadInlineComments();
        }
    });

    function loadInlineComments() {
        const feed = $('#commentFeedInline');
        feed.html('<div class="comment-loading-inline"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Loading comments…</div>');
        $.ajax({
            url: `/clients/${_commentClientId}/comments`,
            type: 'GET',
            success: function(res) {
                renderInlineComments(res.comments);
            },
            error: function() {
                feed.html('<div class="comment-loading-inline text-danger"><i class="fas fa-exclamation-circle me-2"></i>Failed to load comments</div>');
            }
        });
    }

    function renderInlineComments(comments) {
        const feed = $('#commentFeedInline');
        const typeMeta = {
            note:    { label: 'Note',  cls: 'badge-note',    icon: 'fa-sticky-note', gradient: 'linear-gradient(135deg,#6366f1,#4f46e5)' },
            info:    { label: 'Info',  cls: 'badge-info',    icon: 'fa-info-circle', gradient: 'linear-gradient(135deg,#0ea5e9,#0369a1)' },
            success: { label: 'Done',  cls: 'badge-success', icon: 'fa-check-circle', gradient: 'linear-gradient(135deg,#10b981,#047857)' },
            alert:   { label: 'Alert', cls: 'badge-alert',   icon: 'fa-exclamation-triangle', gradient: 'linear-gradient(135deg,#ef4444,#b91c1c)' },
        };

        if (!comments || comments.length === 0) {
            feed.html('<div class="comment-loading-inline"><i class="fas fa-comments me-2"></i>No notes yet. Click "Add Note" to get started.</div>');
            return;
        }

        let html = '<div class="comment-feed-list">';
        comments.forEach(function(c) {
            const meta = typeMeta[c.type] || typeMeta.note;
            const grad = _avatarGrad(c.author_name || '');
            const time = c.time_ago || '';
            const canDelete = c.is_mine ? `<button class="comment-delete-btn-inline" onclick="deleteComment(${c.id})"><i class="fas fa-trash-alt"></i></button>` : '';
            html += `<div class="comment-card-inline">
                <div class="comment-avatar-inline" style="background:${grad}">${c.author_initials || '?'}</div>
                <div class="comment-bubble-inline">
                    <div class="comment-bubble-top-inline">
                        <span class="comment-author-inline">${_esc(c.author_name || 'System')}</span>
                        <span class="comment-type-badge ${meta.cls}" style="background:${meta.gradient};color:#fff;"><i class="fas ${meta.icon}"></i> ${meta.label}</span>
                        <span class="comment-time-inline" title="${c.created_at || ''}">${_esc(time)}</span>
                    </div>
                    <p class="comment-body-text-inline">${_esc(c.body || '')}</p>
                    ${canDelete}
                </div>
            </div>`;
        });
        html += '</div>';
        feed.html(html);
    }

    // ===== SMS LOGS MANAGEMENT =====
    let smsSearchTimeout = null;

    function debounceSmsSearch() {
        clearTimeout(smsSearchTimeout);
        smsSearchTimeout = setTimeout(function() {
            reloadClientSmsLogs(1);
        }, 350);
    }

    function reloadClientSmsLogs(page = 1) {
        const container = $('#clientSmsLogsContainer');
        const refreshBtn = $('#refreshClientSmsBtn');
        const status = $('#smsStatusFilter').val() || 'all';
        const search = $('#smsSearchInput').val() || '';

        refreshBtn.find('i').addClass('fa-spin');
        container.css('opacity', '0.6');

        $.ajax({
            url: `{{ url('clients/' . $client->id . '/sms-logs') }}`,
            type: 'GET',
            data: {
                sms_page: page,
                status: status,
                search: search
            },
            success: function(res) {
                refreshBtn.find('i').removeClass('fa-spin');
                container.css('opacity', '1');

                if (res && res.html !== undefined) {
                    container.html(res.html);
                    if (res.stats) {
                        $('#statClientSmsTotal').text(res.stats.total || 0);
                        $('#statClientSmsDelivered').text(res.stats.delivered || 0);
                        $('#statClientSmsFailed').text(res.stats.failed || 0);
                        $('#clientSmsBadgeCount').text(res.stats.total || 0);
                    }
                }
            },
            error: function() {
                refreshBtn.find('i').removeClass('fa-spin');
                container.css('opacity', '1');
                container.html('<div class="alert alert-danger my-3"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load SMS logs. Please try again.</div>');
            }
        });
    }

    // Intercept pagination clicks inside SMS log table
    $(document).on('click', '#clientSmsLogsContainer .sms-pagination-links a', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        if (!href) return;
        const urlParams = new URLSearchParams(href.split('?')[1]);
        const page = urlParams.get('sms_page') || 1;
        reloadClientSmsLogs(page);
    });
</script>

@endpush

<style>
    .comment-feed-list { display: flex; flex-direction: column; gap: 14px; }
    .comment-card-inline {
        display: flex;
        gap: 10px;
        animation: commentSlideIn 0.3s ease;
    }
    @keyframes commentSlideIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
    .comment-avatar-inline {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.68rem;
        color: #fff;
        flex-shrink: 0;
        text-transform: uppercase;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .comment-bubble-inline {
        flex: 1;
        background: #fff;
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .comment-bubble-top-inline {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    .comment-author-inline { font-weight:700; font-size:0.8rem; color:#1e3a5f; }
    .comment-type-badge { font-size:0.62rem; font-weight:700; padding:2px 8px; border-radius:20px; text-transform:uppercase; letter-spacing:0.3px; color:#fff; }
    .comment-time-inline { font-size:0.68rem; color:#94a3b8; margin-left:auto; }
    .comment-body-text-inline { font-size:0.85rem; color:#334155; line-height:1.5; white-space:pre-wrap; word-break:break-word; margin:0; }
    .comment-delete-btn-inline {
        position: absolute;
        top: 8px;
        right: 8px;
        background: none;
        border: none;
        color: #ef4444;
        font-size: 0.7rem;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        opacity: 0.5;
        transition: all 0.2s;
    }
    .comment-delete-btn-inline:hover { opacity: 1; background: #fee2e2; }
</style>
