@extends('layouts.app')

@section('content')
{{-- ── Fullscreen Spinning Loader Overlay ── --}}
<div id="importLoaderOverlay" class="import-loader-overlay d-none">
    <div class="import-loader-content">
        <div class="import-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring spinner-ring-2"></div>
            <div class="spinner-ring spinner-ring-3"></div>
            <i class="fa fa-file-excel spinner-icon"></i>
        </div>
        <h4 class="import-loader-title">Importing Clients</h4>
        <p class="import-loader-subtitle" id="loaderStatusText">Uploading file...</p>
        <div class="import-loader-stats">
            <div class="loader-stat">
                <span class="loader-stat-value" id="loaderPercent">0%</span>
                <span class="loader-stat-label">Progress</span>
            </div>
            <div class="loader-stat">
                <span class="loader-stat-value" id="loaderElapsed">0s</span>
                <span class="loader-stat-label">Elapsed</span>
            </div>
        </div>
        <div class="loader-progress-track">
            <div class="loader-progress-fill" id="loaderProgressFill"></div>
        </div>
    </div>
</div>

<style>
.import-loader-overlay {
    position: fixed;
    inset: 0;
    z-index: 999999 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    animation: overlayFadeIn 0.3s ease;
}
.import-loader-overlay.d-none { display: none !important; }

@keyframes overlayFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.import-loader-content {
    text-align: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    padding: 48px 56px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
    min-width: 340px;
}

/* Triple-ring spinner */
.import-spinner {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 28px;
}

.spinner-ring {
    position: absolute;
    inset: 0;
    border: 3px solid transparent;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spinRing 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
}
.spinner-ring-2 {
    inset: 10px;
    border-top-color: #06b6d4;
    animation-delay: -0.15s;
    animation-duration: 1.6s;
}
.spinner-ring-3 {
    inset: 20px;
    border-top-color: #8b5cf6;
    animation-delay: -0.3s;
    animation-duration: 2s;
}

@keyframes spinRing {
    to { transform: rotate(360deg); }
}

.spinner-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    color: #22c55e;
    animation: iconPulse 2s ease-in-out infinite;
}

@keyframes iconPulse {
    0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
    50%      { opacity: 1;   transform: translate(-50%, -50%) scale(1.15); }
}

.import-loader-title {
    color: #f1f5f9;
    font-weight: 700;
    font-size: 1.35rem;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.import-loader-subtitle {
    color: #94a3b8;
    font-size: 0.9rem;
    margin-bottom: 24px;
    animation: subtitlePulse 2s ease-in-out infinite;
}

@keyframes subtitlePulse {
    0%, 100% { opacity: 0.7; }
    50%      { opacity: 1; }
}

.import-loader-stats {
    display: flex;
    gap: 32px;
    justify-content: center;
    margin-bottom: 20px;
}

.loader-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.loader-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #e2e8f0;
    font-variant-numeric: tabular-nums;
}

.loader-stat-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #64748b;
    margin-top: 2px;
}

.loader-progress-track {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.loader-progress-fill {
    height: 100%;
    width: 0%;
    border-radius: 4px;
    background: linear-gradient(90deg, #3b82f6, #06b6d4, #8b5cf6);
    background-size: 200% 100%;
    animation: gradientShift 2s ease infinite;
    transition: width 0.4s ease;
}

@keyframes gradientShift {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Import Form --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-upload me-2"></i> Import Clients</h5>
                </div>
                <div class="card-body">
                    <form id="importForm" action="{{ route('clients.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ISP Selector --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select ISP <span class="text-danger">*</span></label>
                            <select name="isp_code" id="isp_code"
                                    class="form-select @error('isp_code') is-invalid @enderror" required>
                                <option value="">-- Select ISP --</option>
                                <option value="carnival" {{ old('isp_code', session('isp_code')) == 'carnival' ? 'selected' : '' }}>Carnival</option>
                                <option value="bijoy"    {{ old('isp_code', session('isp_code')) == 'bijoy'    ? 'selected' : '' }}>Bijoy</option>
                                <option value="icc"      {{ old('isp_code', session('isp_code')) == 'icc'      ? 'selected' : '' }}>ICC</option>
                            </select>
                            @error('isp_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- File Picker --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Excel / CSV File <span class="text-danger">*</span></label>
                            <input type="file" id="clients_file" name="clients_file"
                                   class="form-control @error('clients_file') is-invalid @enderror"
                                   accept=".xlsx,.xls,.csv" required>
                            @error('clients_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Accepted: .xlsx, .xls, .csv — Max 10MB</div>
                        </div>

                        {{-- File Info Preview --}}
                        <div id="fileInfo" class="alert alert-secondary py-2 small d-none">
                            <i class="fa fa-file-excel me-1 text-success"></i>
                            <span id="fileName"></span>
                            <span class="text-muted ms-2" id="fileSize"></span>
                        </div>

                        {{-- Format Guide --}}
                        <div class="alert alert-info py-2 mb-3 small">
                            <strong><i class="fa fa-info-circle me-1"></i> Expected Headers:</strong>
                            <ul class="mb-0 mt-1">
                                <li><strong>Carnival</strong> — Row 1: <code>Carnival_ID, Name, Address, Mobile, Email, Package, Expiration, Status</code></li>
                                <li><strong>Bijoy / ICC</strong> — Row 2: <code>Cust ID, Username, Name, Package, Exp Date, Mobile, Status, Flat/Level, House, Road, Area, Email</code></li>
                            </ul>
                        </div>

                        {{-- Progress Bar --}}
                        <div id="progressWrapper" class="mb-3 d-none">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span id="progressLabel" class="fw-bold small text-primary">
                                    <i class="fa fa-spinner fa-spin me-1"></i> Uploading file...
                                </span>
                                <span id="progressPercent" class="small fw-bold">0%</span>
                            </div>
                            <div class="progress" style="height: 24px; border-radius: 6px;">
                                <div id="progressBar"
                                     class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width: 0%; background-color: #0d6efd; transition: width 0.4s ease;"
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1 small">
                                <span id="progressStatus" class="text-muted">Preparing...</span>
                                <span id="progressTime"  class="text-muted">0s</span>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2 align-items-center">
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

            {{-- ① Summary Stats --}}
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

                            {{-- New --}}
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

                            {{-- Updated --}}
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

                            {{-- Skipped --}}
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

            {{-- ② Changed Fields Log --}}
            @if(session('import_changes'))
                <div class="card mt-3" style="border: 1px solid #0dcaf0;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background-color:#0dcaf0;">
                        <span class="fw-bold" style="color:#055160;">
                            <i class="fa fa-sync me-1"></i>
                            {{ count(session('import_changes')) }} field(s) updated
                        </span>
                        <button class="btn btn-sm btn-light" type="button"
                                data-bs-toggle="collapse" data-bs-target="#changesTable">
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
                                            <td>
                                                <span class="badge" style="background-color:#6c757d;">
                                                    {{ ucfirst($change['isp_code']) }}
                                                </span>
                                            </td>
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

            {{-- ③ Newly Added Clients --}}
            @if(session('import_new'))
                <div class="card mt-3" style="border: 1px solid #198754;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background-color:#198754;">
                        <span class="fw-bold text-white">
                            <i class="fa fa-plus-circle me-1"></i>
                            {{ count(session('import_new')) }} new client(s) added
                        </span>
                        <button class="btn btn-sm btn-light" type="button"
                                data-bs-toggle="collapse" data-bs-target="#newClients">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="newClients">
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush small">
                                @foreach(session('import_new') as $new)
                                    <li class="list-group-item">
                                        <span class="badge" style="background-color:#6c757d;">
                                            {{ ucfirst($new['isp_code']) }}
                                        </span>
                                        <code class="ms-2">{{ $new['username'] }}</code>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ④ Skipped Rows --}}
            @if(session('import_errors'))
                <div class="card mt-3" style="border: 1px solid #ffc107;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background-color:#ffc107;">
                        <span class="fw-bold" style="color:#332701;">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            {{ count(session('import_errors')) }} row(s) skipped
                        </span>
                        <button class="btn btn-sm btn-dark" type="button"
                                data-bs-toggle="collapse" data-bs-target="#skippedRows">
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

            {{-- ⑤ Fatal Error --}}
            @if(session('import_error_fatal'))
                <div class="alert alert-danger mt-3">
                    <strong><i class="fa fa-times-circle me-1"></i> Fatal Error:</strong>
                    {{ session('import_error_fatal') }}
                </div>
            @endif

        </div>
    </div>
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

        // Show fullscreen loader
        const overlay = document.getElementById('importLoaderOverlay');
        if (overlay) {
            overlay.classList.remove('d-none');
            overlay.style.display = 'flex';
        }

        // Optional SweetAlert loader as backup visual
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Importing Clients...',
                text: 'Uploading & processing Excel/CSV rows. Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
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
            updateLoader(uploadPct, 'Uploading file...');
        });

        // ── Phase 2: Upload done, server processing (60 → 90%) ─
        xhr.upload.addEventListener('load', function () {
            setProgress(60, 'Processing rows...', '#0dcaf0');
            animateTo(90, 60, 2000, 'Processing rows...', '#0dcaf0');
            updateLoader(60, 'Processing rows...');
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
                updateLoader(100, 'Import complete!');

                if (importBtn) importBtn.classList.add('d-none');
                if (resetBtn) resetBtn.classList.remove('d-none');

                setTimeout(() => {
                    hideLoader();
                    if (typeof Swal !== 'undefined') Swal.close();
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
                hideLoader();
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
            hideLoader();
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
            updateLoader(current, statusText);
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    function formatBytes(bytes) {
        if (bytes < 1024)    return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function updateLoader(percent, statusText) {
        const lp = document.getElementById('loaderPercent');
        const pf = document.getElementById('loaderProgressFill');
        const st = document.getElementById('loaderStatusText');
        const le = document.getElementById('loaderElapsed');

        if (lp) lp.textContent = percent + '%';
        if (pf) pf.style.width = percent + '%';
        if (statusText && st) st.textContent = statusText;
        if (startTime && le) {
            const s = Math.floor((Date.now() - startTime) / 1000);
            le.textContent = s + 's';
        }
    }

    function hideLoader() {
        const overlay = document.getElementById('importLoaderOverlay');
        if (!overlay) return;
        overlay.style.animation = 'overlayFadeIn 0.3s ease reverse';
        setTimeout(() => {
            overlay.classList.add('d-none');
            overlay.style.display = 'none';
            overlay.style.animation = '';
        }, 300);
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
