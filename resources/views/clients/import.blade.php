@extends('layouts.app')

@section('css')
<style>
    .client-import-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1rem 0 2.5rem;
    }

    .client-import-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(30, 41, 59, 0.08);
    }

    .client-import-page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .client-import-page-title-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.20);
    }

    .client-import-page-title-icon i {
        font-size: 1.1rem;
    }

    .client-import-page-title h1 {
        font-size: 1.65rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 0.2rem;
    }

    .client-import-page-title p {
        font-size: 0.82rem;
        font-weight: 500;
        color: #64748b;
        margin: 0;
    }

    .client-import-header-actions {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .client-import-grid {
        display: grid;
        grid-template-columns: minmax(440px, 1.2fr) minmax(280px, 0.8fr);
        gap: 1rem;
        align-items: start;
    }

    .client-import-card,
    .client-import-guide {
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.20);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        background: #fff;
    }

    .client-import-card .card-body {
        padding: 1.4rem;
    }

    .client-import-card .card-header {
        background: linear-gradient(135deg, #1d4ed8, #4338ca);
        color: #fff;
        border: none;
        border-radius: 16px 16px 0 0;
        padding: 1rem 1.2rem;
    }

    .client-import-card .card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
    }

    .client-import-form {
        padding: 0;
    }

    .client-import-form .form-label {
        font-size: 0.84rem;
        font-weight: 800;
        color: #334155;
        letter-spacing: 0.02em;
    }

    .client-import-form .form-select,
    .client-import-form .form-control {
        min-height: 44px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #1e293b;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .client-import-form .form-select:focus,
    .client-import-form .form-control:focus {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .client-import-form .form-text {
        font-size: 0.78rem;
        margin-top: 0.45rem;
    }

    .file-drop-wrap {
        position: relative;
        margin-bottom: 0.9rem;
    }

    .client-file-drop {
        width: 100%;
        min-height: 132px;
        border-radius: 14px;
        border: 2px dashed #a5b4fc;
        background: linear-gradient(135deg, #eef2ff, #f8fafc);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #334155;
    }

    .client-file-drop:hover {
        transform: translateY(-2px);
        border-color: #4f46e5;
        box-shadow: 0 10px 22px rgba(99, 102, 241, 0.12);
    }

    .client-file-drop i {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.10);
    }

    .client-file-drop .drop-title {
        display: block;
        font-size: 0.95rem;
        font-weight: 900;
        color: #1e293b;
    }

    .client-file-drop .drop-subtitle {
        display: block;
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    #clients_file {
        position: absolute;
        opacity: 0;
        inset: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    #fileInfo {
        border-radius: 12px;
        background: #eefbf3;
        border: 1px solid #a7f3d0;
        color: #14532d;
        font-weight: 700;
        padding: 0.8rem 1rem;
    }

    .client-import-guide {
        padding: 1.1rem;
        border-radius: 16px;
    }

    .client-import-guide .guide-header {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        font-weight: 900;
        color: #1e293b;
        font-size: 0.98rem;
        margin-bottom: 0.8rem;
    }

    .client-import-guide .guide-header i {
        color: #6366f1;
    }

    .client-import-guide .guide-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.8rem;
    }

    .client-import-guide .guide-list li {
        padding: 0.8rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.79rem;
    }

    .client-import-guide .guide-list li strong {
        color: #1e293b;
    }

    .client-import-guide .guide-list code {
        font-size: 0.75rem;
        color: #334155;
        background: #eef2ff;
        padding: 0.18rem 0.36rem;
        border-radius: 6px;
    }

    .client-import-guide .guide-meta {
        padding: 0.7rem 0.8rem;
        border-radius: 12px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 0.78rem;
        font-weight: 700;
        margin-top: 0.75rem;
    }

    .client-import-progress-wrap {
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 0.9rem;
        margin-top: 1rem;
    }

    .client-import-progress-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.45rem;
    }

    .client-import-progress-title span {
        font-size: 0.75rem;
        font-weight: 800;
        color: #475569;
    }

    .client-import-progress-title b {
        font-size: 0.76rem;
        color: #4f46e5;
    }

    .client-import-card .progress {
        height: 20px;
        border-radius: 8px;
        background: #e2e8f0;
    }

    .client-import-card .progress-bar {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
    }

    .client-import-actions {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .client-import-card .btn {
        min-width: 118px;
    }

    @media (max-width: 991px) {
        .client-import-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

<div class="client-import-wrapper">
    <div class="client-import-page-header">
        <div class="client-import-page-title">
            <span class="client-import-page-title-icon">
                <i class="fa fa-upload"></i>
            </span>
            <div>
                <h1>Import Clients</h1>
                <p>Upload a spreadsheet and sync your client records</p>
            </div>
        </div>
        <div class="client-import-header-actions">
            <a href="{{ route('clients.index') }}" class="btn btn-back">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="client-import-grid">
        <div class="client-import-card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fa fa-upload me-2"></i> Import Clients</h5>
            </div>
            <div class="card-body">
                <form id="importForm" class="client-import-form" action="{{ route('clients.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select ISP <span class="text-danger">*</span></label>
                        <select name="isp_code" id="isp_code" class="form-select @error('isp_code') is-invalid @enderror" required>
                            <option value="">-- Select ISP --</option>
                            <option value="carnival" {{ old('isp_code', session('isp_code')) == 'carnival' ? 'selected' : '' }}>Carnival</option>
                            <option value="bijoy" {{ old('isp_code', session('isp_code')) == 'bijoy' ? 'selected' : '' }}>Bijoy</option>
                            <option value="icc" {{ old('isp_code', session('isp_code')) == 'icc' ? 'selected' : '' }}>ICC</option>
                        </select>
                        @error('isp_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Excel / CSV File <span class="text-danger">*</span></label>
                        <div class="file-drop-wrap">
                            <label class="client-file-drop" for="clients_file">
                                <i class="fa fa-cloud-upload"></i>
                                <span>
                                    <span class="drop-title">Choose Excel or CSV file</span>
                                    <span class="drop-subtitle">Drag or click to browse</span>
                                </span>
                            </label>
                            <input type="file" id="clients_file" name="clients_file" class="form-control @error('clients_file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                        </div>
                        @error('clients_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Accepted: .xlsx, .xls, .csv — Max 10MB</div>
                    </div>

                    <div id="fileInfo" class="alert alert-secondary py-2 small d-none">
                        <i class="fa fa-file-excel me-1 text-success"></i>
                        <span id="fileName"></span>
                        <span class="text-muted ms-2" id="fileSize"></span>
                    </div>

                    <div id="progressWrapper" class="client-import-progress-wrap d-none">
                        <div class="client-import-progress-title">
                            <span id="progressLabel">
                                <i class="fa fa-spinner fa-spin me-1"></i> Uploading file...
                            </span>
                            <b id="progressPercent">0%</b>
                        </div>
                        <div class="progress">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%; transition: width 0.4s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small">
                            <span id="progressStatus" class="text-muted">Preparing...</span>
                            <span id="progressTime" class="text-muted">0s</span>
                        </div>
                    </div>

                    <div class="client-import-actions">
                        <button type="submit" id="importBtn" class="btn btn-primary">
                            <i class="fa fa-upload me-1"></i> Import
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-outline-secondary d-none" onclick="resetImport()">
                            <i class="fa fa-redo me-1"></i> Import Another
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <aside class="client-import-guide">
            <div class="guide-header">
                <i class="fa fa-info-circle"></i>
                <span>Expected Headers</span>
            </div>
            <ul class="guide-list">
                <li>
                    <strong>Carnival</strong><br>
                    <code>Carnival_ID, Name, Address, Mobile, Email, Package, Expiration, Status</code>
                </li>
                <li>
                    <strong>Bijoy / ICC</strong><br>
                    <code>Cust ID, Username, Name, Package, Exp Date, Mobile, Status, Flat/Level, House, Road, Area, Email</code>
                </li>
            </ul>
            <div class="guide-meta">
                <i class="fa fa-file-excel me-1"></i> Accepted: .xlsx, .xls, .csv
            </div>
        </aside>
    </div>

    @if(session('import_new') !== null || session('import_changes') !== null || session('import_errors') !== null)
        <div class="card mt-3 shadow-sm">
            <div class="card-header" style="background-color: #343a40;">
                <h6 class="mb-0 text-white fw-bold">
                    <i class="fa fa-chart-bar me-2"></i>Import Summary
                    @if(session('isp_code'))
                        <span class="badge ms-2" style="background-color:#0d6efd;">{{ ucfirst(session('isp_code')) }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body" style="background-color: #f8f9fa;">
                <div class="row text-center g-3">
                    <div class="col-4">
                        <div class="p-3 rounded" style="background-color:#d1e7dd; border: 2px solid #198754;">
                            <div class="fw-bold" style="font-size:2rem; color:#146c43;">
                                {{ count(session('import_new', [])) }}
                            </div>
                            <div class="fw-semibold" style="color:#146c43; font-size:0.85rem;">
                                New Clients
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 rounded" style="background-color:#cff4fc; border: 2px solid #0dcaf0;">
                            <div class="fw-bold" style="font-size:2rem; color:#055160;">
                                {{ count(session('import_changes', [])) }}
                            </div>
                            <div class="fw-semibold" style="color:#055160; font-size:0.85rem;">
                                Fields Updated
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="p-3 rounded" style="background-color:#fff3cd; border: 2px solid #ffc107;">
                            <div class="fw-bold" style="font-size:2rem; color:#664d03;">
                                {{ count(session('import_errors', [])) }}
                            </div>
                            <div class="fw-semibold" style="color:#664d03; font-size:0.85rem;">
                                Skipped Rows
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_changes'))
        <div class="card mt-3" style="border: 1px solid #0dcaf0;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#0dcaf0;">
                <span class="fw-bold" style="color:#055160;">
                    <i class="fa fa-sync me-1"></i>
                    {{ count(session('import_changes')) }} field(s) updated
                </span>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#changesTable">
                    <i class="fa fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="changesTable">
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered table-hover mb-0 small">
                        <thead style="background-color:#e2f4f8;">
                            <tr>
                                <th style="color:#055160;">ISP</th>
                                <th style="color:#055160;">Username</th>
                                <th style="color:#055160;">Field</th>
                                <th style="color:#842029;">Old Value</th>
                                <th style="color:#0a3622;">New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('import_changes') as $change)
                                <tr>
                                    <td><span class="badge" style="background-color:#6c757d;">{{ ucfirst($change['isp_code']) }}</span></td>
                                    <td><code>{{ $change['username'] }}</code></td>
                                    <td><strong>{{ $change['field'] }}</strong></td>
                                    <td style="color:#842029;">{{ $change['old'] ?? '—' }}</td>
                                    <td style="color:#0a3622;">{{ $change['new'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_new'))
        <div class="card mt-3" style="border: 1px solid #198754;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#198754;">
                <span class="fw-bold text-white">
                    <i class="fa fa-plus-circle me-1"></i>
                    {{ count(session('import_new')) }} new client(s) added
                </span>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#newClients">
                    <i class="fa fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="newClients">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        @foreach(session('import_new') as $new)
                            <li class="list-group-item">
                                <span class="badge" style="background-color:#6c757d;">{{ ucfirst($new['isp_code']) }}</span>
                                <code class="ms-2">{{ $new['username'] }}</code>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="card mt-3" style="border: 1px solid #ffc107;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#ffc107;">
                <span class="fw-bold" style="color:#332701;">
                    <i class="fa fa-exclamation-triangle me-1"></i>
                    {{ count(session('import_errors')) }} row(s) skipped
                </span>
                <button class="btn btn-sm btn-dark" type="button" data-bs-toggle="collapse" data-bs-target="#skippedRows">
                    <i class="fa fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="skippedRows">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        @foreach(session('import_errors') as $error)
                            <li class="list-group-item" style="color:#842029;">
                                <i class="fa fa-times-circle me-1"></i> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_error_fatal'))
        <div class="alert alert-danger mt-3">
            <strong><i class="fa fa-times-circle me-1"></i> Fatal Error:</strong>
            {{ session('import_error_fatal') }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    initImportForm();
});

// Fallback in case DOMContentLoaded fired before script tag parsed
if (document.readyState === 'interactive' || document.readyState === 'complete') {
    initImportForm();
}

function initImportForm() {
    const form            = document.getElementById('importForm');
    const fileInput       = document.getElementById('clients_file');
    const importBtn       = document.getElementById('importBtn');
    const resetBtn        = document.getElementById('resetBtn');
    const fileInfo        = document.getElementById('fileInfo');
    const progressWrapper = document.getElementById('progressWrapper');
    const progressBar     = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressLabel   = document.getElementById('progressLabel');
    const progressStatus  = document.getElementById('progressStatus');
    const progressTime    = document.getElementById('progressTime');

    if (!form || form.dataset.initialized) return;
    form.dataset.initialized = 'true';

    let timerInterval = null;
    let startTime     = null;

    // ── File selected preview ──────────────────────────────────
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) { if (fileInfo) fileInfo.classList.add('d-none'); return; }
            const fn = document.getElementById('fileName');
            const fs = document.getElementById('fileSize');
            if (fn) fn.textContent = file.name;
            if (fs) fs.textContent = '(' + formatBytes(file.size) + ')';
            if (fileInfo) fileInfo.classList.remove('d-none');
        });
    }

    // ── Form submit — XHR upload with real progress ────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // stop default submit

        const ispCode = document.getElementById('isp_code') ? document.getElementById('isp_code').value : '';
        const file    = fileInput && fileInput.files ? fileInput.files[0] : null;

        if (!ispCode || !file) {
            alert('Please select an ISP and a file.');
            return;
        }

        // Show progress UI
        if (progressWrapper) progressWrapper.classList.remove('d-none');
        if (importBtn) {
            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Importing...';
        }

        startTime = Date.now();

        // Elapsed timer
        timerInterval = setInterval(function () {
            const s = Math.floor((Date.now() - startTime) / 1000);
            if (progressTime) progressTime.textContent = s + 's';
        }, 500);

        // Build FormData
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        // ── Phase 1: Upload progress (0 → 60%) ────────────────
        xhr.upload.addEventListener('progress', function (e) {
            if (!e.lengthComputable) return;
            const uploadPct = Math.round((e.loaded / e.total) * 60); // 0-60%
            setProgress(uploadPct, 'Uploading file...', '#0d6efd');
        });

        // ── Phase 2: Upload done, server processing (60 → 90%) ─
        xhr.upload.addEventListener('load', function () {
            setProgress(60, 'Processing rows...', '#0dcaf0');
            animateTo(90, 60, 2000, 'Processing rows...', '#0dcaf0');
        });

        // ── Phase 3: Response received (90 → 100%) ─────────────
        xhr.addEventListener('load', function () {
            clearInterval(timerInterval);

            if (xhr.status === 200) {
                setProgress(100, 'Import complete!', '#198754');
                if (progressLabel) {
                    progressLabel.innerHTML =
                        '<i class="fa fa-check-circle me-1" style="color:#198754;"></i>' +
                        '<span style="color:#198754;">Import Complete!</span>';
                }
                if (progressStatus) progressStatus.textContent = 'Done';

                if (importBtn) importBtn.classList.add('d-none');
                if (resetBtn) resetBtn.classList.remove('d-none');

                setTimeout(() => {
                    document.open();
                    document.write(xhr.responseText);
                    document.close();
                }, 500);

            } else {
                setProgress(100, 'Import failed!', '#dc3545');
                if (progressLabel) {
                    progressLabel.innerHTML =
                        '<i class="fa fa-times-circle me-1" style="color:#dc3545;"></i>' +
                        '<span style="color:#dc3545;">Import Failed</span>';
                }
                if (importBtn) {
                    importBtn.disabled = false;
                    importBtn.innerHTML = '<i class="fa fa-upload me-1"></i> Retry';
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Import Failed', 'An error occurred during import.', 'error');
                }
            }
        });

        xhr.addEventListener('error', function () {
            clearInterval(timerInterval);
            setProgress(100, 'Network error!', '#dc3545');
            if (progressLabel) {
                progressLabel.innerHTML =
                    '<i class="fa fa-times-circle me-1" style="color:#dc3545;"></i>' +
                    '<span style="color:#dc3545;">Network Error</span>';
            }
            if (importBtn) {
                importBtn.disabled = false;
                importBtn.innerHTML = '<i class="fa fa-upload me-1"></i> Retry';
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire('Network Error', 'Could not connect to server.', 'error');
            }
        });

        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });

    function setProgress(percent, statusText, color) {
        if (progressBar) {
            progressBar.style.width           = percent + '%';
            progressBar.style.backgroundColor = color;
            progressBar.setAttribute('aria-valuenow', percent);
        }
        if (progressPercent) progressPercent.textContent = percent + '%';
        if (statusText && progressStatus) progressStatus.textContent = statusText;
    }

    function animateTo(target, from, duration, statusText, color) {
        const startVal = from;
        const startTs  = performance.now();

        function step(now) {
            const elapsed  = now - startTs;
            const progress = Math.min(elapsed / duration, 1);
            const current  = Math.round(startVal + (target - startVal) * progress);
            setProgress(current, statusText, color);
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    function formatBytes(bytes) {
        if (bytes < 1024)    return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // ── If page loaded with results — show complete state ─────
    @if(session('import_new') !== null || session('import_changes') !== null || session('import_errors') !== null)
        if (progressWrapper) progressWrapper.classList.remove('d-none');
        setProgress(100, 'Done', '#198754');
        if (progressLabel) {
            progressLabel.innerHTML =
                '<i class="fa fa-check-circle me-1" style="color:#198754;"></i>' +
                '<span style="color:#198754;">Import Complete!</span>';
        }
        if (progressPercent) progressPercent.textContent = '100%';
        if (progressTime) progressTime.textContent    = 'Done';
        if (importBtn) importBtn.classList.add('d-none');
        if (resetBtn) resetBtn.classList.remove('d-none');
    @endif
}

function resetImport() {
    const form = document.getElementById('importForm');
    if (form) form.reset();
    const fi = document.getElementById('fileInfo');
    const pw = document.getElementById('progressWrapper');
    if (fi) fi.classList.add('d-none');
    if (pw) pw.classList.add('d-none');

    const bar = document.getElementById('progressBar');
    if (bar) {
        bar.style.width           = '0%';
        bar.style.backgroundColor = '#0d6efd';
        bar.setAttribute('aria-valuenow', 0);
    }

    const pp = document.getElementById('progressPercent');
    const ps = document.getElementById('progressStatus');
    const pt = document.getElementById('progressTime');
    const pl = document.getElementById('progressLabel');
    if (pp) pp.textContent = '0%';
    if (ps) ps.textContent = 'Preparing...';
    if (pt) pt.textContent = '0s';
    if (pl) pl.innerHTML   = '<i class="fa fa-spinner fa-spin me-1"></i> Uploading file...';

    const ib = document.getElementById('importBtn');
    const rb = document.getElementById('resetBtn');
    if (ib) {
        ib.disabled = false;
        ib.innerHTML = '<i class="fa fa-upload me-1"></i> Import';
        ib.classList.remove('d-none');
    }
    if (rb) rb.classList.add('d-none');
}
</script>
@endsection
