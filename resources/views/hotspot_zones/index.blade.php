@extends('layouts.app')
@section('title')
     @lang('models/hotspotZones.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/hotspotZones.plural')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('hotspotZones.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a>

                <a href="{{ route('hotspots.export') }}" class="mx-2 btn btn-primary form-btn">@lang('crud.export')<i
                    class="fas fa-file-export"></i></a>
            <a href="{{ route('hotspots.import.create') }}" class="mx-2 btn btn-primary form-btn">@lang('crud.import')<i
                    class="fas fa-file-import"></i></a>
            <a href="{{ route('hotspots.erase') }}" class="btn btn-danger form-btn">@lang('crud.erase')<i
                    class="fas fa-trash"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('hotspot_zones.table')
            </div>
       </div>
   </div>

    </section>


@endsection

@section('page_js')

<script>
            function copyText(obj)
            {
                var tmpInput = $("<input>");
                $("body").append(tmpInput);
                var tdVal = $(obj).parent().prev().text();
                tmpInput.val(tdVal).select();
                document.execCommand("copy");
                tmpInput.remove();
                Swal.fire({
                position: 'center',
                icon: 'success',
                title: tdVal +' Copied',
                showConfirmButton: false,
                timer: 1500
                })

            }

</script>
@endsection


@section('scripts')
    {{-- <script src="{{ asset('js/sms_counter.min.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#hotspotZones').DataTable({
                pageLength: 25,
                proccessing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                searching: true,
                select: true,
                dom: 'lBfrtip',
                ajax: "{{ route('hotspotZones.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'zone_id',
                        name: 'zone_id'
                    },
                    {
                        data: 'zone_title',
                        name: 'zone_title'
                    },
                    {
                        data: 'device_brand',
                        name: 'device_brand'
                    },
                    {
                        data: 'onu_brand',
                        name: 'onu_brand'
                    },
                    {
                        data: 'onu_mac',
                        name: 'onu_mac'
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
                // columnDefs: [{
                //     orderable: false,
                //     className: 'select-checkbox',
                //     targets: 0
                // }],
                // select: {
                //     style: 'multi',
                //     selector: 'td:first-child'
                // },
                //for conditional column style/format
                // rowCallback: function(row, data, index) {
                //     if (data.status == "Registered") {
                //         $("td:eq(8)", row).addClass("text-success");
                //     } else {
                //         $("td:eq(8)", row).addClass("text-danger");
                //     }
                // },

                order: [
                    [1, 'asc']
                ]
            });
        });

        $('body').on('click', '.edithotspot', function() {
            var hotspot_id = $(this).data('id');
            var url = "{{ route('hotspotZones.index') }}" + hotspot_id + '/edit';
            location.replace(url);
            $.get("{{ route('hotspotZones.index') }}" + hotspot_id + '/edit', function(data) {
                alert('ok');
            })
        });


</script>

@endsection
