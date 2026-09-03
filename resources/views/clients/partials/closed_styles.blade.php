<style>
        :root {
            --crm-navy:   #1e293b;
            --crm-blue:   #2563eb;
            --crm-indigo: #4f46e5;
            --crm-success:#10b981;
            --crm-warning:#f59e0b;
            --crm-danger: #ef4444;
            --crm-ink:    #0f172a;
            --crm-muted:  #64748b;
            --crm-line:   #e2e8f0;
            --crm-soft:   #f8fafc;
            --crm-radius: 12px;
            --crm-shadow: 0 1px 2px rgba(15,23,42,.04), 0 8px 24px rgba(15,23,42,.06);
        }

        .page-title-heading {
            font-size: 1.55rem;
            font-weight: 700;
            color: var(--crm-ink);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.01em;
        }
        .page-title-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
            color: #fff;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(79,70,229,.25);
        }
        .page-subtitle {
            color: var(--crm-muted);
            font-size: 0.85rem;
            margin: 4px 0 0 52px;
        }
        .btn-back {
            background: #fff;
            border: 1px solid var(--crm-line);
            color: var(--crm-ink);
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .15s ease;
        }
        .btn-back:hover { background: var(--crm-soft); color: var(--crm-blue); border-color: #cbd5e1; }
        .btn-refresh {
            background: #fff;
            border: 1px solid var(--crm-line);
            color: var(--crm-muted);
            width: 38px; height: 38px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .15s ease;
        }
        .btn-refresh:hover { background: var(--crm-soft); color: var(--crm-blue); }
        .btn-refresh.is-spinning i { animation: crm-spin 0.7s linear; }
        @keyframes crm-spin { to { transform: rotate(360deg); } }

        .kpi-band {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        @media (max-width: 992px) { .kpi-band { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 540px) { .kpi-band { grid-template-columns: 1fr; } }
        .kpi-card {
            position: relative;
            background: #fff;
            border-radius: var(--crm-radius);
            border: 1px solid rgba(15,23,42,.05);
            box-shadow: var(--crm-shadow);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(15,23,42,.05), 0 16px 32px rgba(15,23,42,.08); }
        .kpi-card-inner {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 14px;
            padding: 20px;
            align-items: flex-start;
        }
        .kpi-icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .kpi-primary  .kpi-icon-wrap { background: rgba(79,70,229,.10);  color: var(--crm-indigo); }
        .kpi-success  .kpi-icon-wrap { background: rgba(16,185,129,.10);  color: var(--crm-success); }
        .kpi-warning  .kpi-icon-wrap { background: rgba(245,158,11,.12);  color: var(--crm-warning); }
        .kpi-danger   .kpi-icon-wrap { background: rgba(239,68,68,.10);   color: var(--crm-danger);  }
        .kpi-meta { flex: 1; min-width: 0; }
        .kpi-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--crm-muted);
        }
        .kpi-value {
            display: block;
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--crm-ink);
            margin-top: 2px;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }
        .kpi-value small {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--crm-muted);
            margin-left: 2px;
        }
        .kpi-progress {
            height: 5px;
            background: rgba(15,23,42,.06);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }
        .kpi-progress-bar { height: 100%; border-radius: 4px; transition: width .6s ease; }
        .kpi-success .kpi-progress-bar { background: linear-gradient(90deg,#10b981,#34d399); }
        .kpi-warning .kpi-progress-bar { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
        .kpi-danger  .kpi-progress-bar { background: linear-gradient(90deg,#ef4444,#f87171); }
        .kpi-foot {
            display: block;
            font-size: 0.72rem;
            color: var(--crm-muted);
            margin-top: 6px;
        }
        .kpi-glow {
            position: absolute;
            top: -40px; right: -40px;
            width: 120px; height: 120px;
            border-radius: 50%;
            filter: blur(40px);
            opacity: .25;
            z-index: 1;
        }
        .kpi-primary .kpi-glow { background: var(--crm-indigo); }
        .kpi-success .kpi-glow { background: var(--crm-success); }
        .kpi-warning .kpi-glow { background: var(--crm-warning); }
        .kpi-danger  .kpi-glow { background: var(--crm-danger); }

        .pro-card {
            background: #fff;
            border-radius: var(--crm-radius);
            border: 1px solid rgba(15,23,42,.05);
            box-shadow: var(--crm-shadow);
            overflow: hidden;
        }
        .pro-card-header {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--crm-line);
            background: linear-gradient(180deg, #fbfcfd 0%, #f7f9fc 100%);
            gap: 12px;
            flex-wrap: wrap;
        }
        .pro-card-title {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--crm-ink);
            font-size: 0.95rem;
        }
        .pro-card-title i { color: var(--crm-blue); }
        .pro-card-meta {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            font-size: 0.78rem;
            color: var(--crm-muted);
        }
        .filter-meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(37,99,235,.08);
            color: var(--crm-blue);
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.72rem;
        }
        .pro-legend { display: inline-flex; align-items: center; gap: 6px; }
        .pro-legend .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .dot-success { background: var(--crm-success); }
        .dot-warning { background: var(--crm-warning); }
        .dot-danger  { background: var(--crm-danger);  }
        .pro-card-body { padding: 16px 20px; }

        .pro-filter-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: end;
        }
        @media (max-width: 1100px) { .pro-filter-row { grid-template-columns: 1fr; } }
        .pro-filter-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--crm-muted);
            margin-bottom: 6px;
        }
        .pro-chip-set { display: flex; flex-wrap: wrap; gap: 6px; }
        .pro-chip {
            background: #fff;
            color: var(--crm-muted);
            border: 1px solid var(--crm-line);
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .15s ease;
        }
        .pro-chip:hover { background: var(--crm-soft); color: var(--crm-ink); }
        .pro-chip.active {
            background: var(--crm-blue);
            color: #fff;
            border-color: var(--crm-blue);
            box-shadow: 0 4px 10px rgba(37,99,235,.25);
        }
        .pro-chip.chip-success.active { background: var(--crm-success); border-color: var(--crm-success); box-shadow: 0 4px 10px rgba(16,185,129,.25); }
        .pro-chip.chip-warning.active { background: var(--crm-warning); border-color: var(--crm-warning); color:#fff; box-shadow: 0 4px 10px rgba(245,158,11,.25); }
        .pro-chip.chip-danger.active  { background: var(--crm-danger);  border-color: var(--crm-danger);  box-shadow: 0 4px 10px rgba(239,68,68,.25);  }
        .pro-chip.chip-dark.active    { background: var(--crm-navy);    border-color: var(--crm-navy);    box-shadow: 0 4px 10px rgba(15,23,42,.25);   }
        .pro-filter-side {
            display: grid;
            grid-template-columns: 140px 220px auto;
            gap: 10px;
        }
        @media (max-width: 768px) { .pro-filter-side { grid-template-columns: 1fr; } }
        .pro-control {
            background: #fff;
            border: 1px solid var(--crm-line);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.85rem;
            color: var(--crm-ink);
            width: 100%;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .pro-control:focus {
            border-color: var(--crm-blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            outline: none;
        }
        .pro-search { position: relative; }
        .pro-search i {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--crm-muted);
            font-size: 0.85rem;
        }
        .pro-search input {
            background: #fff;
            border: 1px solid var(--crm-line);
            border-radius: 8px;
            padding: 8px 12px 8px 34px;
            font-size: 0.85rem;
            color: var(--crm-ink);
            width: 100%;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .pro-search input:focus {
            border-color: var(--crm-blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            outline: none;
        }
        .btn-pro-reset {
            background: var(--crm-soft);
            color: var(--crm-muted);
            border: 1px solid var(--crm-line);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            transition: all .15s ease;
            white-space: nowrap;
        }
        .btn-pro-reset:hover { background: #fee2e2; color: var(--crm-danger); border-color: #fecaca; }

        .pro-table-wrap { overflow-x: auto; }
        .pro-table {
            margin-bottom: 0;
            font-size: 0.84rem;
            min-width: 1100px;
        }
        .pro-table thead th {
            background: var(--crm-soft);
            border-bottom: 1px solid var(--crm-line);
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--crm-muted);
            padding: 12px 14px;
            white-space: nowrap;
        }
        .pro-table thead .th-group {
            text-align: center;
            background: #eef2f7;
            border-bottom: 2px solid var(--crm-line);
            font-size: 0.72rem;
        }
        .pro-table thead .th-group-cable { color: var(--crm-blue); }
        .pro-table thead .th-group-onu   { color: var(--crm-indigo); }
        .pro-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            background: #fff;
        }
        .pro-table tbody tr:hover td { background: #fafbfc; }
        .pro-table tbody tr.row-both-pending td { background: #fffafa; }
        .pro-table tbody tr.row-both-pending:hover td { background: #fff1f2; }
        .pro-table tbody tr.row-both-pending td.cable-cell,
        .pro-table tbody tr.row-both-pending td.onu-cell {
            background: rgba(239,68,68,.04);
        }

        .th-index { width: 50px; text-align: center; }
        .th-actions { width: 140px; text-align: right; }

        .customer-cell { display: flex; align-items: center; gap: 10px; }
        .customer-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg,#4f46e5,#2563eb);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
            flex-shrink: 0;
            text-transform: uppercase;
        }
        .customer-meta { display: flex; flex-direction: column; line-height: 1.2; }
        .customer-name { font-weight: 600; color: var(--crm-ink); }
        .customer-id   { font-size: 0.72rem; color: var(--crm-muted); }

        .address-cell {
            display: inline-flex;
            align-items: center;
            max-width: 260px;
            color: var(--crm-muted);
            font-size: 0.82rem;
            line-height: 1.35;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
        .status-returned { background: rgba(16,185,129,.10); color: #047857; }
        .status-returned .dot { background: var(--crm-success); }
        .status-pending  { background: rgba(245,158,11,.12); color: #b45309; }
        .status-pending  .dot { background: var(--crm-warning); }
        .status-overdue  { background: rgba(239,68,68,.10); color: #b91c1c; }
        .status-overdue  .dot { background: var(--crm-danger); }

        .action-stack {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            justify-content: flex-end;
        }
        .btn-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            border: 1px solid var(--crm-line);
            background: #fff;
            color: var(--crm-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            transition: all .15s ease;
            cursor: pointer;
        }
        .btn-icon:hover { transform: translateY(-1px); }
        .btn-icon.btn-info-icon    { color: var(--crm-blue); }
        .btn-icon.btn-info-icon:hover    { background: rgba(37,99,235,.08); border-color: #93c5fd; }
        .btn-icon.btn-warning-icon { color: var(--crm-warning); }
        .btn-icon.btn-warning-icon:hover { background: rgba(245,158,11,.10); border-color: #fcd34d; }
        .btn-icon.btn-danger-icon  { color: var(--crm-danger); }
        .btn-icon.btn-danger-icon:hover  { background: rgba(239,68,68,.08); border-color: #fca5a5; }
        .btn-icon.btn-success-icon { color: var(--crm-success); }
        .btn-icon.btn-success-icon:hover { background: rgba(16,185,129,.08); border-color: #6ee7b7; }
        .btn-icon.btn-copy-icon    { color: #0891b2; }
        .btn-icon.btn-copy-icon:hover    { background: rgba(8,145,178,.08); border-color: #67e8f9; }

        .quick-toggle-group {
            display: inline-flex;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--crm-line);
        }
        .quick-toggle-group button {
            background: #fff;
            border: 0;
            padding: 4px 8px;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--crm-muted);
            cursor: pointer;
            transition: all .15s ease;
        }
        .quick-toggle-group button + button { border-left: 1px solid var(--crm-line); }
        .quick-toggle-group button:hover { background: var(--crm-soft); color: var(--crm-ink); }
        .quick-toggle-group button.is-yes.is-active { background: rgba(16,185,129,.10); color: #047857; }
        .quick-toggle-group button.is-no.is-active  { background: rgba(245,158,11,.12); color: #b45309; }

        .pro-modal {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15,23,42,.20);
        }
        .pro-modal-header {
            padding: 18px 22px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
        }
        .pro-modal-header-info    { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .pro-modal-header-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .pro-modal-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: rgba(255,255,255,.18);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .pro-modal-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.01em;
        }
        .pro-modal-sub { font-size: 0.78rem; margin: 2px 0 0; opacity: .85; }
        .pro-modal-close {
            position: absolute;
            top: 12px; right: 12px;
            background: rgba(255,255,255,.15);
            border: 0;
            color: #fff;
            width: 30px; height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s ease;
        }
        .pro-modal-close:hover { background: rgba(255,255,255,.28); }
        .pro-modal-body { padding: 22px; }
        .pro-form-group { margin-bottom: 16px; }
        .pro-form-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--crm-muted);
            margin-bottom: 6px;
        }
        .pro-form-control {
            width: 100%;
            background: #fff;
            border: 1px solid var(--crm-line);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 0.88rem;
            color: var(--crm-ink);
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .pro-form-control:focus {
            border-color: var(--crm-blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            outline: none;
        }
        .pro-form-control[readonly] {
            background: var(--crm-soft);
            color: var(--crm-ink);
            font-weight: 600;
        }
        .pro-modal-footer {
            padding: 14px 22px;
            background: var(--crm-soft);
            border-top: 1px solid var(--crm-line);
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        .btn-pro-secondary {
            background: #fff;
            border: 1px solid var(--crm-line);
            color: var(--crm-muted);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all .15s ease;
        }
        .btn-pro-secondary:hover { background: var(--crm-soft); color: var(--crm-ink); }
        .btn-pro-info, .btn-pro-warning {
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all .15s ease;
        }
        .btn-pro-info    { background: linear-gradient(135deg,#3b82f6,#2563eb); box-shadow: 0 4px 10px rgba(37,99,235,.25); }
        .btn-pro-warning { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow: 0 4px 10px rgba(245,158,11,.25); }
        .btn-pro-info:hover, .btn-pro-warning:hover { transform: translateY(-1px); }

        .mr-1 { margin-right: 6px; }

        .dataTables_wrapper .dataTables_info {
            color: var(--crm-muted);
            font-size: 0.82rem;
            padding-top: 14px;
            padding-left: 20px;
        }
        .dataTables_wrapper .dataTables_paginate { padding-right: 20px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1px solid var(--crm-line) !important;
            margin: 0 2px !important;
            padding: 4px 10px !important;
            background: #fff !important;
            color: var(--crm-muted) !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--crm-soft) !important;
            color: var(--crm-ink) !important;
            border-color: var(--crm-line) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--crm-blue) !important;
            color: #fff !important;
            border-color: var(--crm-blue) !important;
        }
        .dataTables_empty {
            text-align: center !important;
            padding: 40px !important;
            color: var(--crm-muted) !important;
        }
        .dataTables_empty::before {
            content: "\f15b";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            display: block;
            font-size: 2rem;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .page-subtitle { margin-left: 0; }
            .pro-table { font-size: 0.8rem; }
        }
    </style>