@extends('layouts.app')
@section('title')
    @lang('models/clients.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/clients.plural') </h1>
            <div class="section-header-breadcrumb">
                <a id="button" href="#" class="btn btn-primary form-btn">SMS<i class="fas fa-plus"></i></a>
                <a href="{{ route('clients.create') }}" class="btn btn-primary form-btn">@lang('crud.add_new')<i
                        class="fas fa-plus"></i></a>
                <a href="{{ route('clients.export') }}" class="mx-2 btn btn-primary form-btn">@lang('crud.export')<i
                        class="fas fa-file-export"></i></a>
                <a href="{{route('clients.import.create')}}"
                    class="mx-2 btn btn-primary form-btn">@lang('crud.import')<i class="fas fa-file-import"></i></a>
                <a href="{{ route('clients.erase') }}" class="btn btn-danger form-btn">@lang('crud.erase')<i
                        class="fas fa-trash"></i></a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('clients.table')
                </div>
            </div>
        </div>

    </section>




    <!--Sms Modal -->
    <div class="modal fade" id="smsModal" tabindex="-2" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Client SMS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="custom-select mb-3">
                        <option value="">Select From Template</option>

                        @foreach ($templates as $template)
                            <option value="{{ $template->sms_template }}">{{ $template->title }}</option>
                        @endforeach

                    </select>
                    <form id="sms_form" action="">
                        <input id="client_id" type="hidden" name="client_id">
                        <label for="">Write Message ( <small id="sms-counter">
                                {{-- <li>Encoding: <span class="encoding"></span></li> --}}
                                {{-- <li>Length: <span class="length"></span></li> --}}
                                <span>Messages: <span class="messages"></span></span> /
                                {{-- <li>Per Message: <span class="per_message"></span></li> --}}
                                <span>Remaining: <span class="remaining"></span></span>
                            </small> )
                        </label>
                        <textarea name="sms-body" id="sms-body" style="min-height: 140px;" class="form-control "
                            placeholder="Write your message here .."></textarea>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="resetText()" class="btn btn-warning">Reset</button>
                    <button type="button" onclick="sendSMS()" class="btn btn-success">Send</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
@endsection



@section('scripts')
    <script src="{{ asset('js/sms_counter.min.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#clients').DataTable({
                pageLength: 10,
                proccessing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                searching: true,
                select: true,
                dom: 'lBfrtip',
                ajax: "{{ route('clients.index') }}",
                columns: [{
                        "data": null,
                        defaultContent: ''
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'username',
                        name: 'username',
                        "render": function(data, type, row, meta) {
                            if (type === 'display') {
                                data =
                                    '<a href="https://reportpanel.carnival.com.bd/partnercrm/user_details.php?carnivalid=' +
                                    row.username + '" target="_blank">' + data + '</a>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'contact',
                        name: 'contact'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'package',
                        name: 'package'
                    },
                    {
                        data: 'expiration',
                        name: 'expiration'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    },


                ],
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                order: [
                    [1, 'asc']
                ]
            });
        });

        $('body').on('click', '.editClient', function() {
            var client_id = $(this).data('id');
            var url = "{{ route('clients.index') }}" + client_id + '/edit';
            location.replace(url);
            $.get("{{ route('clients.index') }}" + client_id + '/edit', function(data) {
                alert('ok');
            })
        });


        //SMS SELECTED ROWS ON TABLE
        $('#button').click(function() {
            var table = $('#clients').DataTable();
            var selectedData = table.rows({
                selected: true
            }).data().toArray();
            $.ajax({
                type: 'GET',
                url: '{{ route('sms') }}',
                dataType: 'json',
                data: {
                    clients: selectedData
                },
                success: function(data) {

                    //             data.forEach(function(item) {
                    //     console.log(item.contact)
                    // });
                    console.log(data)
                }
            });
            //console.log(selectedData)
        });

        //  alert(table.rows('.selected').data().length + ' row(s) selected');
        // });
        //SMS MODAL TEMPLATE SELECTION
        $('select').on('change', function() {

            $('#sms-body').val(this.value)
            $('#sms-body').countSms('#sms-counter')

            //alert( this.value );
        });

        $('#sms-body').countSms('#sms-counter')

        function setSmsId(id) {
            $("#client_id").val(id);
        }

        function sendSMS() {

            $.ajax({
                type: 'GET',
                url: '{{ route('solo_sms') }}',
                dataType: 'html',
                data: {
                    client_id: $('#client_id').val(),
                    sms: $('#sms-body').val()
                },
                success: function(data) {

                    if (data == true) {
                        //    alert("SMS SENT")
                        Swal.fire({
                            icon: 'success',
                            title: 'SMS SENT',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        resetText()
                        $('#smsModal').modal('toggle')
                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'SMS SENDING FAILED!',
                            showConfirmButton: true,
                        })
                        //alert('SMS SENDING FAILED !')

                    }
                }
            });
        }

        function resetText() {
            $("#sms-body").val('').countSms('#sms-counter');
            $('select').val('');
        }
    </script>
@endsection
