@extends('layouts.app')
@section('title')
    @lang('models/clients.plural')
@endsection
@section('content')
    <section class="section">
        <!-- Dashboard Section - Can be hidden for privacy -->
        <div id="dashboard-section" class="dashboard-section">
            <!-- Main Statistics Section -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card card-statistic-1 h-100 shadow-sm transition-card" data-toggle="tooltip" title="Total number of active clients">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Active Clients</h4>
                            </div>
                            <div class="card-body">
                                <span class="display-stat-number">{{ $ActiveClientsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card card-statistic-1 h-100 shadow-sm transition-card" data-toggle="tooltip" title="Clients with expired subscriptions">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Expired Clients</h4>
                            </div>
                            <div class="card-body">
                                <span class="display-stat-number">{{ $expiredClientsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card card-statistic-1 h-100 shadow-sm transition-card" data-toggle="tooltip" title="Clients with free ONU equipment">
                        <div class="card-icon bg-info">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Free ONU</h4>
                            </div>
                            <div class="card-body">
                                <span class="display-stat-number">{{ $freeOnuClientsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card card-statistic-1 h-100 shadow-sm transition-card" data-toggle="tooltip" title="Clients with returned cables">
                        <div class="card-icon bg-success">
                            <i class="fas fa-plug"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Cable Returned</h4>
                            </div>
                            <div class="card-body">
                                <span class="display-stat-number">{{ $cableReturnedClientsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ISP-wise Active Clients Section -->
            @if($ispWiseActiveClients->count() > 0)
            <div class="row mt-5">
            <div class="col-12">
                <div class="modern-section-header mb-5">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="header-text">
                            <h3 class="header-title">ISP Network Analytics</h3>
                            <p class="header-subtitle">Real-time distribution of active clients across your network providers</p>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $totalActive = $ispWiseActiveClients->sum();
            @endphp

            <!-- ISP Cards Grid -->
            <div class="col-12">
                <div class="isp-cards-grid">
                    @foreach($ispWiseActiveClients as $ispCode => $count)
                        @php
                            $percentage = $totalActive > 0 ? round(($count / $totalActive) * 100, 1) : 0;

                            // Modern color scheme per ISP
                            $ispConfig = [
                                'carnival' => [
                                    'icon' => 'fa-router',
                                    'gradientStart' => '#1e3a8a',
                                    'gradientEnd' => '#3b82f6',
                                    'accentColor' => '#175ddc',
                                    'lightBg' => '#eff6ff',
                                    'borderColor' => '#175ddc'
                                ],
                                'bijoy' => [
                                    'icon' => 'fa-broadcast-tower',
                                    'gradientStart' => '#0891b2',
                                    'gradientEnd' => '#06b6d4',
                                    'accentColor' => '#0dcaf0',
                                    'lightBg' => '#ecf9ff',
                                    'borderColor' => '#0dcaf0'
                                ],
                            ];
                            $config = $ispConfig[strtolower($ispCode)] ?? [
                                'icon' => 'fa-network-wired',
                                'gradientStart' => '#6366f1',
                                'gradientEnd' => '#8b5cf6',
                                'accentColor' => '#7c3aed',
                                'lightBg' => '#f3e8ff',
                                'borderColor' => '#7c3aed'
                            ];
                        @endphp
                        <div class="modern-isp-card" data-isp="{{ strtolower($ispCode) }}">
                            <div class="card-content">
                                <!-- Header with icon -->
                                <div class="card-header-modern">
                                    <div class="icon-wrapper" style="background: linear-gradient(135deg, {{ $config['gradientStart'] }} 0%, {{ $config['gradientEnd'] }} 100%);">
                                        <i class="fas {{ $config['icon'] }}"></i>
                                    </div>
                                    <div class="header-info">
                                        <h5 class="isp-name">{{ ucfirst($ispCode) }}</h5>
                                        <p class="isp-subtitle">Internet Provider</p>
                                    </div>
                                    <div class="percentage-badge" style="background-color: {{ $config['lightBg'] }}; color: {{ $config['accentColor'] }};">
                                        {{ $percentage }}%
                                    </div>
                                </div>

                                <!-- Main Stats -->
                                <div class="card-stats-section">
                                    <div class="main-stat">
                                        <span class="stat-number">{{ $count }}</span>
                                        <span class="stat-label">Active Clients</span>
                                    </div>

                                    <!-- Visual Representation -->
                                    <div class="chart-section">
                                        <div class="modern-progress-bar">
                                            <div class="progress-track" style="background: {{ $config['lightBg'] }};">
                                                <div class="progress-fill"
                                                     style="width: 0%; background: linear-gradient(90deg, {{ $config['gradientStart'] }} 0%, {{ $config['gradientEnd'] }} 100%); transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);"
                                                     data-width="{{ $percentage }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-info">
                                            <span class="progress-label">Market Share</span>
                                            <span class="progress-value">{{ $percentage }}%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Footer -->
                                <div class="card-footer-modern">
                                    <button class="action-btn filter-btn filter-by-isp" data-isp="{{ $ispCode }}" title="Filter by {{ ucfirst($ispCode) }}">
                                        <i class="fas fa-sliders-h"></i>
                                        <span>Filter</span>
                                    </button>
                                    <button class="action-btn details-btn" data-isp="{{ $ispCode }}" title="View details">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>Details</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        </div><!-- End of dashboard-section -->

        <style>
            /* ========== DASHBOARD SECTION TOGGLE ========== */

            .dashboard-section {
                max-height: 2000px;
                overflow: hidden;
                transition: max-height 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                            opacity 0.6s ease;
                opacity: 1;
                margin-bottom: 24px;
            }

            .dashboard-section.hidden {
                max-height: 0;
                opacity: 0;
                margin-bottom: 0;
                visibility: hidden;
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
        </style>

        <div class="section-body" id="clients-header">
            <div class="card">
                <div class="card-body">
                    <!-- Compact Toolbar -->
                    <div class="clients-toolbar mb-3" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                        <button type="button" class="btn btn-sm btn-info" id="privacy-toggle-btn" title="Toggle dashboard visibility for privacy">
                            <i class="fas fa-eye-slash"></i> <span id="privacy-toggle-text">Hide Dashboard</span>
                        </button>

                        <a href="#" id="bulk_btn" style="display: none" data-bs-toggle="modal" data-bs-target="#smsModal"
                           class="btn btn-sm btn-warning">Bulk SMS <i class="fas fa-envelope"></i> <span id="bulk_count"
                            class="badge badge-success p-1"></span> </a>

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

@endsection


@section('scripts')
    <script src="{{ asset('js/sms_counter.min.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        // ========== PRIVACY TOGGLE FUNCTIONALITY ==========
        $(document).ready(function() {
            // Check localStorage for dashboard state
            const dashboardState = localStorage.getItem('dashboard_hidden') === 'true';
            const $dashboardSection = $('#dashboard-section');
            const $toggleBtn = $('#privacy-toggle-btn');
            const $toggleText = $('#privacy-toggle-text');

            // Apply saved state on page load
            if (dashboardState) {
                $dashboardSection.addClass('hidden');
                $toggleBtn.removeClass('btn-info').addClass('btn-secondary');
                $toggleBtn.html('<i class="fas fa-eye"></i> <span id="privacy-toggle-text">Show Dashboard</span>');
            }

            // Toggle dashboard visibility
            $toggleBtn.on('click', function(e) {
                e.preventDefault();

                $dashboardSection.toggleClass('hidden');
                const isHidden = $dashboardSection.hasClass('hidden');

                // Update button appearance
                if (isHidden) {
                    $toggleBtn.removeClass('btn-info').addClass('btn-secondary');
                    $toggleBtn.html('<i class="fas fa-eye"></i> Show Dashboard');
                    localStorage.setItem('dashboard_hidden', 'true');

                    // Smooth scroll to table
                    $('html, body').animate({
                        scrollTop: $('#clients-header').offset().top - 100
                    }, 500);
                } else {
                    $toggleBtn.removeClass('btn-secondary').addClass('btn-info');
                    $toggleBtn.html('<i class="fas fa-eye-slash"></i> Hide Dashboard');
                    localStorage.setItem('dashboard_hidden', 'false');
                }
            });
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

                            $(td).html(`<a href="${url}" target="_blank">${cellData}</a>`);
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
                    { data: 'comment', name: 'comment' },
                    { data: 'isp_code', name: 'isp_code' },
                    { data: 'status', name: 'status' },
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
            Swal.showLoading();

            var table = $('#clients').DataTable();
            var selectedData = table.rows({
                selected: true
            }).data().toArray();

            var clientsData = $.map(selectedData, function(val) {
                return {
                    username: val.username,
                    contact: val.contact
                };
            })

            if (selectedData.length > 0 && selectedData.length <= 50) { //Bulk sms

                $.ajax({
                    type: 'POST', // UX/Security FIX: Use POST
                    url: '{{ route('bulk_sms') }}',
                    data: {
                        _token: '{{ csrf_token() }}', // UX/Security FIX: Add CSRF token
                        clients: clientsData,
                        sms: $('#sms-body').val()
                    },
                    success: function(data) {
                        Swal.hideLoading();

                        if (data == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'SMS SENT',
                                showConfirmButton: false,
                                timer: 1500,

                            });

                            resetText();
                            $('#smsModal').modal('hide');
                        } else {
                            Swal.hideLoading();

                            Swal.fire({
                                icon: 'error',
                                title: 'SMS SENDING FAILED!',
                                showConfirmButton: true,
                            })
                        }
                    }
                });

            } else if (selectedData.length > 51) {
                Swal.hideLoading();
                // UX FIX: Improved error message
                Swal.fire({
                    icon: 'error',
                    title: 'Too Many Clients Selected',
                    text: `You have selected ${selectedData.length} clients. Please select 50 or fewer to send a bulk SMS.`,
                    showConfirmButton: true,
                })

            } else {
                //single sms
                Swal.hideLoading();
                $.ajax({
                    type: 'POST', // UX/Security FIX: Use POST
                    url: '{{ route('solo_sms') }}',
                    data: {
                        _token: '{{ csrf_token() }}', // UX/Security FIX: Add CSRF token
                        client_id: $('#client_id').val(),
                        sms: $('#sms-body').val()
                    },
                    success: function(data) {

                        if (data == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'SMS SENT',
                                showConfirmButton: false,
                                timer: 1500,

                            })

                            resetText()
                            $('#smsModal').modal('hide')
                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'SMS SENDING FAILED!',
                                showConfirmButton: true,
                            })
                        }
                    }
                });

            }
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
    </script>

@endsection
