<div class="sms-table-wrap">
    <table class="table sms-log-table align-middle mb-0" id="sMSLOGS-table">
        <thead>
            <tr>
                <th>@lang('models/sMSLOGS.fields.recipient')</th>
                <th>@lang('models/sMSLOGS.fields.sms')</th>
                <th>@lang('models/sMSLOGS.labels.type_label')</th>
                <th class="text-center">@lang('models/sMSLOGS.fields.parts')</th>
                <th>@lang('models/sMSLOGS.fields.time')</th>
                <th class="text-center">@lang('models/sMSLOGS.fields.status')</th>
                <th>@lang('models/sMSLOGS.fields.sender')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($SMSLOGS as $sMSLOG)
                @php
                    $statusValue = strtolower((string) $sMSLOG->status);
                    $isSent = in_array($statusValue, ['1', 'sent', 'delivered'], true);
                    $clientReference = $sMSLOG->client_identifier ?: ($sMSLOG->client_id ?: '-');
                    $clientInitial = strtoupper(substr((string) $clientReference, 0, 1));
                    $messageType = strtolower((string) ($sMSLOG->message_type ?: 'single'));
                    $messageTypes = [
                        'single'      => ['Single', 'primary'],
                        'bulk'        => ['Bulk', 'info'],
                        'bill'        => ['Bill', 'success'],
                        'bill_payment' => ['Bill payment', 'warning'],
                        'reminder'    => ['Reminder', 'danger'],
                    ];
                    $typeLabel = $messageTypes[$messageType] ?? ['Custom', 'secondary'];
                @endphp
                <tr>
                    <td>
                        <div class="sms-recipient">
                            <div class="sms-recipient-avatar">{{ $clientInitial }}</div>
                            <div class="min-width-0">
                                @if($sMSLOG->client_id)
                                    <a class="sms-recipient-title" href="https://reportpanel.carnival.com.bd/partnercrm/user_details.php?carnivalid={{ $sMSLOG->client_id }}" target="_blank" rel="noopener noreferrer">{{ $sMSLOG->client_id }}</a>
                                @else
                                    <span class="sms-recipient-title">{{ $sMSLOG->client_identifier ?: __('models/sMSLOGS.labels.custom_recipient') }}</span>
                                @endif
                                <span class="sms-recipient-meta">{{ $sMSLOG->contact ?: $sMSLOG->client_identifier ?: '-' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="sms-message" title="{{ $sMSLOG->sms ?: '-' }}">{{ $sMSLOG->sms ?: __('models/sMSLOGS.labels.no_message') }}</div>
                    </td>
                    <td>
                        <span class="sms-type-badge bg-{{ $typeLabel[1] }}-subtle text-{{ $typeLabel[1] }} border border-{{ $typeLabel[1] }}-subtle">
                            <i class="fas @if($messageType === 'bulk') fa-bullhorn @elseif($messageType === 'reminder') fa-bell @else fa-paper-plane @endif me-1"></i>
                            {{ $typeLabel[0] }}
                        </span>
                        @if($messageType === 'bulk' && $sMSLOG->campaign)
                            <span class="sms-campaign" title="{{ $sMSLOG->campaign->title }}">{{ $sMSLOG->campaign->title }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="sms-parts">{{ $sMSLOG->sms_count ?: 1 }} {{ Str::plural('part', $sMSLOG->sms_count ?: 1) }}</span>
                        @if($sMSLOG->character_count)
                            <span class="text-muted ms-1">({{ $sMSLOG->character_count }} {{ __('models/sMSLOGS.fields.characters') }})</span>
                        @endif
                    </td>
                    <td>
                        @if($sMSLOG->created_at)
                            <span class="sms-time-primary">{{ $sMSLOG->created_at->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}</span>
                            <span class="sms-time-relative">{{ $sMSLOG->created_at->diffForHumans() }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="sms-status @if($isSent) bg-success-subtle text-success border border-success-subtle @else bg-danger-subtle text-danger border border-danger-subtle @endif" @if(!$isSent && $sMSLOG->error_message) title="{{ $sMSLOG->error_message }}" @endif>
                            <i class="fas @if($isSent) fa-check-circle @else fa-times-circle @endif me-1"></i>
                            {{ $isSent ? __('models/sMSLOGS.labels.sent') : __('models/sMSLOGS.labels.failed') }}
                        </span>
                    </td>
                    <td>
                        <span class="sms-sender">
                            <i class="fas fa-user-circle"></i>
                            {{ $sMSLOG->sender->name ?? __('models/sMSLOGS.labels.system') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="sms-empty">
                        <div class="sms-empty-icon"><i class="fas fa-inbox"></i></div>
                        <h6>@lang('models/sMSLOGS.labels.no_records')</h6>
                        <p>@lang('models/sMSLOGS.labels.no_records_description')</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($SMSLOGS->hasPages())
    <div class="sms-pagination-footer">
        <div class="sms-pagination-info">
            {{ number_format($SMSLOGS->firstItem() ?: 0) }}–{{ number_format($SMSLOGS->lastItem() ?: 0) }} of {{ number_format($SMSLOGS->total()) }} @lang('models/sMSLOGS.labels.records')
        </div>
        <div class="sms-pagination">
            {{ $SMSLOGS->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endif

