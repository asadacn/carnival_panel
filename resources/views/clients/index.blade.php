@extends('layouts.app')
@section('title')
    @lang('models/clients.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/clients.plural') </h1>

            <div class="section-header-breadcrumb">
                <a href="#" id="bulk_btn" style="display: none" data-bs-toggle="modal" data-bs-target="#smsModal"
                   class="btn btn-warning form-btn mx-2">Bulk SMS <i class="fas fa-envelope"></i> <span id="bulk_count"
                    class="badge badge-success p-1"></span> </a>

                <div class="btn-group mx-2">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cogs"></i> Tools
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('clients.export') }}"><i class="fas fa-file-export me-2"></i> @lang('crud.export')</a></li>
                        <li><a class="dropdown-item" href="{{ route('clients.import.create') }}"><i class="fas fa-file-import me-2"></i> @lang('crud.import')</a></li>
                    </ul>
                </div>

                <a href="{{ route('clients.create') }}" class="btn btn-success form-btn">@lang('crud.add_new')<i
                        class="fas fa-plus"></i></a>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Active Clients</h4>
                        </div>
                        <div class="card-body">
                            {{ $ActiveClientsCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Expired Clients</h4>
                        </div>
                        <div class="card-body">
                            {{ $expiredClientsCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Free ONU</h4>
                        </div>
                        <div class="card-body">
                            {{ $freeOnuClientsCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-plug"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Cable Returned</h4>
                        </div>
                        <div class="card-body">
                            {{ $cableReturnedClientsCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
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
                        // Optimized for rendering link
                        createdCell: function (td, cellData, rowData) {
                             $(td).html(`<a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid=${rowData.username}" target="_blank">${cellData}</a>`);
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
    </script>

@endsection
