<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="clientSmsTable">
        <thead class="table-light">
            <tr>
                <th style="width: 170px;">Date & Time</th>
                <th style="width: 110px;">Type</th>
                <th style="width: 140px;">Recipient</th>
                <th>Message Content</th>
                <th style="width: 120px;" class="text-center">Parts</th>
                <th style="width: 100px;" class="text-center">Status</th>
                <th style="width: 110px;">Sent By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($smsLogs as $log)
                <tr>
                    <td class="text-nowrap small">
                        <div class="fw-semibold text-dark">{{ $log->created_at ? $log->created_at->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') : '-' }}</div>
                        <span class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</span>
                    </td>
                    <td>
                        @if($log->message_type === 'bulk')
                            <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size: 0.75rem;">
                                <i class="fas fa-bullhorn me-1"></i> Bulk
                            </span>
                            @if($log->campaign)
                                <div class="small text-muted text-truncate mt-1" style="max-width: 130px;" title="{{ $log->campaign->title }}">
                                    {{ $log->campaign->title }}
                                </div>
                            @endif
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.75rem;">
                                <i class="fas fa-paper-plane me-1"></i> Single
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="font-monospace text-dark fw-medium small">{{ $log->contact }}</span>
                    </td>
                    <td>
                        <div class="text-break" style="max-width: 380px; font-size: 0.88rem; line-height: 1.45;">
                            {{ $log->sms }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.74rem;">
                            {{ $log->sms_count }} {{ Str::plural('part', $log->sms_count) }}
                            <small class="text-muted">({{ $log->character_count }} ch)</small>
                        </span>
                    </td>
                    <td class="text-center">
                        @if($log->status == '1' || $log->status === 'sent' || $log->status === 'delivered')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle me-1"></i> Sent
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 0.75rem;" title="{{ $log->error_message ?? 'Failed to send' }}">
                                <i class="fas fa-times-circle me-1"></i> Failed
                            </span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        <i class="fas fa-user-circle me-1"></i>{{ $log->sender->name ?? 'System' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-comment-slash fa-3x mb-3 text-secondary opacity-50"></i>
                            <h6 class="fw-semibold text-secondary">No SMS Records Found</h6>
                            <p class="small mb-2 text-muted">No text messages have been dispatched to this client yet.</p>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="setSmsId({{ $client->id }})" data-bs-toggle="modal" data-bs-target="#smsModal">
                                <i class="fas fa-paper-plane me-1"></i> Send First SMS
                            </button>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($smsLogs->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
        <div class="text-muted small">
            Showing {{ $smsLogs->firstItem() ?? 0 }} to {{ $smsLogs->lastItem() ?? 0 }} of {{ $smsLogs->total() }} records
        </div>
        <div class="sms-pagination-links">
            {!! $smsLogs->appends(request()->except('sms_page'))->links() !!}
        </div>
    </div>
@endif
