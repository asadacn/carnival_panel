<script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let table;

        $(function() {
            table = $('#closed-clients').DataTable({
                pageLength: 25,
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                searching: true,
                dom: '<"row"<"col-sm-12"tr>>' +
                     '<"row mt-2"<"col-sm-6"i><"col-sm-6 text-end"p>>',
                ajax: {
                    url: "{{ route('clients.closed') }}",
                    data: function(d) {
                        d.isp_filter   = $('#isp-filter').val();
                        d.cable_filter = window.currentCableFilter || '';
                        d.onu_filter   = window.currentOnuFilter   || '';
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        try { table.processing(false); } catch (e) {}
                        setTimeout(function() { $('.dataTables_processing').hide(); }, 50);
                        const isOffline = !navigator.onLine || xhr.status === 0;
                        const message = isOffline
                            ? 'No internet connection. Please check your network and try again.'
                            : 'Unable to load data. Server may be unreachable.';
                        $('#closed-clients tbody').html(
                            '<tr class="text-center">' +
                            '<td colspan="100" style="padding: 40px 20px; color: #64748b; font-size: 0.9rem;">' +
                            '<div style="font-size: 2.5rem; margin-bottom: 10px; opacity: 0.7;">' +
                            (isOffline ? '&#x1F4F6;' : '&#x26A0;&#xFE0F;') +
                            '</div>' +
                            '<div style="font-weight: 600; margin-bottom: 4px;">' + message + '</div>' +
                            '<button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="$(\'#closed-clients\').DataTable().ajax.reload();">Retry</button>' +
                            '</td></tr>'
                        );
                    }
                },
                columns: [
                    { data: 'DT_RowIndex',         name: 'DT_RowIndex',          searchable: false, orderable: false, className: 'th-index' },
                    { data: 'customer',            name: 'name',                 searchable: true,  orderable: true  },
                    { data: 'contact',             name: 'contact',              searchable: true,  orderable: true  },
                    { data: 'address',             name: 'address',              searchable: true,  orderable: true  },
                    { data: 'cable_status',        name: 'cable_status',         searchable: false, orderable: false, className: 'cable-cell' },
                    { data: 'cable_action',        name: 'cable_action',         searchable: false, orderable: false, className: 'cable-cell' },
                    { data: 'onu_status',          name: 'onu_status',           searchable: false, orderable: false, className: 'onu-cell'   },
                    { data: 'onu_action',          name: 'onu_action',           searchable: false, orderable: false, className: 'onu-cell'   },
                    { data: 'closed_at_formatted', name: 'closed_at_formatted',  searchable: false, orderable: true  },
                    { data: 'isp_code',            name: 'isp_code',             searchable: true,  orderable: true  },
                    { data: 'action',              name: 'action',               searchable: false, orderable: false, className: 'th-actions text-end' },
                ],
                order: [[0, 'asc']],
                initComplete: function() {
                    updateRecordCount(this.api().page.info().recordsTotal);
                },
                drawCallback: function(settings) {
                    const info = settings._iDisplayLength === -1 ? settings.fnRecordsTotal() : settings._iDisplayEnd;
                    updateRecordCount(info);
                    $('#closed-clients tbody tr').each(function() {
                        const cableHtml = $(this).find('td.cable-cell').first().html() || '';
                        const onuHtml   = $(this).find('td.onu-cell').first().html()   || '';
                        const cablePending = cableHtml.indexOf('Returned') === -1 && cableHtml.indexOf('status-pill') !== -1;
                        const onuPending   = onuHtml.indexOf('Returned')   === -1 && onuHtml.indexOf('status-pill')   !== -1;
                        if (cablePending && onuPending) {
                            $(this).addClass('row-both-pending');
                        }
                    });
                }
            });

            // refresh button
            $('#btn-refresh-page').on('click', function() {
                const $btn = $(this);
                $btn.addClass('is-spinning');
                table.ajax.reload(function() {
                    setTimeout(() => $btn.removeClass('is-spinning'), 400);
                }, false);
            });
        });

        function updateRecordCount(n) {
            const el = document.getElementById('record-count');
            if (el) el.textContent = n;
        }

        $('#custom-closed-search').on('keyup input', function() {
            table.search($(this).val()).draw();
        });

        $('#isp-filter').on('change', function() {
            $('.pro-chip').removeClass('active');
            $('.pro-chip[data-filter-type="all"]').addClass('active');
            table.draw();
        });

        window.currentCableFilter = '';
        window.currentOnuFilter   = '';

        $('.pro-chip').on('click', function() {
            const isAll = $(this).data('filter-type') === 'all';
            if (isAll) { $('#isp-filter').val(''); }
            $('.pro-chip').removeClass('active');
            $(this).addClass('active');

            const type = $(this).data('filter-type');
            const val  = $(this).data('filter-value') || '';

            window.currentCableFilter = '';
            window.currentOnuFilter   = '';

            if (type === 'cable')         window.currentCableFilter = val;
            else if (type === 'onu')      window.currentOnuFilter   = val;
            else if (type === 'both_pending') {
                window.currentCableFilter = 'not_returned';
                window.currentOnuFilter   = 'not_returned';
            }

            table.draw();
        });

        $('#reset-filters-btn').on('click', function() {
            $('#custom-closed-search').val('');
            table.search('');
            $('#isp-filter').val('');
            window.currentCableFilter = '';
            window.currentOnuFilter   = '';
            $('.pro-chip').removeClass('active');
            $('.pro-chip[data-filter-type="all"]').addClass('active');
            table.ajax.reload();
        });

        window.removeFromClosedList = function(clientId, clientName) {
            Swal.fire({
                title: 'Remove from Closed List?',
                text: '"' + clientName + '" will be restored to the active clients list.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2563eb',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;
                Swal.showLoading();
                $.ajax({
                    url: "{{ url('clients') }}/" + clientId + "/unclose",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.close();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Restored!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not restore client' });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                    }
                });
            });
        };

        window.copyClientDetails = function(clientId) {
            $.ajax({
                url: "{{ url('clients') }}/" + clientId + "/details",
                type: 'GET',
                success: function(res) {
                    if (!res || !res.success || !res.data) {
                        Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not load client details.' });
                        return;
                    }
                    const d = res.data;
                    const lines = [
                        '— CLIENT DETAILS —',
                        'Name     : ' + (d.name || '-'),
                        'Username : ' + (d.username || '-'),
                        'Contact  : ' + (d.contact || '-'),
                        'Alt Ph.  : ' + (d.secondary_contact || '-'),
                        'Email    : ' + (d.email || '-'),
                        'Address  : ' + (d.address || '-'),
                        'GPS      : ' + (d.gps_location || '-'),
                        'ISP      : ' + (d.isp || '-'),
                        'Package  : ' + (d.package || '-'),
                        '',
                        '— EQUIPMENT —',
                        'ONU Brand : ' + (d.onu_brand || '-'),
                        'ONU Serial: ' + (d.onu_serial || '-'),
                        'ONU MAC   : ' + (d.onu_mac || '-'),
                        'ONU Owner : ' + (d.onu_owner || '-'),
                        'Cable Owner: ' + (d.cable_owner || '-'),
                        '',
                        '— RETURN STATUS —',
                        'Cable    : ' + (d.cable_status || '-'),
                        'ONU      : ' + (d.onu_status || '-'),
                        'Closed   : ' + (d.closed_at || '-'),
                    ];
                    const text = lines.join('\n');

                    const showCopied = () => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied!',
                            text: 'Client details copied to clipboard. Paste it anywhere (WhatsApp, SMS, email).',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                        });
                    };

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(showCopied).catch(() => fallbackCopy(text, showCopied));
                    } else {
                        fallbackCopy(text, showCopied);
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Could not load client details.' });
                }
            });
        };

        function fallbackCopy(text, onSuccess) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity  = '0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                document.execCommand('copy');
                onSuccess && onSuccess();
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Copy failed', text: 'Please copy manually from the client profile.' });
            }
            document.body.removeChild(ta);
        }

        window.quickToggleReturn = function(clientId, type, returnStatus, clientName) {
            const isCable = type === 'cable';
            const endpoint = isCable
                ? "{{ url('clients') }}/" + clientId + "/cable-return"
                : "{{ url('clients') }}/" + clientId + "/onu-return";

            const fieldName   = isCable ? 'cable_returned' : 'onu_returned';
            const reasonField = isCable ? 'cable_return_reason' : 'onu_return_reason';
            const dateField   = isCable ? 'cable_returned_at' : 'onu_returned_at';
            const equipment   = isCable ? 'Cable' : 'ONU';

            if (returnStatus === 1 || returnStatus === '1') {
                const reason = equipment + ' marked as returned via quick toggle';
                const dateVal = new Date().toISOString().split('T')[0];
                submitQuickReturn(clientId, endpoint, fieldName, reasonField, dateField, 1, reason, dateVal);
            } else {
                Swal.fire({
                    title: 'Reason Required',
                    html: '<p class="mb-2">Why is the ' + (isCable ? 'cable' : 'ONU') + ' not being returned for</p>' +
                          '<p class="fw-bold mb-3 text-primary">"' + clientName + '"?</p>' +
                          '<textarea id="swal-reason" class="form-control" rows="3" placeholder="e.g. Client unreachable, Equipment damaged, Still pending..."></textarea>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    preConfirm: () => {
                        const r = document.getElementById('swal-reason').value;
                        if (!r || !r.trim()) {
                            Swal.showValidationMessage('Reason is required');
                            return false;
                        }
                        return r.trim();
                    }
                }).then((inputResult) => {
                    if (inputResult.isConfirmed && inputResult.value) {
                        submitQuickReturn(clientId, endpoint, fieldName, reasonField, dateField, 0, inputResult.value, null);
                    }
                });
            }
        };

        function submitQuickReturn(clientId, endpoint, fieldName, reasonField, dateField, returned, reason, dateVal) {
            const equipment = fieldName.indexOf('cable') !== -1 ? 'Cable' : 'ONU';
            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while we update the ' + equipment + ' return status.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: endpoint,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            [fieldName]: returned ? 1 : 0,
                            [reasonField]: reason || null,
                            [dateField]: dateVal || null,
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1200,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not update status' });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();
                            let msg = 'Server error. Please try again.';
                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                                const errs = xhr.responseJSON.errors;
                                msg = Object.values(errs).flat().join('<br>');
                            }
                            Swal.fire({ icon: 'error', title: 'Error', html: msg });
                        }
                    });
                }
            });
        }

        window.openCableReturnModal = function(clientId, clientName, cableReturned, cableReturnReason) {
            $('#cable_client_id').val(clientId);
            $('#cable_client_name').val(clientName);
            $('#cable_returned').val(cableReturned ? '1' : '0');
            $('#cable_return_reason').val(cableReturnReason || '');
            $('#cable_returned_at').val(new Date().toISOString().split('T')[0]);
            const modal = new bootstrap.Modal(document.getElementById('cableReturnModal'));
            modal.show();
        };

        window.saveCableReturn = function() {
            const clientId = $('#cable_client_id').val();
            const returned = $('#cable_returned').val();
            const reason   = $('#cable_return_reason').val();
            const dateVal  = $('#cable_returned_at').val();
            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('cableReturnModal'));
            modalInstance.hide();
            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while we update the cable return status.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: "{{ url('clients') }}/" + clientId + "/cable-return",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            cable_returned: returned,
                            cable_returned_at: dateVal || null,
                            cable_return_reason: reason || null,
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not update cable status' });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();
                            let msg = 'Server error. Please try again.';
                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                                const errs = xhr.responseJSON.errors;
                                msg = Object.values(errs).flat().join('<br>');
                            }
                            Swal.fire({ icon: 'error', title: 'Error', html: msg });
                        }
                    });
                }
            });
        };

        window.openOnuReturnModal = function(clientId, clientName, onuReturned, onuReturnReason) {
            $('#onu_client_id').val(clientId);
            $('#onu_client_name').val(clientName);
            $('#onu_returned').val(onuReturned ? '1' : '0');
            $('#onu_return_reason').val(onuReturnReason || '');
            $('#onu_returned_at').val(new Date().toISOString().split('T')[0]);
            const modal = new bootstrap.Modal(document.getElementById('onuReturnModal'));
            modal.show();
        };

        window.saveOnuReturn = function() {
            const clientId = $('#onu_client_id').val();
            const returned = $('#onu_returned').val();
            const reason   = $('#onu_return_reason').val();
            const dateVal  = $('#onu_returned_at').val();
            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('onuReturnModal'));
            modalInstance.hide();
            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while we update the ONU return status.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: "{{ url('clients') }}/" + clientId + "/onu-return",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            onu_returned: returned,
                            onu_returned_at: dateVal || null,
                            onu_return_reason: reason || null,
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not update ONU status' });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                        }
                    });
                }
            });
        };

        window.addToClosedList = function(clientId, clientName) {
            Swal.fire({
                title: 'Close Client?',
                text: '"' + clientName + '" will be added to the closed clients list for cable return management.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Close Client',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;
                Swal.showLoading();
                $.ajax({
                    url: "{{ url('clients') }}/" + clientId + "/close",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.hideLoading();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Client Closed!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.href = "{{ route('clients.closed') }}";
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Could not close client' });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                    }
                });
            });
        };
    </script>