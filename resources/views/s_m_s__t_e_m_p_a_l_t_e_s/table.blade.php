@php
    $smsMeta = [];
    foreach ($sMSTEMPALTES as $template) {
        $smsMeta[$template->id] = sms_count($template->sms_template);
    }
@endphp

@if($sMSTEMPALTES->isEmpty())
    <div class="sms-template-empty">
        <div class="sms-template-empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <h6>@lang('models/sMSTEMPALTES.labels.no_templates')</h6>
        <p>@lang('models/sMSTEMPALTES.labels.no_templates_description')</p>
    </div>
@else
    <div class="sms-template-list">
        @foreach($sMSTEMPALTES as $sMSTEMPALTE)
            @php
                $meta = $smsMeta[$sMSTEMPALTE->id] ?? ['messages' => 0, 'length' => 0, 'encoding' => 'UTF16'];
                $initial = strtoupper(mb_substr($sMSTEMPALTE->title, 0, 1, 'UTF-8'));
            @endphp
            <div class="sms-template-card">
                <div class="sms-template-avatar">{{ $initial }}</div>
                <div class="sms-template-main">
                    <div class="sms-template-title-row">
                        <a href="{{ route('sMSTEMPALTES.show', $sMSTEMPALTE->id) }}" class="sms-template-title">
                            {{ $sMSTEMPALTE->title }}
                        </a>
                        <span class="sms-template-id">#{{ $sMSTEMPALTE->id }}</span>
                    </div>
                    <div class="sms-template-preview">{{ $sMSTEMPALTE->sms_template }}</div>
                    <div class="sms-template-meta">
                        <span class="sms-template-meta-item">
                            <i class="fas fa-text-width"></i> {{ $meta['length'] }} @lang('models/sMSTEMPALTES.labels.chars')
                        </span>
                        <span class="sms-template-meta-item">
                            <i class="fas fa-layer-group"></i> {{ $meta['messages'] > 0 ? $meta['messages'] : '-' }} @lang('models/sMSTEMPALTES.labels.parts')
                        </span>
                        <span class="sms-template-meta-item">
                            <i class="fas fa-clock"></i> {{ $sMSTEMPALTE->updated_at->setTimezone('Asia/Dhaka')->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                <div class="sms-template-side">
                    <div class="sms-template-updated">
                        <strong>{{ $sMSTEMPALTE->updated_at->diffForHumans() }}</strong>
                        @lang('models/sMSTEMPALTES.labels.last_updated')
                    </div>
                    <div class="sms-template-actions">
                        <a href="{{ route('sMSTEMPALTES.show', $sMSTEMPALTE->id) }}" class="sms-template-action-btn" title="@lang('crud.view')">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('sMSTEMPALTES.edit', $sMSTEMPALTE->id) }}" class="sms-template-action-btn" title="@lang('crud.edit')">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button type="button" class="sms-template-action-btn sms-template-copy-action"
                                data-copy-template="{{ $sMSTEMPALTE->sms_template }}"
                                title="@lang('models/sMSTEMPALTES.labels.copy')">
                            <i class="fas fa-copy"></i>
                        </button>
                        {!! Form::open(['route' => ['sMSTEMPALTES.destroy', $sMSTEMPALTE->id], 'method' => 'delete', 'id' => 'sms-template-delete-' . $sMSTEMPALTE->id, 'style' => 'display:inline']) !!}
                        <button type="button" class="sms-template-action-btn sms-template-delete-action"
                                data-delete-template="{{ $sMSTEMPALTE->id }}"
                                data-delete-title="{{ $sMSTEMPALTE->title }}"
                                title="@lang('crud.delete')">
                            <i class="fas fa-trash"></i>
                        </button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($sMSTEMPALTES->hasPages())
        <div class="sms-template-pagination-footer">
            <div class="sms-template-pagination-info">
                @lang('models/sMSTEMPALTES.labels.showing') {{ $sMSTEMPALTES->firstItem() }} - {{ $sMSTEMPALTES->lastItem() }} @lang('models/sMSTEMPALTES.labels.of') {{ $sMSTEMPALTES->total() }} @lang('models/sMSTEMPALTES.labels.records')
            </div>
            <div class="sms-template-pagination">
                {{ $sMSTEMPALTES->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
@endif
