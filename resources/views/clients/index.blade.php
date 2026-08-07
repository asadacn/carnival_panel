@extends('layouts.app')
@section('title')
    @lang('models/clients.plural')
@endsection
@section('content')
    <section class="section">
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="dashboard-section">
            <!-- All Stats in one compact row -->
            <div class="row mb-2">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                    <div class="card card-statistic-1 shadow-sm mini-stat-card" data-toggle="tooltip" title="Total number of active clients">
                        <div class="card-icon bg-primary mini-icon"><i class="fas fa-user-check"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Active Clients</h4></div>
                            <div class="card-body"><span class="mini-stat-number">{{ $ActiveClientsCount }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                    <div class="card card-statistic-1 shadow-sm mini-stat-card" data-toggle="tooltip" title="Clients with expired subscriptions">
                        <div class="card-icon bg-danger mini-icon"><i class="fas fa-user-clock"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Expired Clients</h4></div>
                            <div class="card-body"><span class="mini-stat-number">{{ $expiredClientsCount }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                    <div class="card card-statistic-1 shadow-sm mini-stat-card" data-toggle="tooltip" title="Clients with free ONU equipment">
                        <div class="card-icon bg-info mini-icon"><i class="fas fa-gift"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Free ONU</h4></div>
                            <div class="card-body"><span class="mini-stat-number">{{ $freeOnuClientsCount }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                    <div class="card card-statistic-1 shadow-sm mini-stat-card" data-toggle="tooltip" title="Clients with returned cables">
                        <div class="card-icon bg-success mini-icon"><i class="fas fa-plug"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Cable Returned</h4></div>
                            <div class="card-body"><span class="mini-stat-number">{{ $cableReturnedClientsCount }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                    <div class="card card-statistic-1 shadow-sm mini-stat-card" data-toggle="tooltip" title="Total outstanding bills amount">
                        <div class="card-icon bg-warning mini-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Total Due</h4></div>
                            <div class="card-body"><span class="mini-stat-number" style="font-size:0.95rem;">৳{{ number_format($totalDueAmount, 0) }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                    <div class="card card-statistic-1 shadow-sm mini-stat-card" data-toggle="tooltip" title="Number of unpaid bills">
                        <div class="card-icon bg-danger mini-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Unpaid Bills</h4></div>
                            <div class="card-body"><span class="mini-stat-number">{{ $unpaidBillsCount }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ISP-wise Active Clients Section -->
            @if($ispWiseActiveClients->count() > 0)
            @php
                $totalActive = $ispWiseActiveClients->sum();
                $ispConfig = [
                    'carnival' => ['icon' => 'fa-router', 'gradientStart' => '#1e3a8a', 'gradientEnd' => '#3b82f6', 'accentColor' => '#175ddc', 'lightBg' => '#eff6ff'],
                    'bijoy'    => ['icon' => 'fa-broadcast-tower', 'gradientStart' => '#0891b2', 'gradientEnd' => '#06b6d4', 'accentColor' => '#0dcaf0', 'lightBg' => '#ecf9ff'],
                ];
            @endphp
            <div class="row mb-2">
                <div class="col-12 mb-1">
                    <small class="text-muted fw-semibold" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="fas fa-globe me-1"></i> ISP Network Analytics
                    </small>
                </div>
                @foreach($ispWiseActiveClients as $ispCode => $count)
                @php
                    $percentage = $totalActive > 0 ? round(($count / $totalActive) * 100, 1) : 0;
                    $config = $ispConfig[strtolower($ispCode)] ?? ['icon' => 'fa-network-wired', 'gradientStart' => '#6366f1', 'gradientEnd' => '#8b5cf6', 'accentColor' => '#7c3aed', 'lightBg' => '#f3e8ff'];
                @endphp
                <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                    <div class="card mini-stat-card shadow-sm" style="border-top: 3px solid {{ $config['accentColor'] }};">
                        <div class="card-body" style="padding:8px 10px !important;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div style="width:28px;height:28px;border-radius:6px;background:linear-gradient(135deg,{{ $config['gradientStart'] }},{{ $config['gradientEnd'] }});display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;">
                                    <i class="fas {{ $config['icon'] }}"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.7rem;font-weight:700;color:#1a202c;line-height:1;">{{ ucfirst($ispCode) }}</div>
                                    <div style="font-size:0.65rem;color:#94a3b8;">Internet Provider</div>
                                </div>
                                <span class="ms-auto" style="font-size:0.7rem;font-weight:700;color:{{ $config['accentColor'] }};">{{ $percentage }}%</span>
                            </div>
                            <div style="font-size:1.3rem;font-weight:800;color:#1a202c;line-height:1;">{{ $count }}</div>
                            <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.3px;">Active Clients</div>
                            <div style="height:4px;border-radius:2px;background:{{ $config['lightBg'] }};margin-top:6px;">
                                <div class="progress-fill" style="height:100%;border-radius:2px;width:0%;background:linear-gradient(90deg,{{ $config['gradientStart'] }},{{ $config['gradientEnd'] }});transition:width 0.8s ease;" data-width="{{ $percentage }}"></div>
                            </div>
                            <div class="d-flex justify-content-end gap-1 mt-1">
                                <button class="filter-by-isp btn btn-xs" data-isp="{{ $ispCode }}" style="font-size:0.65rem;padding:2px 6px;background:#f5f7fa;border:none;border-radius:4px;color:#64748b;">
                                    <i class="fas fa-sliders-h"></i> Filter
                                </button>
                                <button class="details-btn btn btn-xs" data-isp="{{ $ispCode }}" style="font-size:0.65rem;padding:2px 6px;background:#f5f7fa;border:none;border-radius:4px;color:#64748b;">
                                    <i class="fas fa-arrow-right"></i> Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div><!-- End of dashboard-section -->

        <style>
            /* ========== MINI STAT CARDS ========== */
            .mini-stat-card {
                border-radius: 8px;
                border: 1px solid rgba(0,0,0,0.06);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .mini-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            }
            .mini-stat-card .mini-icon {
                width: 40px !important;
                height: 40px !important;
                min-height: unset !important;
                font-size: 1rem !important;
                border-radius: 6px !important;
            }
            .mini-stat-card .card-header h4 {
                font-size: 0.72rem !important;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                color: #6c757d;
            }
            .mini-stat-number {
                font-size: 1.4rem;
                font-weight: 700;
                color: #2c3e50;
                line-height: 1.1;
            }
            .mini-stat-card .card-body {
                padding: 4px 8px 8px !important;
            }
            .mini-stat-card .card-header {
                padding: 8px 8px 2px !important;
            }
            .dashboard-section {
                margin-bottom: 8px;
            }

            .dashboard-section.visible {
                animation: fadeInDown 0.35s ease;
            }

            @keyframes fadeInDown {
                from { opacity: 0; transform: translateY(-12px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            #privacy-toggle-btn {
                transition: all 0.3s ease;
            }

            #privacy-toggle-btn:hover {
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            /* ========== MODERN ISP CARDS STYLES ========== */

            /* Modern Section Header */
            .modern-section-header {
                background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
                border-radius: 12px;
                padding: 32px 28px;
                border-left: 5px solid #175ddc;
            }

            .header-content {
                display: flex;
                align-items: center;
                gap: 24px;
            }

            .header-icon {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #175ddc 0%, #3b82f6 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                color: white;
                box-shadow: 0 4px 12px rgba(23, 93, 220, 0.15);
            }

            .header-text h3.header-title {
                margin: 0;
                font-size: 28px;
                font-weight: 700;
                color: #1a202c;
                letter-spacing: -0.5px;
            }

            .header-text p.header-subtitle {
                margin: 8px 0 0 0;
                color: #64748b;
                font-size: 14px;
                font-weight: 500;
            }

            /* ISP Cards Grid */
            .isp-cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 24px;
            }

            /* Modern ISP Card */
            .modern-isp-card {
                background: white;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
                border: 1px solid #e5e7eb;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }

            .modern-isp-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #175ddc 0%, #3b82f6 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .modern-isp-card:hover::before {
                opacity: 1;
            }

            .modern-isp-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
                border-color: #d1d5db;
            }

            .card-content {
                padding: 28px;
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            /* Card Header Modern */
            .card-header-modern {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                margin-bottom: 24px;
                padding-bottom: 20px;
                border-bottom: 1px solid #f0f2f5;
            }

            .icon-wrapper {
                width: 56px;
                height: 56px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 24px;
                flex-shrink: 0;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .header-info {
                flex: 1;
            }

            .isp-name {
                margin: 0;
                font-size: 18px;
                font-weight: 700;
                color: #1a202c;
            }

            .isp-subtitle {
                margin: 4px 0 0 0;
                color: #94a3b8;
                font-size: 12px;
                font-weight: 500;
            }

            .percentage-badge {
                font-size: 16px;
                font-weight: 700;
                padding: 8px 12px;
                border-radius: 8px;
                flex-shrink: 0;
                transition: transform 0.2s ease;
            }

            .modern-isp-card:hover .percentage-badge {
                transform: scale(1.08);
            }

            /* Stats Section */
            .card-stats-section {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .main-stat {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .stat-number {
                font-size: 40px;
                font-weight: 800;
                color: #1a202c;
                line-height: 1;
            }

            .stat-label {
                font-size: 12px;
                font-weight: 600;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* Modern Progress Bar */
            .chart-section {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .modern-progress-bar {
                width: 100%;
            }

            .progress-track {
                width: 100%;
                height: 8px;
                border-radius: 4px;
                overflow: hidden;
            }

            .progress-fill {
                height: 100%;
                border-radius: 4px;
                box-shadow: 0 0 8px rgba(23, 93, 220, 0.2);
            }

            .progress-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 12px;
            }

            .progress-label {
                color: #94a3b8;
                font-weight: 600;
            }

            .progress-value {
                color: #1a202c;
                font-weight: 700;
            }

            /* Card Footer Modern */
            .card-footer-modern {
                display: flex;
                gap: 12px;
                margin-top: auto;
                padding-top: 20px;
                border-top: 1px solid #f0f2f5;
            }

            .action-btn {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 14px;
                border: none;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                background: #f5f7fa;
                color: #64748b;
            }

            .action-btn i {
                font-size: 14px;
            }

            .action-btn:hover {
                background: #e8eef5;
                color: #175ddc;
                transform: translateX(2px);
            }

            .filter-btn:hover {
                background: linear-gradient(135deg, #175ddc 0%, #3b82f6 100%);
                color: white;
            }

            .details-btn:hover {
                background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
                color: white;
            }

            /* Responsive Grid */
            @media (max-width: 768px) {
                .isp-cards-grid {
                    grid-template-columns: 1fr;
                }

                .modern-section-header {
                    padding: 24px;
                }

                .header-content {
                    flex-direction: column;
                    text-align: center;
                }

                .header-icon {
                    margin: 0 auto;
                }
            }

            /* ========== ORIGINAL STYLES ========== */

            .transition-card {
                transition: all 0.3s ease;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }

            .transition-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
                border-color: rgba(0, 0, 0, 0.1);
            }

            .display-stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: #2c3e50;
            }

            .section-title {
                padding-bottom: 1rem;
                border-bottom: 2px solid #f0f2f5;
            }

            /* ========== TOOLBAR STYLES ========== */
            .clients-toolbar {
                display: flex !important;
                gap: 10px !important;
                flex-wrap: wrap !important;
                align-items: center !important;
                padding: 12px 0;
                border-bottom: 1px solid #e9ecef;
                animation: slideDown 0.3s ease-out;
            }

            .clients-toolbar .btn-sm {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
                white-space: nowrap;
                transition: all 0.2s ease;
            }

            .clients-toolbar .btn-sm:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .clients-toolbar .btn-group .btn-primary {
                background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
                border-color: #0d47a1;
            }

            .clients-toolbar .btn-group .btn-primary:hover {
                background: linear-gradient(135deg, #0d47a1 0%, #1a73e8 100%);
                border-color: #0d47a1;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive toolbar adjustments */
            @media (max-width: 576px) {
                .clients-toolbar {
                    gap: 8px !important;
                    padding: 10px 0;
                }

                .clients-toolbar .btn-sm {
                    font-size: 0.8rem;
                    padding: 0.25rem 0.5rem;
                }

                .clients-toolbar .btn-sm i {
                    margin-right: 0.25rem;
                }

                .clients-toolbar .btn-sm span {
                    display: none;
                }

                .clients-toolbar .btn-group .dropdown-menu {
                    right: 0 !important;
                    left: auto !important;
                }
            }
            /* ===== LATEST COMMENT INLINE CELL ===== */
            .lc-cell {
                display: flex;
                align-items: flex-start;
                gap: 7px;
                cursor: pointer;
                padding: 4px 2px;
                border-radius: 8px;
                transition: background 0.15s;
                max-width: 220px;
            }
            .lc-cell:hover {
                background: #f0f4ff;
            }
            .lc-badge {
                width: 24px;
                height: 24px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                flex-shrink: 0;
                margin-top: 1px;
            }
            .lc-content {
                display: flex;
                flex-direction: column;
                gap: 1px;
                min-width: 0;
            }
            .lc-body {
                font-size: 0.78rem;
                color: #1e293b;
                font-weight: 500;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 180px;
                display: block;
            }
            .lc-meta {
                font-size: 0.68rem;
                color: #94a3b8;
                white-space: nowrap;
            }

        </style>

        <div class="section-body" id="clients-header">
            <div class="card">
                <div class="card-body">
                    <!-- Compact Toolbar -->
                    <div class="clients-toolbar mb-3" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">


                        <a href="#" id="bulk_btn" style="display: none" data-bs-toggle="modal" data-bs-target="#smsModal"
                           class="btn btn-sm btn-warning">Bulk SMS <i class="fas fa-envelope"></i> <span id="bulk_count"
                            class="badge badge-success p-1"></span> </a>

                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#quickBillModal" id="quick-bill-btn" style="display: none;" title="Create quick due bill for current month">
                            <i class="fas fa-receipt"></i> Quick Bill <span id="quick_bill_count" class="badge badge-light p-1"></span>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cogs"></i> Tools
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('clients.export') }}"><i class="fas fa-file-export me-2"></i> @lang('crud.export')</a></li>
                                <li><a class="dropdown-item" href="{{ route('clients.import.create') }}"><i class="fas fa-file-import me-2"></i> @lang('crud.import')</a></li>
                            </ul>
                        </div>

                        <a href="{{ route('clients.create') }}" class="btn btn-sm btn-success">@lang('crud.add_new')<i class="fas fa-plus"></i></a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="clients">
                            <thead>
                                <tr>
                                    <th></th> {{-- Checkbox --}}
                                    <th>#</th>
                                    <th>@lang('models/clients.fields.username')</th>
                                    <th>@lang('models/clients.fields.name')</th>
                                    <th>@lang('models/clients.fields.contact')</th>
                                    <th>@lang('models/clients.fields.address')</th>
                                    <th>@lang('models/clients.fields.package')</th>
                                    <th>@lang('models/clients.fields.expiration')</th>
                                    <th>Cable</th>
                                    <th>ONU</th>
                                    <th>Comment</th>
                                    <th>ISP</th>
                                    <th>@lang('models/clients.fields.status')</th>
                                    <th>Total Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables will populate this tbody --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="smsModalLabel">Client SMS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <select class="form-select mb-3 border border-secondary" id="template-select">
                        <option value="">Select From Template</option>
                        @foreach ($templates as $template)
                            <option value="{{ $template->sms_template }}">{{ $template->title }}</option>
                        @endforeach
                    </select>

                    <form id="sms_form" action="">
                        <input id="client_id" type="hidden" name="client_id">
                        <label for="sms-body">Write Message
                            (<small id="sms-counter">
                                <span>Messages: <span class="messages"></span></span> /
                                <span>Remaining: <span class="remaining"></span></span>
                            </small>)
                        </label>
                        <textarea name="sms-body" id="sms-body" style="min-height: 140px;" class="form-control border border-success"
                            placeholder="Write your message here .."></textarea>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" onclick="resetText()" class="btn btn-warning">Reset</button>
                    <button type="button" onclick="sendSMS()" class="btn btn-success">Send</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <h5 id="qrModalLabel" class="mb-3">Client QR</h5>

                <div id="qrcode" class="mx-auto mb-3"></div>

                <p id="clientName" class="fw-bold mb-3"></p>

                <a href="#" id="whatsappBtn" class="btn btn-success w-100">
                    <i class="fab fa-whatsapp"></i> Send WhatsApp
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verifyPasswordModal" tabindex="-1" aria-labelledby="verifyPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyPasswordModalLabel">Verify Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to confirm the **deletion** of this client.</p>
                    <form id="verify_password_form">
                        <input type="hidden" id="client_to_delete_id">
                        <div class="mb-3">
                            <label for="verification_password" class="form-label">Your Password</label>
                            <input type="password" class="form-control" id="verification_password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteWithPassword()">Confirm Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Bill Modal -->
    <div class="modal fade" id="quickBillModal" tabindex="-1" aria-labelledby="quickBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content quick-bill-modal">
                <div class="quick-bill-header">
                    <div class="quick-bill-title-section">
                        <h5 class="quick-bill-title" id="quickBillModalLabel">
                            <i class="fas fa-receipt"></i> Quick Due Bill
                        </h5>
                        <p class="quick-bill-subtitle">{{ now()->format('F Y') }} - Current Month</p>
                    </div>
                    <button type="button" class="btn-close-smooth" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="quick-bill-body">
                    <form id="quick_bill_form">
                        <input type="hidden" id="quick_client_id">

                        <!-- Client Information Card -->
                        <div class="quick-bill-info-card">
                            <div class="info-item">
                                <span class="info-icon"><i class="fas fa-user"></i></span>
                                <div class="info-content">
                                    <label class="info-label">Client Name</label>
                                    <p id="quick_client_name" class="info-value"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Month/Year Display -->
                        <div class="month-year-display">
                            <span class="month-label"><i class="fas fa-calendar"></i> Billing Period:</span>
                            <span class="month-value" id="quick_month_year">{{ now()->format('F Y') }}</span>
                        </div>

                        <!-- Amount Input -->
                        <div class="quick-bill-form-group">
                            <label for="quick_amount" class="quick-bill-form-label">Amount (৳) <span class="required-star">*</span></label>
                            <input type="number" id="quick_amount" class="quick-bill-form-control" placeholder="Enter billing amount" step="0.01" min="0.01" required>
                            <small class="form-helper-text">e.g., 2500.00</small>
                        </div>

                        <!-- Notes Input -->
                        <div class="quick-bill-form-group">
                            <label for="quick_notes" class="quick-bill-form-label">Notes <span class="optional-label">(Optional)</span></label>
                            <textarea id="quick_notes" class="quick-bill-form-control" rows="2" placeholder="Add any additional notes..."></textarea>
                        </div>

                        <!-- Info Alert -->
                        <div class="quick-bill-alert">
                            <div class="alert-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                <strong>Bill Details:</strong> This bill will be created for <strong>{{ now()->format('F Y') }}</strong> with today's date as the bill date.
                            </div>
                        </div>
                    </form>
                </div>

                <div class="quick-bill-footer">
                    <button type="button" class="quick-bill-btn quick-bill-btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="quick-bill-btn quick-bill-btn-create" onclick="createQuickBill()">
                        <i class="fas fa-check-circle"></i> Create Bill
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ===== QUICK BILL MODAL SMOOTH STYLING ===== */
        .quick-bill-modal {
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .quick-bill-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f1f3 100%);
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .quick-bill-title-section {
            flex: 1;
        }

        .quick-bill-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-bill-subtitle {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0.5rem 0 0 0;
        }

        .btn-close-smooth {
            background: none;
            border: none;
            font-size: 1rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.25rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-close-smooth:hover {
            color: #1f2937;
            transform: rotate(90deg);
        }

        .quick-bill-body {
            padding: 1.75rem;
            background: white;
        }

        .quick-bill-info-card {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #d1d5db;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #3b82f6;
            color: white;
            border-radius: 8px;
            font-size: 1rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            display: block;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0.25rem 0 0 0;
        }

        .month-year-display {
            background-color: #f9fafb;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .month-label {
            font-weight: 500;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .month-value {
            font-weight: 700;
            color: #3b82f6;
            font-size: 1.05rem;
        }

        .quick-bill-form-group {
            margin-bottom: 1.25rem;
        }

        .quick-bill-form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .required-star {
            color: #ef4444;
        }

        .optional-label {
            font-size: 0.8rem;
            font-weight: 400;
            color: #9ca3af;
        }

        .quick-bill-form-control {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.7rem 0.875rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #fafbfc;
            font-family: inherit;
        }

        .quick-bill-form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .quick-bill-form-control::placeholder {
            color: #9ca3af;
        }

        .form-helper-text {
            display: block;
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 0.35rem;
        }

        .quick-bill-alert {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border: 1px solid #93c5fd;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
        }

        .alert-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            color: #1e40af;
            font-size: 1.1rem;
            margin-top: 0.1rem;
        }

        .alert-content {
            flex: 1;
            font-size: 0.9rem;
            color: #1e40af;
            line-height: 1.5;
        }

        .quick-bill-footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.75rem;
            display: flex;
            gap: 0.875rem;
            justify-content: flex-end;
        }

        .quick-bill-btn {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .quick-bill-btn-cancel {
            background-color: #e5e7eb;
            color: #374151;
        }

        .quick-bill-btn-cancel:hover {
            background-color: #d1d5db;
            transform: translateY(-2px);
        }

        .quick-bill-btn-create {
            background-color: #10b981;
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .quick-bill-btn-create:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .quick-bill-btn:active {
            transform: translateY(0);
        }

        /* Modal backdrop smooth effect */
        .modal-backdrop.fade {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .modal-backdrop.fade.show {
            opacity: 0.5;
        }

        @media (max-width: 576px) {
            .quick-bill-modal {
                border-radius: 8px;
            }

            .quick-bill-header {
                padding: 1.25rem;
            }

            .quick-bill-body {
                padding: 1.25rem;
            }

            .quick-bill-footer {
                padding: 1rem 1.25rem;
                flex-direction: column;
            }

            .quick-bill-btn {
                width: 100%;
                justify-content: center;
            }

            .month-year-display {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-icon {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }
        }
    </style>

    <!-- ============================================================
         SOCIAL COMMENT MODAL
    ============================================================= -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered comment-modal-dialog">
            <div class="modal-content comment-modal-content">

                <!-- Header -->
                <div class="comment-modal-header">
                    <div class="comment-modal-header-inner">
                        <div class="comment-modal-avatar-wrap">
                            <span class="comment-modal-icon"><i class="fas fa-comments"></i></span>
                        </div>
                        <div>
                            <h6 class="comment-modal-title" id="commentModalLabel">Client Notes</h6>
                            <p class="comment-modal-subtitle" id="commentModalClientName">Loading…</p>
                        </div>
                    </div>
                    <button type="button" class="comment-modal-close" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Feed -->
                <div class="comment-feed-wrap" id="commentFeed">
                    <!-- comments injected here -->
                    <div class="comment-loading" id="commentLoading">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        Loading comments…
                    </div>
                </div>

                <!-- Compose -->
                <div class="comment-compose-wrap">
                    <!-- Type picker -->
                    <div class="comment-type-row" id="commentTypeRow">
                        <button type="button" class="ctype-btn active" data-type="note" title="Note">
                            <i class="fas fa-sticky-note"></i> Note
                        </button>
                        <button type="button" class="ctype-btn" data-type="info" title="Info">
                            <i class="fas fa-info-circle"></i> Info
                        </button>
                        <button type="button" class="ctype-btn" data-type="success" title="Done">
                            <i class="fas fa-check-circle"></i> Done
                        </button>
                        <button type="button" class="ctype-btn" data-type="alert" title="Alert">
                            <i class="fas fa-exclamation-triangle"></i> Alert
                        </button>
                    </div>

                    <div class="comment-input-row">
                        <div class="comment-self-avatar" id="commentSelfAvatar">ME</div>
                        <div class="comment-input-wrap">
                            <textarea id="commentBody"
                                      class="comment-textarea"
                                      placeholder="Write a note, update or alert…"
                                      rows="2"
                                      maxlength="1000"></textarea>
                            <div class="comment-input-footer">
                                <span class="comment-char-count" id="commentCharCount">0 / 1000</span>
                                <button type="button" class="comment-send-btn" id="commentSendBtn"
                                        onclick="submitComment()">
                                    <i class="fas fa-paper-plane"></i> Post
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
    /* ===== COMMENT MODAL ===== */
    .comment-modal-dialog {
        max-width: 560px;
    }
    .comment-modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }
    /* Header */
    .comment-modal-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .comment-modal-header-inner {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .comment-modal-icon {
        width: 42px;
        height: 42px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        backdrop-filter: blur(6px);
    }
    .comment-modal-title {
        margin: 0;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .comment-modal-subtitle {
        margin: 3px 0 0;
        color: rgba(255,255,255,0.75);
        font-size: 0.8rem;
    }
    .comment-modal-close {
        background: rgba(255,255,255,0.12);
        border: none;
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .comment-modal-close:hover {
        background: rgba(255,255,255,0.25);
        transform: rotate(90deg);
    }
    /* Feed */
    .comment-feed-wrap {
        flex: 1;
        overflow-y: auto;
        padding: 18px 20px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 14px;
        min-height: 200px;
        max-height: 380px;
    }
    .comment-loading {
        display: flex;
        align-items: center;
        color: #94a3b8;
        font-size: 0.9rem;
        justify-content: center;
        padding: 30px 0;
    }
    .comment-empty {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .comment-empty i {
        font-size: 2.5rem;
        color: #cbd5e1;
        display: block;
        margin-bottom: 10px;
    }
    .comment-empty p { margin: 0; font-size: 0.9rem; }
    /* Individual comment card */
    .comment-card {
        display: flex;
        gap: 10px;
        animation: commentSlideIn 0.3s ease;
    }
    @keyframes commentSlideIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .comment-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        color: #fff;
        flex-shrink: 0;
        text-transform: uppercase;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        box-shadow: 0 2px 8px rgba(59,130,246,0.25);
    }
    .comment-bubble {
        flex: 1;
        background: #fff;
        border-radius: 14px 14px 14px 4px;
        padding: 12px 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .comment-bubble.mine {
        border-radius: 14px 14px 4px 14px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #bfdbfe;
    }
    .comment-bubble-top {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    .comment-author {
        font-weight: 700;
        font-size: 0.82rem;
        color: #1e3a5f;
    }
    .comment-type-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .badge-note    { background: #e0e7ff; color: #3730a3; }
    .badge-info    { background: #dbeafe; color: #1d4ed8; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-alert   { background: #fee2e2; color: #991b1b; }
    .comment-time {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-left: auto;
    }
    .comment-body-text {
        font-size: 0.88rem;
        color: #334155;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
        margin: 0;
    }
    .comment-bubble-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
        gap: 6px;
    }
    .comment-delete-btn {
        background: none;
        border: none;
        color: #ef4444;
        font-size: 0.72rem;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 6px;
        transition: all 0.2s;
        opacity: 0.6;
    }
    .comment-delete-btn:hover {
        opacity: 1;
        background: #fee2e2;
    }
    /* Compose area */
    .comment-compose-wrap {
        padding: 14px 20px 18px;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .comment-type-row {
        display: flex;
        gap: 6px;
        margin-bottom: 12px;
    }
    .ctype-btn {
        flex: 1;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 5px 4px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        white-space: nowrap;
    }
    .ctype-btn.active, .ctype-btn:hover {
        border-color: #2563eb;
        background: #dbeafe;
        color: #1d4ed8;
    }
    .ctype-btn[data-type="success"].active, .ctype-btn[data-type="success"]:hover {
        border-color: #10b981; background: #d1fae5; color: #065f46;
    }
    .ctype-btn[data-type="alert"].active, .ctype-btn[data-type="alert"]:hover {
        border-color: #ef4444; background: #fee2e2; color: #991b1b;
    }
    .ctype-btn[data-type="info"].active, .ctype-btn[data-type="info"]:hover {
        border-color: #0ea5e9; background: #e0f2fe; color: #0369a1;
    }
    .comment-input-row {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .comment-self-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        text-transform: uppercase;
        box-shadow: 0 2px 6px rgba(99,102,241,0.3);
    }
    .comment-input-wrap {
        flex: 1;
        background: #f1f5f9;
        border-radius: 14px;
        border: 1.5px solid #e2e8f0;
        transition: border-color 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .comment-input-wrap:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        background: #fff;
    }
    .comment-textarea {
        width: 100%;
        border: none;
        background: transparent;
        padding: 10px 14px 6px;
        font-size: 0.88rem;
        color: #1e293b;
        resize: none;
        outline: none;
        font-family: inherit;
        line-height: 1.5;
    }
    .comment-textarea::placeholder { color: #94a3b8; }
    .comment-input-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 10px 8px 14px;
    }
    .comment-char-count {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    .comment-send-btn {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .comment-send-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(37,99,235,0.35);
    }
    .comment-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    @media (max-width: 576px) {
        .comment-type-row { flex-wrap: wrap; }
        .ctype-btn { flex: 1 1 40%; }
    }
    </style>

@endsection


@section('scripts')
    <script src="{{ asset('js/sms_counter.min.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        // ========== SCROLL TO CLIENTS & FOCUS SEARCH ON LOAD ==========
        $(document).ready(function() {
            setTimeout(function() {
                var clientsTop = $('#clients-header').offset().top;
                $(window).scrollTop(clientsTop - 200);
                // Focus the DataTables search input so user is ready to search
                $('#clients_filter input').focus();
            }, 800); // wait for DataTables to fully render
        });

        // ------------------ DATA TABLES SETUP ------------------
        $(document).ready(function() {
            // CSRF Setup for all AJAX calls
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const table = $('#clients').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true, // CRITICAL: Server-side processing for performance
                responsive: true,
                autoWidth: false,
                searching: true,
                select: true,
                ajax: "{{ route('clients.index') }}",
                columns: [
                    { data: null, defaultContent: '', orderable: false, searchable: false }, // Checkbox
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                   {
                        data: 'username',
                        name: 'username',
                        createdCell: function (td, cellData, rowData) {
                            let url = '';

                            // ISP অনুযায়ী লিংকের কন্ডিশন
                            if (rowData.isp_code === 'bijoy') {
                                url = `https://selfcare.bijoy.net/pay/${rowData.username}`;
                            } else {
                                // ডিফল্ট অথবা Carnival-এর জন্য আগের লিংক
                                url = `https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid=${rowData.username}`;
                            }

                            $(td).html(`
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <a href="${url}" target="_blank">${cellData}</a>
                                    <i class="far fa-copy text-secondary copy-id-btn" style="cursor: pointer; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7" onclick="copyCustomerId('${cellData}')" title="Copy to clipboard"></i>
                                </div>
                            `);
                        }
                    },
                    { data: 'name', name: 'name' },
                    { data: 'contact', name: 'contact' },
                    { data: 'address', name: 'address' },
                    { data: 'package', name: 'package' },
                    { data: 'expiration', name: 'expiration' },
                    {
                        data: 'cable_returned',
                        name: 'cable_returned',
                        render: (data) => data == 1 ? '<i class="fa fa-check-circle text-success"></i>' : '-',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'onu_free',
                        name: 'onu_free',
                        render: (data) => data == 1 ? '<span class="badge bg-success">Free</span>' : '-',
                        searchable: false,
                        orderable: false
                    },
                    { data: 'latest_comment', name: 'latest_comment', searchable: false, orderable: false },
                    { data: 'isp_code', name: 'isp_code' },
                    { data: 'status', name: 'status' },
                    {
                        data: 'total_due',
                        name: 'total_due',
                        searchable: false,
                        orderable: false,
                        render: (data) => data
                    },
                    { data: 'action', name: 'action', searchable: false, orderable: false },
                ],
                columnDefs: [
                    { orderable: false, className: 'select-checkbox', targets: 0 }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                rowCallback: function(row, data) {
                    const statusCell = $("td:eq(12)", row);
                    if (data.status === "Active") {
                        statusCell.addClass("text-success");
                    } else {
                        statusCell.addClass("text-danger");
                    }
                },
                order: [
                    [1, 'asc']
                ]
            });

            // SMS MODAL TEMPLATE SELECTION
            $('#template-select').on('change', function() {
                $('#sms-body').val(this.value);
                $('#sms-body').countSms('#sms-counter');
            });

            // Initial SMS count
            $('#sms-body').countSms('#sms-counter');

            // Bulk SMS count handler
            table.on('select deselect', function() {
                const count = table.rows({ selected: true }).count();
                $('#bulk_count').text(count);
                count > 0 ? $('#bulk_btn').show('fast') : $('#bulk_btn').hide('fast');

                // Also update quick bill count
                updateQuickBillCount();
            });

        });
        // ------------------ END DATA TABLES SETUP ------------------

        // ------------------ GLOBAL FUNCTIONS ------------------

        function setSmsId(id) {
            $("#client_id").val(id);
        }

        function resetText() {
            $("#sms-body").val('').countSms('#sms-counter');
            $('#template-select').val('');
        }

        function deleteClient(clientId) {
            // Step 1: Confirm intent with SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! This action requires password verification.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete Client'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Step 2: Open the password verification modal
                    $('#client_to_delete_id').val(clientId);
                    $('#verification_password').val(''); // Clear old password

                    var verifyModal = new bootstrap.Modal(document.getElementById('verifyPasswordModal'));
                    verifyModal.show();
                }
            });
        }

        function confirmDeleteWithPassword() {
            const clientId = $('#client_to_delete_id').val();
            const password = $('#verification_password').val();

            if (!password) {
                Swal.fire('Error', 'Please enter your password.', 'error');
                return;
            }

            // Hide the verification modal and show loading spinner
            $('#verifyPasswordModal').modal('hide');
            Swal.showLoading();

            const deleteUrl = `{{ url('clients') }}/${clientId}`;

            // Step 3: Send AJAX DELETE request with password for verification
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    password: password
                },
                success: function(response) {
                    Swal.hideLoading();
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#clients').DataTable().ajax.reload();
                    } else {
                        // Catches errors like 'Password is required' or 'Password incorrect'
                        Swal.fire('Failed!', response.message || 'Deletion failed due to verification error.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.hideLoading();
                    let errorMsg = 'Could not delete the record. Check server logs.';
                    if (xhr.status === 401) {
                         errorMsg = 'Verification Failed: The password provided is incorrect.';
                    } else if (xhr.status === 419) {
                        errorMsg = 'Session expired (CSRF Token Mismatch). Please reload the page.';
                    }
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        }

        function sendSMS() {
            var table = $('#clients').DataTable();
            var selectedData = table.rows({ selected: true }).data().toArray();

            var clientsData = $.map(selectedData, function(val) {
                return { username: val.username, contact: val.contact };
            });

            // Validate message is not empty before showing loader
            var smsText = $('#sms-body').val().trim();
            if (!smsText) {
                Swal.fire({ icon: 'warning', title: 'Empty Message', text: 'Please write a message before sending.' });
                return;
            }

            if (selectedData.length > 50) {
                Swal.fire({
                    icon: 'error',
                    title: 'Too Many Clients Selected',
                    text: `You have selected ${selectedData.length} clients. Please select 50 or fewer to send a bulk SMS.`,
                });
                return;
            }

            // Show proper loading dialog (must be fired first, THEN showLoading attaches to it)
            Swal.fire({
                title: 'Sending SMS...',
                text: selectedData.length > 0
                    ? `Sending to ${selectedData.length} client(s)...`
                    : 'Sending message...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });

            var ajaxUrl    = selectedData.length > 0 ? '{{ route('bulk_sms') }}' : '{{ route('solo_sms') }}';
            var ajaxData   = {
                _token: '{{ csrf_token() }}',
                sms:    smsText,
            };
            if (selectedData.length > 0) {
                ajaxData.clients = clientsData;
            } else {
                ajaxData.client_id = $('#client_id').val();
            }

            $.ajax({
                type: 'POST',
                url:  ajaxUrl,
                data: ajaxData,
                success: function(data) {
                    var ok = (data === true) || (data && data.success === true);
                    if (ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'SMS Sent!',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        resetText();
                        $('#smsModal').modal('hide');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'SMS Sending Failed!',
                            text: (data && data.message) ? data.message : 'Unknown error from API.',
                        });
                    }
                },
                error: function(xhr) {
                    var msg = 'Server error. Please check logs.';
                    if (xhr.status === 405) msg = 'Route method mismatch (405). Please contact admin.';
                    if (xhr.status === 419) msg = 'Session expired. Please reload the page.';
                    if (xhr.status === 422) msg = 'Validation error: ' + (xhr.responseJSON?.message || 'Invalid data.');
                    Swal.fire({ icon: 'error', title: 'SMS Sending Failed!', text: msg });
                }
            });
        }

        function showQr(id, name, contact) {
            // QR code data (vCard) - use \r\n for line endings and no indentation
            const vCard =
                "BEGIN:VCARD\r\n" +
                "VERSION:3.0\r\n" +
                `FN:${name}\r\n` +
                `TEL;TYPE=CELL:${contact}\r\n` +
                "END:VCARD";

            // WhatsApp message
            const message = `কার্নিভাল রিচার্জ\n` +
                            `হ্যালো ${name}\n` +
                            `আপনার ইন্টারনেট সংযোগের মেয়াদ শেষ। অনুগ্রহ করে রিচার্জ করুন।\n` +
                            `01770033448 (নগদ/বিকাশ)\n` +
                            `Hotline: +8809642363693`;

            // WhatsApp URL (mobile vs web fallback)
            const waAppLink = `whatsapp://send?phone=+88${contact}&text=${encodeURIComponent(message)}`;

            // Clear old QR code and create new
            const qrContainer = document.getElementById("qrcode");
            qrContainer.innerHTML = "";
            new QRCode(qrContainer, {
                text: vCard,
                width: 200,
                height: 200,
                correctLevel: QRCode.CorrectLevel.H
            });

            // Set client name
            document.getElementById("clientName").innerText = name;

            // WhatsApp button click
            const waBtn = document.getElementById("whatsappBtn");
            waBtn.onclick = function(e) {
                e.preventDefault();
                window.location.href = waAppLink ;
            };

            // Show modal (using Bootstrap 5 syntax)
            var qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
            qrModal.show();
        }

// ISP Filter functionality - Modern Enhanced Version
        $(document).on('click', '.filter-by-isp', function(e) {
            e.preventDefault();
            const ispCode = $(this).data('isp');
            const $btn = $(this);

            // Add visual feedback
            $btn.css('transform', 'scale(0.95)');
            setTimeout(() => $btn.css('transform', 'scale(1)'), 150);

            // Set the filter value in DataTables search
            const table = $('#clients').DataTable();
            table.column(11).search(ispCode, true, false).draw();

            // Scroll to the table with smooth animation
            $('html, body').animate({
                scrollTop: $('#clients').offset().top - 200
            }, 800, 'easeInOutQuad');

            // Show modern notification with ISP name
            const ispName = ispCode.charAt(0).toUpperCase() + ispCode.slice(1);
            Swal.fire({
                icon: 'info',
                title: 'Filtering Applied',
                html: `<strong>${ispName}</strong> Active Clients<br><small style="color: #999;">Showing only ${ispName} subscribers</small>`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            });
        });

        // Modern Details Button Handler - Enhanced
        $(document).on('click', '.details-btn', function(e) {
            e.preventDefault();
            const ispCode = $(this).data('isp');
            const ispName = ispCode.charAt(0).toUpperCase() + ispCode.slice(1);

            // Show loading state
            Swal.fire({
                title: `Loading ${ispName} Details...`,
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: (modal) => {
                    Swal.showLoading();
                }
            });

            // Fetch ISP statistics via AJAX
            $.ajax({
                url: '{{ route("clients.isp.stats") }}',
                type: 'GET',
                data: { isp_code: ispCode },
                success: function(data) {
                    // Get ISP colors
                    const ispColors = {
                        'carnival': { bg: '#eff6ff', accent: '#175ddc', light: '#e0ecff' },
                        'bijoy': { bg: '#ecf9ff', accent: '#0dcaf0', light: '#b3f0ff' }
                    };
                    const colors = ispColors[ispCode] || { bg: '#f3e8ff', accent: '#7c3aed', light: '#ede9fe' };

                    Swal.fire({
                        title: `<span style="color: ${colors['accent']}">${ispName}</span> ISP Details`,
                        html: `
                            <div style="text-align: left; background: ${colors['bg']}; padding: 24px; border-radius: 12px; margin: 20px 0;">
                                <!-- Header Stats -->
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                                    <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid ${colors['accent']};">
                                        <p style="margin: 0; font-size: 12px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Active Clients</p>
                                        <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: 700; color: ${colors['accent']};">${data.active_clients}</p>
                                    </div>
                                    <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #ffc107;">
                                        <p style="margin: 0; font-size: 12px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Market Share</p>
                                        <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: 700; color: #ffc107;">${data.market_share_percentage}%</p>
                                    </div>
                                </div>

                                <!-- Breakdown Stats -->
                                <div style="background: white; padding: 20px; border-radius: 8px;">
                                    <h6 style="margin: 0 0 16px 0; color: #1a202c; font-weight: 700; font-size: 14px;">Detailed Breakdown</h6>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                            <span style="font-size: 13px; color: #666;"><i class="fas fa-users" style="margin-right: 8px; color: ${colors['accent']};"></i>Total Clients</span>
                                            <span style="font-weight: 700; color: #1a202c;">${data.total_clients}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                            <span style="font-size: 13px; color: #666;"><i class="fas fa-user-times" style="margin-right: 8px; color: #dc3545;"></i>Expired</span>
                                            <span style="font-weight: 700; color: #1a202c;">${data.expired_clients}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                            <span style="font-size: 13px; color: #666;"><i class="fas fa-gift" style="margin-right: 8px; color: #17a2b8;"></i>Free ONU</span>
                                            <span style="font-weight: 700; color: #1a202c;">${data.free_onu_clients}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                            <span style="font-size: 13px; color: #666;"><i class="fas fa-plug" style="margin-right: 8px; color: #28a745;"></i>Cable Returned</span>
                                            <span style="font-weight: 700; color: #1a202c;">${data.cable_returned_clients}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: colors['accent'],
                        confirmButtonText: '👁️ View All Clients',
                        cancelButtonText: 'Close',
                        showCancelButton: true,
                        width: '600px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Trigger filter and show notification
                            $(`.filter-by-isp[data-isp="${ispCode}"]`).click();
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to load ISP statistics.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Animate progress bars on page load
        $(window).on('load', function() {
            setTimeout(function() {
                $('.progress-fill').each(function() {
                    const targetWidth = $(this).data('width');
                    $(this).css('width', targetWidth + '%');
                });
            }, 300);
        });

        // Initialize tooltips on page load
        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // ========== QUICK DUE BILL FUNCTIONALITY ==========

        // Show quick bill modal for a client
        // Store client details globally for copy feature
        var _qbClientContact = '';
        var _qbClientAddress = '';

        function showQuickBillModal(clientId, clientName, clientContact, clientAddress) {
            document.getElementById('quick_client_id').value = clientId;
            document.getElementById('quick_client_name').innerText = clientName;
            document.getElementById('quick_amount').value = '';
            document.getElementById('quick_notes').value = '';

            // Store contact & address for copy feature
            _qbClientContact = clientContact || '';
            _qbClientAddress = clientAddress || '';

            // Fetch package price for this client
            $.ajax({
                url: `{{ route('clients.package-price', ':id') }}`.replace(':id', clientId),
                type: 'GET',
                success: function(response) {
                    if (response.success && response.price) {
                        document.getElementById('quick_amount').value = response.price;
                        console.log(`Package "${response.package_name}" price: ৳${response.price}`);
                    }
                },
                error: function(err) {
                    console.log('Could not fetch package price');
                }
            });

            var quickBillModal = new bootstrap.Modal(document.getElementById('quickBillModal'));
            quickBillModal.show();
        }

        // Create quick bill with auto-filled current month
        function createQuickBill() {
            const clientId = document.getElementById('quick_client_id').value;
            const amount = document.getElementById('quick_amount').value;
            const notes = document.getElementById('quick_notes').value;

            if (!clientId || !amount) {
                Swal.fire('Error', 'Please fill in all required fields', 'error');
                return;
            }

            Swal.showLoading();

            // Today's date for bill_date and calculate due_date (30 days later)
            const today = new Date();
            const billDate = today.toISOString().split('T')[0];
            const dueDate = new Date(today.getTime() + 30*24*60*60*1000).toISOString().split('T')[0];

            const currentMonth = today.getMonth() + 1;
            const currentYear = today.getFullYear();

            $.ajax({
                url: '{{ route("due-bills.store") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    client_id: clientId,
                    month: currentMonth,
                    year: currentYear,
                    bill_date: billDate,
                    due_date: dueDate,
                    amount: amount,
                    notes: notes
                },
                success: function(response) {
                    Swal.hideLoading();

                    // Hide modal and reload table IMMEDIATELY so due status updates
                    var qbModalEl = document.getElementById('quickBillModal');
                    var qbModalInstance = bootstrap.Modal.getInstance(qbModalEl);
                    if (qbModalInstance) qbModalInstance.hide();
                    $('#clients').DataTable().ajax.reload(null, false);

                    var clientName = document.getElementById('quick_client_name').innerText;
                    var billAmount = document.getElementById('quick_amount').value;
                    var currentMonthYear = document.getElementById('quick_month_year').innerText;

                    // Formatted plain text for clipboard/sharing
                    window._qbCurrentCopyText = '🔔 *DUE BILL CREATED* 🔔\n' +
                        '-----------------------------\n' +
                        '👤 *Client:* ' + clientName + '\n' +
                        '📞 *Contact:* ' + (_qbClientContact || 'N/A') + '\n' +
                        '📍 *Address:* ' + (_qbClientAddress || 'N/A') + '\n' +
                        '📅 *Period:* ' + currentMonthYear + '\n' +
                        '💰 *Amount:* ৳' + parseFloat(billAmount).toLocaleString() + '\n' +
                        '-----------------------------';

                    // HTML version for Telegram with HTML parse mode
                    window._qbCurrentTelegramText = '🔔 <b>DUE BILL CREATED</b> 🔔\n' +
                        '-----------------------------\n' +
                        '👤 <b>Client:</b> ' + clientName + '\n' +
                        '📞 <b>Contact:</b> ' + (_qbClientContact || 'N/A') + '\n' +
                        '📍 <b>Address:</b> ' + (_qbClientAddress || 'N/A') + '\n' +
                        '📅 <b>Period:</b> ' + currentMonthYear + '\n' +
                        '💰 <b>Amount:</b> ৳' + parseFloat(billAmount).toLocaleString() + '\n' +
                        '-----------------------------';

                    Swal.fire({
                        icon: 'success',
                        title: 'Bill Created!',
                        html: `
                            <p style="margin-bottom: 12px; color: #6b7280; font-size: 0.9rem;">Due bill for current month created successfully.</p>
                            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 16px; text-align: left; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 8px; font-weight: 600; color: #166534; font-size: 0.85rem;">
                                    <i class="fas fa-user-circle"></i> ${clientName}
                                </div>
                                <div style="font-size: 0.82rem; color: #374151; line-height: 1.7;">
                                    <div><i class="fas fa-phone-alt" style="width: 16px; color: #6b7280;"></i> ${_qbClientContact || 'N/A'}</div>
                                    <div><i class="fas fa-map-marker-alt" style="width: 16px; color: #6b7280;"></i> ${_qbClientAddress || 'N/A'}</div>
                                    <div><i class="fas fa-money-bill-wave" style="width: 16px; color: #6b7280;"></i> ৳${parseFloat(billAmount).toLocaleString()}</div>
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <button onclick="qbCopyDetails()" class="btn btn-primary w-100" style="display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; padding: 10px; border-radius: 8px; background-color: #3b82f6; border-color: #3b82f6; color: white;">
                                    <i class="fas fa-copy"></i> Copy Bill Details
                                </button>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                    <button onclick="qbSendWhatsApp()" class="btn btn-success" style="display: flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; padding: 10px; border-radius: 8px; background-color: #25d366; border-color: #25d366; color: white;">
                                        <i class="fab fa-whatsapp"></i> WhatsApp Group
                                    </button>
                                    <button id="tg-share-btn" onclick="qbSendTelegram()" class="btn btn-info" style="display: flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; padding: 10px; border-radius: 8px; background-color: #0088cc; border-color: #0088cc; color: white;">
                                        <i class="fab fa-telegram-plane"></i> Telegram Channel
                                    </button>
                                </div>
                            </div>
                        `,
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        cancelButtonColor: '#6b7280',
                    });
                },
                error: function(xhr) {
                    Swal.hideLoading();
                    let errorMsg = 'Failed to create bill';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        }

        // Global sharing helpers for Quick Bill Success Dialog
        window.qbCopyDetails = function() {
            var copyText = window._qbCurrentCopyText || '';
            var ta = document.createElement('textarea');
            ta.value = copyText;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            ta.style.top = '-9999px';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                document.execCommand('copy');
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Bill details copied to clipboard.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } catch (err) {
                console.error('Failed to copy', err);
            }
            document.body.removeChild(ta);
        };

        window.qbSendWhatsApp = function() {
            var shareText = window._qbCurrentCopyText || '';
            var waUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(shareText);
            window.open(waUrl, '_blank');
        };

        window.qbSendTelegram = function() {
            var shareText = window._qbCurrentTelegramText || '';
            var $btn = $('#tg-share-btn');
            var originalHtml = $btn.html();

            // Loading state
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Sending...').prop('disabled', true);

            $.ajax({
                url: '{{ route("due-bills.send-telegram") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    message: shareText
                },
                success: function(response) {
                    $btn.html(originalHtml).prop('disabled', false);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sent!',
                            text: 'Bill details posted to Telegram Channel.',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire('Error', 'Failed to send to Telegram.', 'error');
                    }
                },
                error: function(err) {
                    $btn.html(originalHtml).prop('disabled', false);
                    Swal.fire('Error', 'An error occurred while sending to Telegram.', 'error');
                }
            });
        };

        // Add quick bill button to action cells
        function addQuickBillOption(rowIndex, clientId, clientName) {
            // This will be called from the DataTable rendering
        }

        // Update quick bill button count in toolbar
        function updateQuickBillCount() {
            const count = $('#bulk_count').text();
            $('#quick_bill_count').text(count);
            if (parseInt(count) > 0) {
                $('#quick-bill-btn').show('fast');
            } else {
                $('#quick-bill-btn').hide('fast');
            }
        }

        // Handle bulk quick bill creation
        function createQuickBillForSelected() {
            const table = $('#clients').DataTable();
            const selectedData = table.rows({ selected: true }).data().toArray();

            if (selectedData.length === 0) {
                Swal.fire('Error', 'Please select at least one client', 'error');
                return;
            }

            if (selectedData.length === 1) {
                showQuickBillModal(selectedData[0].id, selectedData[0].name, selectedData[0].contact || '', selectedData[0].address || '');
            } else {
                Swal.fire({
                    title: 'Create Bills for Multiple Clients?',
                    text: `Create due bills for ${selectedData.length} selected clients?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Create Bills',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        createMultipleQuickBills(selectedData);
                    }
                });
            }
        }

        // Create bills for multiple clients
        function createMultipleQuickBills(clientsData) {
            Swal.fire({
                title: 'Enter Amount for All Clients',
                input: 'number',
                inputLabel: 'Amount (৳)',
                inputPlaceholder: 'Enter amount',
                inputAttributes: {
                    step: '0.01',
                    min: '0.01'
                },
                showCancelButton: true,
                confirmButtonText: 'Create Bills'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Swal.showLoading();

                    const today = new Date();
                    const billDate = today.toISOString().split('T')[0];
                    const dueDate = new Date(today.getTime() + 30*24*60*60*1000).toISOString().split('T')[0];
                    const currentMonth = today.getMonth() + 1;
                    const currentYear = today.getFullYear();

                    let completed = 0;
                    let failed = 0;

                    clientsData.forEach(function(client) {
                        $.ajax({
                            url: '{{ route("due-bills.store") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                client_id: client.id,
                                month: currentMonth,
                                year: currentYear,
                                bill_date: billDate,
                                due_date: dueDate,
                                amount: result.value,
                                notes: `Bulk created on ${new Date().toLocaleDateString()}`
                            },
                            success: function(response) {
                                completed++;
                                checkBillCreationComplete(clientsData.length, completed, failed);
                            },
                            error: function(xhr) {
                                failed++;
                                checkBillCreationComplete(clientsData.length, completed, failed);
                            }
                        });
                    });
                }
            });
        }

        // Check if all bulk bill creations are complete
        function checkBillCreationComplete(total, completed, failed) {
            if ((completed + failed) === total) {
                Swal.hideLoading();
                Swal.fire({
                    icon: failed === 0 ? 'success' : 'warning',
                    title: 'Bills Created',
                    text: `Successfully created ${completed} bills${failed > 0 ? `. Failed: ${failed}` : ''}`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#clients').DataTable().ajax.reload();
                });
            }
        }

        // Update count on table selection/deselection
        $(document).on('selection.dt', '#clients', function() {
            updateQuickBillCount();
        });

        // Handle quick bill button click
        $(document).on('click', '#quick-bill-btn', function() {
            createQuickBillForSelected();
        });

        // ========== SOCIAL COMMENT MODAL ==========
        let _commentClientId   = null;
        let _commentClientName = null;
        let _commentType       = 'note';

        // Colour seeds for avatar gradients per username
        const _avatarGradients = [
            ['#6366f1','#4f46e5'], ['#ec4899','#be185d'], ['#f59e0b','#b45309'],
            ['#10b981','#047857'], ['#0ea5e9','#0369a1'], ['#8b5cf6','#6d28d9'],
            ['#ef4444','#b91c1c'], ['#14b8a6','#0f766e'],
        ];
        function _avatarGrad(name) {
            let h = 0;
            for (let c of (name||'?')) h = (h * 31 + c.charCodeAt(0)) & 0xffff;
            const [a,b] = _avatarGradients[h % _avatarGradients.length];
            return `linear-gradient(135deg,${a},${b})`;
        }

        // Open comment modal for a client
        function openCommentModal(clientId, clientName) {
            _commentClientId   = clientId;
            _commentClientName = clientName;

            // Reset compose
            $('#commentBody').val('');
            $('#commentCharCount').text('0 / 1000');

            // Set header
            $('#commentModalClientName').text(clientName);

            // Self-avatar initials
            const selfName = '{{ Auth::user()->name ?? "Me" }}';
            const selfInits = selfName.split(' ').map(w=>w[0]||'').join('').toUpperCase().slice(0,2);
            $('#commentSelfAvatar').text(selfInits).css('background', _avatarGrad(selfName));

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('commentModal'));
            modal.show();

            // Load comments
            loadComments();
        }

        function loadComments() {
            if (!_commentClientId) return;
            $('#commentFeed').html(`
                <div class="comment-loading">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    Loading comments…
                </div>
            `);

            $.ajax({
                url: `/clients/${_commentClientId}/comments`,
                type: 'GET',
                success: function(res) {
                    renderComments(res.comments);
                },
                error: function() {
                    $('#commentFeed').html('<div class="comment-loading text-danger"><i class="fas fa-exclamation-circle me-2"></i>Failed to load comments</div>');
                }
            });
        }

        function renderComments(comments) {
            const feed = $('#commentFeed');
            if (!comments || comments.length === 0) {
                feed.html(`
                    <div class="comment-empty">
                        <i class="fas fa-comments"></i>
                        <p>No notes yet. Be the first to add one!</p>
                    </div>
                `);
                return;
            }

            const typeMeta = {
                note:    { label: 'Note',  cls: 'badge-note',    icon: 'fa-sticky-note' },
                info:    { label: 'Info',  cls: 'badge-info',    icon: 'fa-info-circle' },
                success: { label: 'Done',  cls: 'badge-success', icon: 'fa-check-circle' },
                alert:   { label: 'Alert', cls: 'badge-alert',   icon: 'fa-exclamation-triangle' },
            };

            let html = '';
            comments.forEach(function(c) {
                const meta  = typeMeta[c.type] || typeMeta.note;
                const mineClass = c.is_mine ? 'mine' : '';
                const grad  = _avatarGrad(c.author_name);
                const del   = c.is_mine
                    ? `<button class="comment-delete-btn" onclick="deleteComment(${c.id})" title="Delete"><i class="fas fa-trash-alt"></i> Delete</button>`
                    : '';

                html += `
                <div class="comment-card" id="cc-${c.id}">
                    <div class="comment-avatar" style="background:${grad}">${c.author_initials}</div>
                    <div class="comment-bubble ${mineClass}">
                        <div class="comment-bubble-top">
                            <span class="comment-author">${_esc(c.author_name)}</span>
                            <span class="comment-type-badge ${meta.cls}"><i class="fas ${meta.icon}"></i> ${meta.label}</span>
                            <span class="comment-time" title="${_esc(c.created_at)}">${_esc(c.time_ago)}</span>
                        </div>
                        <p class="comment-body-text">${_esc(c.body)}</p>
                        <div class="comment-bubble-actions">${del}</div>
                    </div>
                </div>`;
            });

            feed.html(html);
        }

        function _esc(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        function submitComment() {
            const body = $('#commentBody').val().trim();
            if (!body) {
                $('#commentBody').focus();
                return;
            }

            const btn = $('#commentSendBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Posting…');

            $.ajax({
                url: `/clients/${_commentClientId}/comments`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    body:   body,
                    type:   _commentType,
                },
                success: function(res) {
                    if (res.success) {
                        $('#commentBody').val('');
                        $('#commentCharCount').text('0 / 1000');
                        loadComments(); // reload list
                        // Reload DataTable row to update count badge
                        $('#clients').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Failed to post comment';
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: msg, showConfirmButton: false, timer: 2500 });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Post');
                }
            });
        }

        function deleteComment(commentId) {
            Swal.fire({
                title: 'Delete this note?',
                text: 'This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then(result => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: `/client-comments/${commentId}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.success) {
                            $(`#cc-${commentId}`).fadeOut(300, function() { $(this).remove(); });
                            $('#clients').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function() {
                        Swal.fire({ toast: true, position:'top-end', icon:'error', title:'Could not delete', showConfirmButton:false, timer:2000 });
                    }
                });
            });
        }

        // Type pill selection
        $(document).on('click', '.ctype-btn', function() {
            $('.ctype-btn').removeClass('active');
            $(this).addClass('active');
            _commentType = $(this).data('type');
        });

        // Char counter + Ctrl+Enter submit
        $(document).on('input', '#commentBody', function() {
            const len = $(this).val().length;
            $('#commentCharCount').text(`${len} / 1000`);
        });
        $(document).on('keydown', '#commentBody', function(e) {
            if (e.ctrlKey && e.key === 'Enter') submitComment();
        });
        // ========== END SOCIAL COMMENT MODAL ==========

        // Robust copy to clipboard function
        function copyCustomerId(text) {
            // Create a temporary textarea element
            const $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val(text).select();

            try {
                // Execute copy command
                document.execCommand("copy");
                // Show success toast
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Customer ID copied to clipboard!',
                    showConfirmButton: false,
                    timer: 1500
                });
            } catch (err) {
                console.error('Failed to copy', err);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed to copy!',
                    showConfirmButton: false,
                    timer: 1500
                });
            } finally {
                // Remove the temporary element
                $temp.remove();
            }
        }
    </script>

@endsection
