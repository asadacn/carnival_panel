<div class="table-responsive p-3">
    <table class="table table-hover modern-table align-middle w-100" id="investments-table">
        <thead class="bg-light">
            <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">@lang('models/investments.fields.type')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">@lang('models/investments.fields.purpose')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">@lang('models/investments.fields.amount')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">@lang('models/investments.fields.invested_by')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">@lang('models/investments.fields.created_at')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($investments as $investment)
            <tr>
                <td class="px-4">
                    <span class="badge" style="background-color: #e0e7ff; color: #4338ca; border-radius: 6px; padding: 6px 12px; font-weight: 600;">
                        {{ $investment->type ?: 'General' }}
                    </span>
                </td>
                <td class="text-sm fw-semibold text-dark">{{ $investment->purpose }}</td>
                <td>
                    <span class="fw-bold" style="color: #059669; font-size: 0.95rem;">
                        ৳ {{ number_format($investment->amount, 0) }}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center text-secondary me-2" style="width: 32px; height: 32px;">
                            <i class="far fa-user"></i>
                        </div>
                        <span class="text-sm fw-semibold">{{ $investment->invested_by ?: 'Anonymous' }}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="text-sm fw-semibold text-dark">{{ $investment->created_at->format('d M, Y') }}</span>
                        <span class="text-xs text-muted" style="font-size: 0.75rem;">Recorded</span>
                    </div>
                </td>
                <td class="text-center">
                    {!! Form::open(['route' => ['investments.destroy', $investment->id], 'method' => 'delete', 'class' => 'm-0']) !!}
                    <div class='btn-group action-buttons'>
                        <a href="{!! route('investments.show', [$investment->id]) !!}" class='btn btn-sm btn-light text-primary' data-bs-toggle="tooltip" title="View Details">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{!! route('investments.edit', [$investment->id]) !!}" class='btn btn-sm btn-light text-warning' data-bs-toggle="tooltip" title="Edit Record">
                            <i class="fa fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-sm btn-light text-danger', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")', 'data-bs-toggle' => 'tooltip', 'title' => 'Delete']) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<style>
    .modern-table th {
        border-bottom: 2px solid #e2e8f0 !important;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    .modern-table td {
        border-bottom: 1px solid #f1f5f9;
        padding-top: 12px;
        padding-bottom: 12px;
        vertical-align: middle;
    }
    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .action-buttons .btn {
        padding: 5px 10px;
        box-shadow: none;
        border: 1px solid transparent;
        background: transparent;
        transition: all 0.2s ease;
    }
    .action-buttons .btn:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }
    .action-buttons .btn-light.text-primary:hover { background: #eff6ff; }
    .action-buttons .btn-light.text-warning:hover { background: #fffbeb; }
    .action-buttons .btn-light.text-danger:hover { background: #fef2f2; }
    
    /* DataTables Pagination & Search Styling Tweaks */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #3b82f6 !important;
        color: white !important;
        border: none !important;
        border-radius: 6px;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px;
        border: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f1f5f9 !important;
        color: #333 !important;
        border: none !important;
    }
</style>
