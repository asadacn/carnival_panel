@extends('layouts.app')
@section('title')
    Bulk Communications
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">Bulk Communications</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body p-0">

                                <ul class="nav nav-tabs" id="bulkCommsTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="sms-tab" data-toggle="tab" href="#bulkSms" role="tab" aria-controls="bulkSms" aria-selected="true">
                                            Bulk SMS 💬
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="voice-tab" data-toggle="tab" href="#bulkVoice" role="tab" aria-controls="bulkVoice" aria-selected="false">
                                            Bulk Voice Message 🗣️
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content p-4" id="bulkCommsTabContent">

                                    <div class="tab-pane fade show active" id="bulkSms" role="tabpanel" aria-labelledby="sms-tab">
                                        <h5 class="mb-4">Send Bulk SMS</h5>
                                        <form id="sms_form" action="{{ route('bulk_sms') }}" method="GET">
                                            @csrf

                                            <div class="form-group">
                                                <label for="sms_client_status">Select Clients Group:</label>
                                                <select name="client_status" id="sms_client_status" class="border border-secondary form-control mb-3" required>
                                                    <option value="">Select Clients Group</option>

                                                    <option value="custom">Custom Numbers</option>

                                                    <option value="expired">Expired</option>
                                                    <option value="expired_this_month">Expired This Month - {{ $expired_this_month }} Clients</option>
                                                    <option value="expired_today">Expired Today - {{ $expired_today }} Clients</option>
                                                    <option value="expiring">Expiring Tomorrow - {{ $expiring_soon }} Clients</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="sms_isp_code">Filter by ISP:</label>
                                                <select name="isp_code" id="sms_isp_code" class="border border-info form-control mb-3">
                                                    <option value="">🌐 All ISPs</option>
                                                    <option value="carnival">🎪 Carnival</option>
                                                    <option value="bijoy">⚡ Bijoy</option>
                                                    <option value="icc">📡 ICC</option>
                                                </select>
                                            </div>

                                            <div class="form-group" id="sms_custom_numbers_div" style="display:none;">
                                                <label for="sms_custom_numbers">Enter Custom Contact Numbers (One per Line):</label>
                                                <textarea name="custom_contacts" id="sms_custom_numbers" class="form-control border border-warning" rows="5"
                                                    placeholder="e.g.,
0171xxxxxxx
0181xxxxxxx
0191xxxxxxx"></textarea>
                                                <small class="form-text text-danger">Enter numbers without the '88' prefix. **Use a new line for each number.**</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="temp">Select From Template:</label>
                                                <select id="temp" class="border border-secondary form-control mb-3">
                                                    <option value="">Select From Template</option>
                                                    @foreach ($templates as $template)
                                                        <option value="{{ $template->sms_template }}">{{ $template->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="sms-body">Write Message
                                                    (<small id="sms-counter">
                                                        <span>Messages: <span class="messages">1</span></span> /
                                                        <span>Remaining: <span class="remaining">160</span></span>
                                                    </small>):
                                                </label>
                                                <textarea name="sms_body" id="sms-body" style="min-height: 140px;" class="form-control border border-success"
                                                    placeholder="Write your message here..." required></textarea>
                                            </div>

                                            <div class="modal-footer d-flex justify-content-start align-items-center p-0 pt-3 flex-wrap gap-2">
                                                <button type="button" onclick="resetText()" class="btn btn-warning m-1">Reset</button>
                                                <button type="button" onclick="previewContacts('sms')" class="btn btn-secondary m-1">
                                                    🔍 Preview Contacts
                                                </button>
                                                <span id="sms_preview_result" class="badge badge-light border" style="font-size:0.95rem;display:none;"></span>
                                                <button type="submit" onclick="Swal.showLoading();" class="btn btn-success m-1">Send Bulk SMS</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="bulkVoice" role="tabpanel" aria-labelledby="voice-tab">
                                        <h5 class="mb-4">Start New Voice Campaign (via Elit Call)</h5>
                                        <form action="{{ route('bulk_voice_campaign') }}" method="POST">
                                            @csrf

                                            <div class="form-group">
                                                <label for="voice_client_status">Select Clients Group:</label>
                                                <select name="client_status" id="voice_client_status" class="border border-secondary form-control mb-3" required>
                                                    <option value="">Select Clients Group</option>

                                                    <option value="custom">Custom Numbers</option>

                                                    <option value="expired">Expired</option>
                                                    <option value="registered">Registered</option>
                                                    <option value="expired_this_month">Expired This Month - {{ $expired_this_month }} Clients</option>
                                                    <option value="expired_today">Expired Today - {{ $expired_today }} Clients</option>
                                                    <option value="expiring">Expiring Tomorrow - {{ $expiring_soon }} Clients</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="voice_isp_code">Filter by ISP:</label>
                                                <select name="isp_code" id="voice_isp_code" class="border border-info form-control mb-3">
                                                    <option value="">🌐 All ISPs</option>
                                                    <option value="carnival">🎪 Carnival</option>
                                                    <option value="bijoy">⚡ Bijoy</option>
                                                    <option value="icc">📡 ICC</option>
                                                </select>
                                            </div>

                                            <div class="form-group" id="voice_custom_numbers_div" style="display:none;">
                                                <label for="voice_custom_numbers">Enter Custom Contact Numbers (One per Line):</label>
                                                <textarea name="custom_contacts" id="voice_custom_numbers" class="form-control border border-warning" rows="5"
                                                    placeholder="e.g.,
0171xxxxxxx
0181xxxxxxx
0191xxxxxxx"></textarea>
                                                <small class="form-text text-danger">Enter numbers without the '88' prefix. **Use a new line for each number.**</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="broadcast_id">Select Pre-recorded Voice Message:</label>
                                                <select name="broadcast_id" id="broadcast_id" class="form-control border border-secondary" required>
                                                    <option value="">Select Voice Message...</option>
                                                    <option value="1679">1679 - Carnival Being Expired (Tomorrow)</option>
                                                    <option value="1678">1678 - Carnival Expired Clients (Today)</option>
                                                </select>
                                                <small class="form-text text-muted">This selects the audio file from the Elit Call platform.</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="campaign_title">Campaign Title:</label>
                                                <input type="text" name="campaign_title" id="campaign_title" class="form-control"
                                                    placeholder="E.g., Oct 23 - Carnival Expiring Tomorrow"
                                                    value="Voice Campaign - {{ now()->format('M d, Y H:i:s') }}" required>
                                                <small class="form-text text-muted">This title will be used in logs and reports. It is made unique before submission.</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="sender">Sender (Caller ID):</label>
                                                <input type="text" name="sender" id="sender" class="form-control"
                                                    value="9610990410"
                                                    placeholder="E.g., 09613XXXXXX" required readonly>
                                                <small class="form-text text-muted">This is your dedicated Elit Call sender number.</small>
                                            </div>

                                            <div class="modal-footer d-flex justify-content-start align-items-center p-0 pt-3 flex-wrap gap-2">
                                                <button type="reset" class="btn btn-warning m-1">Reset Form</button>
                                                <button type="button" onclick="previewContacts('voice')" class="btn btn-secondary m-1">
                                                    🔍 Preview Contacts
                                                </button>
                                                <span id="voice_preview_result" class="badge badge-light border" style="font-size:0.95rem;display:none;"></span>
                                                <button type="submit" onclick="Swal.showLoading();" class="btn btn-info m-1">Start Voice Campaign</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
<script src="{{ asset('js/sms_counter.min.js') }}"></script>
<script>
    // Initialize SMS counter on load
    $(document).ready(function() {
        $('#sms-body').countSms('#sms-counter');

        // **UX Improvement for Voice Campaign Title**
        // Updates the title preview when the client group or broadcast is selected
        $('#voice_client_status, #broadcast_id').on('change', function() {
            var group = $('#voice_client_status option:selected').text();
            var broadcast = $('#broadcast_id option:selected').text();

            if ($('#voice_client_status').val() && $('#broadcast_id').val()) {
                let status = $('#voice_client_status').val();
                let groupText = status === 'custom' ? 'Custom Numbers' : group;
                // Try to get just the description part of the broadcast
                let broadcastText = broadcast.includes(' - ') ? broadcast.split(' - ')[1].replace(/\(|\)/g, '').trim() : broadcast;

                // Set a clean dynamic title (the controller will ensure it's unique and API-compliant)
                var newTitle = 'Voice - ' + groupText + ' - ' + broadcastText;
                $('#campaign_title').val(newTitle);
            }
        });

        // **Handle Custom Numbers Visibility (SMS)**
        $('#sms_client_status').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#sms_custom_numbers_div').show();
                $('#sms_custom_numbers').prop('required', true);
            } else {
                $('#sms_custom_numbers_div').hide();
                $('#sms_custom_numbers').prop('required', false).val('');
            }
        });

        // **Handle Custom Numbers Visibility (Voice)**
        $('#voice_client_status').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#voice_custom_numbers_div').show();
                $('#voice_custom_numbers').prop('required', true);
            } else {
                $('#voice_custom_numbers_div').hide();
                $('#voice_custom_numbers').prop('required', false).val('');
            }
        });
    });

    // SMS MODAL TEMPLATE SELECTION
    $('#temp').on('change', function() {
        $('#sms-body').val(this.value);
        $('#sms-body').countSms('#sms-counter');
    });

    // Reset SMS modal form
    function resetText() {
        $("#sms-body").val('').countSms('#sms-counter');
        $('#sms_client_status').val('').trigger('change'); // trigger change to hide custom field
        $('#temp').val('');
    }

    // Initialize Bootstrap tabs
    $('#bulkCommsTab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    // PREVIEW: dry-run contact count (no message sent)
    function previewContacts(tab) {
        var clientStatus, ispCode, customContacts, resultBadge;

        if (tab === 'sms') {
            clientStatus   = $('#sms_client_status').val();
            ispCode        = $('#sms_isp_code').val();
            customContacts = $('#sms_custom_numbers').val();
            resultBadge    = $('#sms_preview_result');
        } else {
            clientStatus   = $('#voice_client_status').val();
            ispCode        = $('#voice_isp_code').val();
            customContacts = $('#voice_custom_numbers').val();
            resultBadge    = $('#voice_preview_result');
        }

        if (!clientStatus) {
            Swal.fire('Select a group first', 'Please choose a clients group before previewing.', 'warning');
            return;
        }

        resultBadge.text('Loading...').removeClass().addClass('badge badge-secondary').show();

        $.ajax({
            url: '{{ route("bulk_sms.preview") }}',
            method: 'GET',
            data: {
                client_status:   clientStatus,
                isp_code:        ispCode,
                custom_contacts: customContacts,
                _token:          '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.error) {
                    resultBadge.text('Error: ' + res.error).removeClass().addClass('badge badge-danger').show();
                    return;
                }
                var label = res.isp + ' — ' + res.label;
                if (res.count === 0) {
                    resultBadge.text('⚠️ 0 contacts matched').removeClass().addClass('badge badge-warning').show();
                } else {
                    resultBadge.text('✅ ' + res.count + ' contact(s) — ' + label).removeClass().addClass('badge badge-success').show();
                }
            },
            error: function() {
                resultBadge.text('❌ Request failed').removeClass().addClass('badge badge-danger').show();
            }
        });
    }

</script>
@endsection
