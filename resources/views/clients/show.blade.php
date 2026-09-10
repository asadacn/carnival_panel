@extends('layouts.app')

@section('title')
    @lang('models/clients.singular') @lang('crud.details')
@endsection

@section('content')
    <section class="section client-detail-page">
        <div class="section-header modern-detail-header">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('clients.index') }}" class="btn btn-ghost btn-back shadow-sm" title="Back to Clients">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="d-flex align-items-center gap-3">
                    <div class="client-name-pill text-uppercase fw-bold px-4 py-2 rounded-circle shadow-glow"
                         style="background: {{ $client->status == 'Active' ? 'linear-gradient(135deg, #10b981, #059669)' : ($client->status == 'Expired' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #2563eb, #1d4ed8)') }}">
                        {{ substr(strtoupper($client->name), 0, 1) }}
                    </div>
                    <div>
                        <h1 class="mb-0 fw-bold text-dark">{{ $client->name }}</h1>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-id-badge me-1"></i> {{ $client->username ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap section-header-breadcrumb">
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-cta-secondary btn-sm shadow-sm" title="Edit Client">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>

                @if($client->total_due > 0)
                <a href="{{ route('due-bill-payments.create', ['client_id' => $client->id]) }}" class="btn btn-cta-success btn-sm shadow-sm" title="Add Payment">
                    <i class="fas fa-money-bill-wave me-1"></i> Add Payment
                </a>
                @endif

                <a href="{{ route('clients.due-bills', $client->id) }}" class="btn btn-cta-secondary btn-sm shadow-sm" title="View Due Bills">
                    <i class="fas fa-file-invoice me-1"></i> Bills
                </a>

                <a href="{{ route('clients.payment-history', $client->id) }}" class="btn btn-cta-secondary btn-sm shadow-sm" title="Payment History">
                    <i class="fas fa-history me-1"></i> Payments
                </a>

                <a href="{{ route('clients.bills-statement-pdf', $client->id) }}" class="btn btn-cta-info btn-sm shadow-sm" target="_blank" title="Bill Statement (PDF)">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <a href="{{ route('clients.payments-statement-pdf', $client->id) }}" class="btn btn-cta-info btn-sm shadow-sm" target="_blank" title="Payment Statement (PDF)">
                    <i class="fas fa-file-invoice"></i>
                </a>

                <a href="#" class="btn btn-cta-secondary btn-sm shadow-sm" title="Send SMS" onclick="setSmsId({{ $client->id }})" data-bs-toggle="modal" data-bs-target="#smsModal">
                    <i class="fas fa-paper-plane"></i>
                </a>

                <a href="#" class="btn btn-cta-secondary btn-sm shadow-sm" title="Show QR" onclick="showQr({{ $client->id }}, '{{ addslashes($client->name) }}', '{{ addslashes($client->contact ?? '') }}')">
                    <i class="fas fa-qrcode"></i>
                </a>

                @if(!$client->closed_at)
                <button type="button" class="btn btn-cta-warning btn-sm shadow-sm" title="Close Client" onclick="addToClosedList({{ $client->id }}, '{{ addslashes($client->name) }}')">
                    <i class="fas fa-user-slash"></i>
                </button>
                @else
                <button type="button" class="btn btn-cta-success btn-sm shadow-sm" title="Reopen Client" onclick="removeFromClosedList({{ $client->id }}, '{{ addslashes($client->name) }}')">
                    <i class="fas fa-user-check"></i>
                </button>
                @endif

                <button type="button" class="btn btn-cta-danger btn-sm shadow-sm" title="Delete Client" onclick="deleteClient({{ $client->id }})">
                    <i class="fas fa-trash"></i>
                </button>

                <button type="button" class="btn btn-cta-primary btn-sm shadow-sm" title="Add Note" onclick="openCommentModal({{ $client->id }}, '{{ addslashes($client->name) }}')">
                    <i class="fas fa-comment-plus me-1"></i> Note
                </button>
            </div>
        </div>

        @include('stisla-templates::common.errors')

        <div class="section-body">
            @include('clients.show_fields')
        </div>

        <!-- Comment Modal -->
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered comment-modal-dialog">
                <div class="modal-content comment-modal-content">
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
                    <div class="comment-feed-wrap" id="commentFeed">
                        <div class="comment-loading">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Loading comments…
                        </div>
                    </div>
                    <div class="comment-compose-wrap">
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
                                <textarea id="commentBody" class="comment-textarea"
                                          placeholder="Write a note, update or alert…" rows="2" maxlength="1000"></textarea>
                                <div class="comment-input-footer">
                                    <span class="comment-char-count" id="commentCharCount">0 / 1000</span>
                                    <button type="button" class="comment-send-btn" id="commentSendBtn" onclick="submitComment()">
                                        <i class="fas fa-paper-plane"></i> Post
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Modal -->
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
                            @foreach ($templates ?? [] as $template)
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

        <!-- QR Modal -->
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

        <!-- Verify Password Modal -->
        <div class="modal fade" id="verifyPasswordModal" tabindex="-1" aria-labelledby="verifyPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyPasswordModalLabel">Verify Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please enter your password to confirm the <strong>deletion</strong> of this client.</p>
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
    </section>
@endsection

@section('css')
<style>
    .client-detail-page { opacity: 0; animation: fadeInUp 0.4s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    /* Ensure modals and backdrops stack correctly above all page content */
    .modal-backdrop { z-index: 2050 !important; }
    .modal { z-index: 2060 !important; }
    .modal > .modal-dialog { z-index: 2070 !important; }

    .modern-detail-header {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 18px 28px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .modern-detail-header h1 { font-size: 1.4rem; }

    .client-name-pill {
        width: 44px; height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        letter-spacing: 1px;
        color: #fff;
    }
    .shadow-glow { box-shadow: 0 0 0 3px rgba(255,255,255,0.5), 0 2px 8px rgba(0,0,0,0.08); }

    .btn-ghost {
        background: rgba(248, 250, 255, 0.85);
        border: 1.5px solid #e2e8f0;
        color: #475569;
        border-radius: 8px;
        padding: 7px 14px;
        transition: all 0.2s ease;
    }
    .btn-ghost:hover {
        background: #f1f5f9;
        color: #1e293b;
        transform: translateX(-1px);
    }

    /* CTA Button variants matching index page */
    .btn-cta-primary   { background: linear-gradient(135deg,#059669 0%,#10b981 100%); color:#fff !important; border:none; border-radius:10px; padding:7px 16px; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(16,185,129,.25); }
    .btn-cta-primary:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(16,185,129,.38); color:#fff !important; }
    .btn-cta-success   { background: linear-gradient(135deg,#059669 0%,#10b981 100%); color:#fff !important; border:none; border-radius:10px; padding:7px 16px; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(16,185,129,.25); }
    .btn-cta-success:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(16,185,129,.38); color:#fff !important; }
    .btn-cta-secondary  { background:#fff; color:#475569 !important; border:1.5px solid #cbd5e1 !important; border-radius:10px; padding:7px 16px; transition:all 0.2s ease; }
    .btn-cta-secondary:hover { background:#f8fafc; color:#0f172a !important; border-color:#94a3b8 !important; transform:translateY(-1px); }
    .btn-cta-info    { background: linear-gradient(135deg,#0ea5e9 0%,#0284c7 100%); color:#fff !important; border:none; border-radius:10px; padding:7px 16px; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(14,165,233,.25); }
    .btn-cta-info:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(14,165,233,.38); color:#fff !important; }
    .btn-cta-warning { background: linear-gradient(135deg,#d97706 0%,#f59e0b 100%); color:#fff !important; border:none; border-radius:10px; padding:7px 16px; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(245,158,11,.25); }
    .btn-cta-warning:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(245,158,11,.38); color:#fff !important; }
    .btn-cta-danger  { background: linear-gradient(135deg,#dc2626 0%,#ef4444 100%); color:#fff !important; border:none; border-radius:10px; padding:7px 16px; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(239,68,68,.25); }
    .btn-cta-danger:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(239,68,68,.38); color:#fff !important; }

    /* ---------- Card & section styles (injected via show_fields) ---------- */

    /* ===== COMMENT MODAL ===== */
    .comment-modal-dialog { max-width: 560px; }
    .comment-modal-content {
        border: none; border-radius: 18px; overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        display: flex; flex-direction: column; max-height: 90vh;
    }
    .comment-modal-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        padding: 18px 20px; display: flex; align-items: center;
        justify-content: space-between; flex-shrink: 0;
    }
    .comment-modal-header-inner { display: flex; align-items: center; gap: 12px; }
    .comment-modal-avatar-wrap {
        width: 42px; height: 42px; border-radius: 50%;
        background: rgba(255,255,255,0.15); display: flex;
        align-items: center; justify-content: center; color: #fff;
        font-size: 1.1rem; backdrop-filter: blur(6px);
    }
    .comment-modal-title { margin: 0; color: #fff; font-size: 1rem; font-weight: 700; letter-spacing: 0.2px; }
    .comment-modal-subtitle { margin: 3px 0 0; color: rgba(255,255,255,0.75); font-size: 0.8rem; }
    .comment-modal-close {
        background: rgba(255,255,255,0.12); border: none; color: #fff;
        width: 34px; height: 34px; border-radius: 50%; display: flex;
        align-items: center; justify-content: center; cursor: pointer;
        transition: all 0.2s; font-size: 0.9rem;
    }
    .comment-modal-close:hover { background: rgba(255,255,255,0.25); transform: rotate(90deg); }
    .comment-feed-wrap {
        flex: 1; overflow-y: auto; padding: 18px 20px;
        background: #f8fafc; display: flex; flex-direction: column;
        gap: 14px; min-height: 200px; max-height: 380px;
    }
    .comment-loading { display: flex; align-items: center; color: #94a3b8; font-size: 0.9rem; justify-content: center; padding: 30px 0; }
    .comment-empty { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .comment-empty i { font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 10px; }
    .comment-empty p { margin: 0; font-size: 0.9rem; }
    .comment-card {
        display: flex; gap: 10px; animation: commentSlideIn 0.3s ease;
    }
    @keyframes commentSlideIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .comment-avatar {
        width: 38px; height: 38px; border-radius: 50%; display: flex;
        align-items: center; justify-content: center; font-weight: 700;
        font-size: 0.75rem; color: #fff; flex-shrink: 0; text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(59,130,246,0.25);
    }
    .comment-bubble {
        flex: 1; background: #fff; border-radius: 14px 14px 14px 4px;
        padding: 12px 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0; position: relative;
    }
    .comment-bubble.mine {
        border-radius: 14px 14px 4px 14px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #bfdbfe;
    }
    .comment-bubble-top { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
    .comment-author { font-weight: 700; font-size: 0.82rem; color: #1e3a5f; }
    .comment-type-badge {
        font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 20px;
        text-transform: uppercase; letter-spacing: 0.4px;
    }
    .badge-note    { background: #e0e7ff; color: #3730a3; }
    .badge-info    { background: #dbeafe; color: #1d4ed8; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-alert   { background: #fee2e2; color: #991b1b; }
    .comment-time { font-size: 0.72rem; color: #94a3b8; margin-left: auto; }
    .comment-body-text { font-size: 0.88rem; color: #334155; line-height: 1.55; white-space: pre-wrap; word-break: break-word; margin: 0; }
    .comment-bubble-actions { display: flex; justify-content: flex-end; margin-top: 8px; gap: 6px; }
    .comment-delete-btn {
        background: none; border: none; color: #ef4444; font-size: 0.72rem;
        cursor: pointer; padding: 2px 6px; border-radius: 6px;
        transition: all 0.2s; opacity: 0.6;
    }
    .comment-delete-btn:hover { opacity: 1; background: #fee2e2; }
    .comment-compose-wrap { padding: 14px 20px 18px; background: #fff; border-top: 1px solid #e2e8f0; flex-shrink: 0; }
    .comment-type-row { display: flex; gap: 6px; margin-bottom: 12px; }
    .ctype-btn {
        flex: 1; border: 1.5px solid #e2e8f0; background: #f8fafc;
        color: #6474b8; font-size: 0.72rem; font-weight: 600;
        padding: 5px 4px; border-radius: 8px; cursor: pointer;
        transition: all 0.2s; display: flex; align-items: center;
        justify-content: center; gap: 4px; white-space: nowrap;
    }
    .ctype-btn.active, .ctype-btn:hover { border-color: #2563eb; background: #dbeafe; color: #1d4ed8; }
    .ctype-btn[data-type="success"].active, .ctype-btn[data-type="success"]:hover { border-color: #10b981; background: #d1fae5; color: #065f46; }
    .ctype-btn[data-type="alert"].active, .ctype-btn[data-type="alert"]:hover { border-color: #ef4444; background: #fee2e2; color: #991b1b; }
    .ctype-btn[data-type="info"].active, .ctype-btn[data-type="info"]:hover { border-color: #0ea5e9; background: #e0f2fe; color: #0369a1; }
    .comment-input-row { display: flex; gap: 10px; align-items: flex-start; }
    .comment-self-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; font-size: 0.7rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; text-transform: uppercase;
        box-shadow: 0 2px 6px rgba(99,102,241,0.3);
    }
    .comment-input-wrap {
        flex: 1; background: #f1f5f9; border-radius: 14px;
        border: 1.5px solid #e2e8f0; transition: border-color 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .comment-input-wrap:focus-within { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); background: #fff; }
    .comment-textarea {
        width: 100%; border: none; background: transparent;
        padding: 10px 14px 6px; font-size: 0.88rem; color: #1e293b;
        resize: none; outline: none; font-family: inherit; line-height: 1.5;
    }
    .comment-textarea::placeholder { color: #94a3b8; }
    .comment-input-footer { display: flex; align-items: center; justify-content: space-between; padding: 4px 10px 8px 14px; }
    .comment-char-count { font-size: 0.7rem; color: #94a3b8; }
    .comment-send-btn {
        background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff;
        border: none; border-radius: 10px; padding: 6px 16px; font-size: 0.8rem;
        font-weight: 600; cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; gap: 6px;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .comment-send-btn:hover { background: linear-gradient(135deg, #1d4ed8, #1e3a8a); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,0.35); }
    .comment-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    @media (max-width: 576px) {
        .comment-type-row { flex-wrap: wrap; }
        .ctype-btn { flex: 1 1 40%; }
    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('js/sms_counter.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    let _commentClientId   = {{ $client->id }};
    let _commentClientName = '{{ addslashes($client->name) }}';
    let _commentType       = 'note';

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
    function _esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function openCommentModal(clientId, clientName) {
        _commentClientId   = clientId;
        _commentClientName = clientName;
        $('#commentBody').val('');
        $('#commentCharCount').text('0 / 1000');
        $('#commentModalClientName').text(clientName);
        const selfName = '{{ Auth::user()->name ?? "Me" }}';
        const selfInits = selfName.split(' ').map(w=>w[0]||'').join('').toUpperCase().slice(0,2);
        $('#commentSelfAvatar').text(selfInits).css('background', _avatarGrad(selfName));
        const modal = new bootstrap.Modal(document.getElementById('commentModal'));
        modal.show();
        loadComments();
    }

    function loadComments() {
        if (!_commentClientId) return;
        $('#commentFeed').html('<div class="comment-loading"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Loading comments…</div>');
        $.ajax({
            url: `/clients/${_commentClientId}/comments`,
            type: 'GET',
            success: function(res) { renderComments(res.comments); },
            error: function() {
                $('#commentFeed').html('<div class="comment-loading text-danger"><i class="fas fa-exclamation-circle me-2"></i>Failed to load comments</div>');
            }
        });
    }

    function renderComments(comments) {
        const feed = $('#commentFeed');
        if (!comments || comments.length === 0) {
            feed.html('<div class="comment-empty"><i class="fas fa-comments"></i><p>No notes yet. Be the first to add one!</p></div>');
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
            const del   = c.is_mine ? `<button class="comment-delete-btn" onclick="deleteComment(${c.id})" title="Delete"><i class="fas fa-trash-alt"></i> Delete</button>` : '';
            html += `<div class="comment-card" id="cc-${c.id}">
                <div class="comment-avatar" style="background:${grad}">${_esc(c.author_initials)}</div>
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

    function submitComment() {
        const body = $('#commentBody').val().trim();
        if (!body) { $('#commentBody').focus(); return; }
        const btn = $('#commentSendBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Posting…');
        $.ajax({
            url: `/clients/${_commentClientId}/comments`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', body: body, type: _commentType },
            success: function(res) {
                if (res.success) {
                    $('#commentBody').val('');
                    $('#commentCharCount').text('0 / 1000');
                    loadComments();
                }
            },
            error: function(xhr) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Failed to post', showConfirmButton: false, timer: 2500 });
            },
            complete: function() { btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Post'); }
        });
    }

    function deleteComment(commentId) {
        Swal.fire({
            title: 'Delete this note?', text: 'This cannot be undone.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444',
            confirmButtonText: 'Delete', cancelButtonText: 'Cancel',
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: `/client-comments/${commentId}`, type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        $(`#cc-${commentId}`).fadeOut(300, function() { $(this).remove(); });
                    }
                },
                error: function() { Swal.fire({ toast: true, position:'top-end', icon:'error', title:'Could not delete', showConfirmButton:false, timer:2000 }); }
            });
        });
    }

    $(document).on('click', '.ctype-btn', function() {
        $('.ctype-btn').removeClass('active');
        $(this).addClass('active');
        _commentType = $(this).data('type');
    });

    $(document).on('input', '#commentBody', function() {
        $('#commentCharCount').text(`${$(this).val().length} / 1000`);
    });
    $(document).on('keydown', '#commentBody', function(e) {
        if (e.ctrlKey && e.key === 'Enter') submitComment();
    });

    function setSmsId(id) { $("#client_id").val(id); }
    function resetText() { $("#sms-body").val('').countSms('#sms-counter'); $('#template-select').val(''); }

    function sendSMS() {
        var smsText = $('#sms-body').val().trim();
        if (!smsText) { Swal.fire({ icon: 'warning', title: 'Empty Message', text: 'Please write a message before sending.' }); return; }
        Swal.fire({ title: 'Sending SMS...', text: 'Please wait…', allowOutsideClick: false, allowEscapeKey: false, showConfirmButton: false, didOpen: () => { Swal.showLoading(); } });
        $.ajax({
            type: 'POST', url: '{{ route('solo_sms') }}',
            data: { _token: '{{ csrf_token() }}', sms: smsText, client_id: $('#client_id').val() },
            success: function(data) {
                Swal.close();
                if (data === true || (data && data.success === true)) {
                    Swal.fire({ icon: 'success', title: 'SMS Sent!', showConfirmButton: false, timer: 1500 });
                    resetText(); $('#smsModal').modal('hide');
                    if (typeof reloadClientSmsLogs === 'function') { reloadClientSmsLogs(1); }
                } else { Swal.fire({ icon: 'error', title: 'Failed', text: (data && data.message) ? data.message : 'Unknown error.' }); }

            },
            error: function() { Swal.close(); Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' }); }
        });
    }

    function showQr(id, name, contact) {
        const vCard = "BEGIN:VCARD\r\nVERSION:3.0\r\n" + `FN:${name}\r\n` + `TEL;TYPE=CELL:${contact}\r\n` + "END:VCARD";
        const qrContainer = document.getElementById("qrcode");
        qrContainer.innerHTML = "";
        new QRCode(qrContainer, { text: vCard, width: 200, height: 200, correctLevel: QRCode.CorrectLevel.H });
        $('#clientName').text(name);
        const message = `Hello ${name}, your internet service. Please recharge. 01770033448 (Nagad/Bkash)`;
        $('#whatsappBtn').attr('href', `https://wa.me/${contact}?text=${encodeURIComponent(message)}`);
        const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
        qrModal.show();
    }

    function deleteClient(clientId) {
        Swal.fire({
            title: 'Are you sure?', text: "You won't be able to revert this! This requires password verification.",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, Delete Client'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#client_to_delete_id').val(clientId);
                $('#verification_password').val('');
                new bootstrap.Modal(document.getElementById('verifyPasswordModal')).show();
            }
        });
    }

    function confirmDeleteWithPassword() {
        const clientId = $('#client_to_delete_id').val();
        const password = $('#verification_password').val();
        if (!password) { Swal.fire('Error', 'Please enter your password.', 'error'); return; }
        $('#verifyPasswordModal').modal('hide');
        Swal.showLoading();
        $.ajax({
            url: `{{ url('clients') }}/${clientId}`, type: 'DELETE',
            data: { password: password },
            success: function(response) {
                Swal.hideLoading();
                if (response.status === 'success') {
                    Swal.fire('Deleted!', response.message, 'success').then(() => { window.location.href = '{{ route('clients.index') }}'; });
                } else { Swal.fire('Failed!', response.message || 'Deletion failed.', 'error'); }
            },
            error: function(xhr) {
                Swal.hideLoading();
                let errorMsg = 'Could not delete the record.';
                if (xhr.status === 401) errorMsg = 'Verification Failed: The password provided is incorrect.';
                else if (xhr.status === 419) errorMsg = 'Session expired (CSRF Token Mismatch). Please reload.';
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    }

    function addToClosedList(clientId, clientName) {
        Swal.fire({
            title: 'Close Client?', text: '"' + clientName + '" will be added to the closed clients list.',
            icon: 'question', showCancelButton: true, confirmButtonText: 'Yes, Close Client', cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (!result.isConfirmed) return;
            Swal.showLoading();
            $.ajax({
                url: '{{ url("clients") }}/' + clientId + '/close', type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.hideLoading();
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Client Closed!', text: res.message, timer: 1500, showConfirmButton: false }).then(() => {
                            window.location.href = '{{ route('clients.closed') }}';
                        });
                    } else { Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not close client' }); }
                },
                error: function() { Swal.hideLoading(); Swal.fire({ icon: 'error', title: 'Error', text: 'Server error.' }); }
            });
        });
    }

    function removeFromClosedList(clientId, clientName) {
        Swal.fire({
            title: 'Remove from Closed List?', text: '"' + clientName + '" will be removed from the closed list.',
            icon: 'question', showCancelButton: true, confirmButtonText: 'Yes, Remove', cancelButtonText: 'Cancel',
            confirmButtonColor: '#6563f1',
        }).then((result) => {
            if (!result.isConfirmed) return;
            Swal.showLoading();
            $.ajax({
                url: '{{ url("clients") }}/' + clientId + '/unclose', type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.hideLoading();
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Removed!', text: res.message, timer: 1500, showConfirmButton: false }).then(() => {
                            window.location.reload();
                        });
                    } else { Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not remove' }); }
                },
                error: function() { Swal.hideLoading(); Swal.fire({ icon: 'error', title: 'Error', text: 'Server error.' }); }
            });
        });
    }

    // Relocate modals to document.body to escape stacking contexts created by
    // .main-content (transform animation) and .client-detail-page (opacity animation)
    function _relocateModals() {
        $('#commentModal, #smsModal, #qrModal, #verifyPasswordModal').each(function () {
            if (this.parentNode !== document.body) {
                document.body.appendChild(this);
            }
        });
    }
    _relocateModals();

    // Ensure modals are in document.body every time before they open
    $('#commentModal, #smsModal, #qrModal, #verifyPasswordModal').on('show.bs.modal', function () {
        _relocateModals();
        // Force Bootstrap to place its backdrop on document.body too
        $('.modal-backdrop').each(function () {
            if (this.parentNode !== document.body) {
                document.body.appendChild(this);
            }
        });
    });

    // Initialize tooltips
    $(function() {
        $('[data-bs-toggle="tooltip"]').each(function() {
            new bootstrap.Tooltip(this);
        });
        $('#sms-body').countSms('#sms-counter');
        $('#template-select').on('change', function() {
            $('#sms-body').val(this.value).countSms('#sms-counter');
        });

        // Animate stat numbers on tab show
        $('#clientTabs').on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function() {
            animateStats();
        });
        animateStats();
    });

    function animateStats() {
        $('.stat-number[data-target]').each(function() {
            const $el = $(this);
            const target = parseFloat($el.data('target')) || 0;
            if (target === 0) { $el.text('0'); return; }
            const isCurrency = $el.data('currency');
            const isPercent  = $el.data('percent');
            let start = 0;
            const duration = 800;
            const step = target / (duration / 16);
            const timer = setInterval(() => {
                start += step;
                if (start >= target) { start = target; clearInterval(timer); }
                if (isCurrency) { $el.text('৳' + Number(start).toLocaleString('en-US', {maximumFractionDigits: 0})); }
                else if (isPercent) { $el.text(start.toFixed(1) + '%'); }
                else { $el.text(Math.floor(start)); }
            }, 16);
        });
    }
</script>
@endsection
