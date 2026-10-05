@extends('layouts.app')
@section('title')
    @lang('models/cardSellers.plural')
@endsection

@section('page_css')
<style>
    :root {
        --cs-primary: #6777ef;
        --cs-primary-d: #5a67d8;
        --cs-accent: #3abaf4;
        --cs-success: #47c363;
        --cs-danger: #fc544b;
        --cs-warning: #ffa426;
        --cs-bg: #f4f6f9;
        --cs-border: #e9ecef;
        --cs-text: #2d3748;
        --cs-muted: #7a828a;
        --cs-radius: 10px;
        --cs-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .cs-page { padding: 20px 24px 32px; animation: csFadeUp .45s ease both; }
    @keyframes csFadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

    .cs-hero {
        background: linear-gradient(135deg, var(--cs-primary) 0%, var(--cs-accent) 100%);
        border-radius: var(--cs-radius);
        padding: 24px 28px;
        margin-bottom: 20px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 6px 22px rgba(103, 119, 239, 0.25);
        position: relative; overflow: hidden;
    }
    .cs-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(255,255,255,.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .cs-hero-text h1 { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0; letter-spacing: -.3px; }
    .cs-hero-text p { color: rgba(255,255,255,.85); margin: 4px 0 0; font-size: .88rem; }
    .cs-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; position: relative; z-index: 1; }
    .cs-hero-actions .btn { display: inline-flex; align-items: center; gap: 7px; font-weight: 600; font-size: .85rem; padding: 9px 16px; border-radius: 8px; transition: transform .18s, box-shadow .18s; }
    .cs-hero-actions .btn:hover { transform: translateY(-2px); }
    .cs-btn-white { background: #fff; color: var(--cs-primary); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .cs-btn-white:hover { color: var(--cs-primary-d); box-shadow: 0 6px 16px rgba(0,0,0,.12); }
    .cs-btn-outline { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; }
    .cs-btn-outline:hover { background: rgba(255,255,255,.28); color: #fff; }

    .cs-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px; }
    .cs-stat {
        background: #fff; border: 1px solid var(--cs-border); border-radius: var(--cs-radius);
        padding: 16px 18px; display: flex; align-items: center; gap: 13px;
        box-shadow: var(--cs-shadow); transition: transform .2s, box-shadow .2s;
    }
    .cs-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.06); }
    .cs-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .cs-stat-icon.indigo { background: rgba(103,119,239,.1); color: var(--cs-primary); }
    .cs-stat-icon.cyan   { background: rgba(58,186,244,.1);  color: var(--cs-accent); }
    .cs-stat-icon.green  { background: rgba(71,195,99,.1);   color: var(--cs-success); }
    .cs-stat-icon.orange { background: rgba(255,164,38,.12); color: var(--cs-warning); }
    .cs-stat-val { font-size: 1.55rem; font-weight: 700; color: var(--cs-text); line-height: 1; }
    .cs-stat-lbl { font-size: .78rem; color: var(--cs-muted); margin-top: 3px; }

    .cs-card { background: #fff; border: 1px solid var(--cs-border); border-radius: var(--cs-radius); box-shadow: var(--cs-shadow); overflow: hidden; }
    .cs-card-header { padding: 16px 22px; border-bottom: 1px solid var(--cs-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; background: rgba(103,119,239,.03); }
    .cs-card-title { font-size: 1rem; font-weight: 700; color: var(--cs-text); margin: 0; }

    #cardSellers-table { width: 100%; border-collapse: collapse; }
    #cardSellers-table thead tr { background: #f8fafc; border-bottom: 1px solid var(--cs-border); }
    #cardSellers-table th { padding: 12px 16px; font-size: .76rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--cs-muted); white-space: nowrap; }
    #cardSellers-table tbody tr { border-bottom: 1px solid var(--cs-border); transition: background .15s; }
    #cardSellers-table tbody tr:hover { background: rgba(103,119,239,.03); }
    #cardSellers-table td { padding: 12px 16px; font-size: .87rem; color: var(--cs-text); vertical-align: middle; }
    #cardSellers-table .btn-group .btn { padding: 4px 9px; }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--cs-border); border-radius: 8px;
        padding: .45rem .75rem; margin-left: .5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus { outline: none; border-color: var(--cs-primary); box-shadow: 0 0 0 3px rgba(103,119,239,.15); }
    .dataTables_wrapper .dataTables_length select { border: 1px solid var(--cs-border); border-radius: 8px; padding: .35rem .5rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid var(--cs-border) !important; border-radius: 6px; margin: 0 2px; color: var(--cs-muted) !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: var(--cs-primary) !important; border-color: var(--cs-primary) !important; color: #fff !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: rgba(103,119,239,.08) !important; color: var(--cs-primary) !important; }
    .dataTables_wrapper .dataTables_info { padding-top: 14px; color: var(--cs-muted); font-size: .83rem; }
    .dataTables_wrapper .dataTables_processing { color: var(--cs-primary); }

    @media (max-width: 640px) {
        .cs-hero { padding: 18px; }
        .cs-hero-text h1 { font-size: 1.2rem; }
    }
</style>
@endsection

@section('content')
<div class="cs-page">

    <div class="cs-hero">
        <div class="cs-hero-text">
            <h1><i class="fas fa-id-card me-2"></i>@lang('models/cardSellers.plural')</h1>
            <p>@lang('models/cardSellers.plural') @lang('crud.list')</p>
        </div>
        <div class="cs-hero-actions">
            <a href="{{ route('cardSellers.create') }}" class="btn cs-btn-white">
                <i class="fas fa-plus"></i> @lang('crud.add_new')
            </a>
            <a href="{{ route('cardseller.export') }}" class="btn cs-btn-outline">
                <i class="fas fa-file-export"></i> @lang('crud.export')
            </a>
            <a href="{{ route('cardseller.import.create') }}" class="btn cs-btn-outline">
                <i class="fas fa-file-import"></i> @lang('crud.import')
            </a>
        </div>
    </div>

    <div class="cs-stats">
        <div class="cs-stat">
            <div class="cs-stat-icon indigo"><i class="fas fa-layer-group"></i></div>
            <div><div class="cs-stat-val">{{ $stats['total'] }}</div><div class="cs-stat-lbl">@lang('models/cardSellers.plural')</div></div>
        </div>
        <div class="cs-stat">
            <div class="cs-stat-icon cyan"><i class="fas fa-phone-alt"></i></div>
            <div><div class="cs-stat-val">{{ $stats['withContact'] }}</div><div class="cs-stat-lbl">@lang('models/cardSellers.fields.contact')</div></div>
        </div>
        <div class="cs-stat">
            <div class="cs-stat-icon green"><i class="fas fa-store"></i></div>
            <div><div class="cs-stat-val">{{ $stats['withStore'] }}</div><div class="cs-stat-lbl">@lang('models/cardSellers.fields.store_title')</div></div>
        </div>
        <div class="cs-stat">
            <div class="cs-stat-icon orange"><i class="fas fa-calendar-plus"></i></div>
            <div><div class="cs-stat-val">{{ $stats['newThisMonth'] }}</div><div class="cs-stat-lbl">@lang('crud.add_new') ({{ \Carbon\Carbon::now('Asia/Dhaka')->format('F') }})</div></div>
        </div>
    </div>

    <div class="cs-card">
        <div class="cs-card-header">
            <span class="cs-card-title"><i class="fas fa-table me-2" style="color:var(--cs-primary)"></i>@lang('models/cardSellers.plural')</span>
        </div>
        <div class="table-responsive">
            <table class="table" id="cardSellers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('models/cardSellers.fields.seller')</th>
                        <th>@lang('models/cardSellers.fields.contact')</th>
                        <th>@lang('models/cardSellers.fields.store_title')</th>
                        <th>@lang('models/cardSellers.fields.address')</th>
                        <th>@lang('models/cardSellers.fields.created_at')</th>
                        <th>@lang('crud.action')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$(function () {
    var table = $('#cardSellers-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 20,
        order: [[1, 'asc']],
        ajax: "{{ route('cardSellers.index') }}",
        language: {
            search: '',
            searchPlaceholder: 'Search seller, contact, shop, address...',
            lengthMenu: '_MENU_ rows per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ sellers',
            infoEmpty: 'No sellers found',
            infoFiltered: '(filtered from _MAX_ total)',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' },
            emptyTable: 'No card sellers found. Add one to get started!'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '4%' },
            { data: 'name', name: 'name' },
            { data: 'contact', name: 'contact' },
            { data: 'store_title', name: 'store_title' },
            { data: 'address', name: 'address', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: '14%' }
        ]
    });

    $('.dataTables_filter input').addClass('form-control').css({'border-radius': '8px', 'padding': '0.45rem 0.75rem'});
    $('.dataTables_length select').addClass('form-select').css('border-radius', '8px');

    window.deleteCardSeller = function (id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Card seller "' + name + '" will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '{{ url('cardSellers') }}/' + id,
                type: 'POST',
                data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                success: function (res) {
                    Swal.fire('Deleted!', res.message || 'Card seller deleted.', 'success');
                    table.ajax.reload(null, false);
                },
                error: function (xhr) {
                    Swal.fire('Failed!', (xhr.responseJSON && xhr.responseJSON.message) || 'Delete failed.', 'error');
                }
            });
        });
    };

    window.copyCardSellerContact = function (contact) {
        if (!contact) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(contact).then(function () {
                Swal.fire({ icon: 'success', title: 'Copied!', text: contact, showConfirmButton: false, timer: 1200, position: 'top-end', toast: true });
            });
        } else {
            var $tmp = $('<input>');
            $('body').append($tmp);
            $tmp.val(contact).select();
            document.execCommand('copy');
            $tmp.remove();
            Swal.fire({ icon: 'success', title: 'Copied!', text: contact, showConfirmButton: false, timer: 1200, position: 'top-end', toast: true });
        }
    };
});
</script>
@endsection
