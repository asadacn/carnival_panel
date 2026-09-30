@php
    $prefix = $prefix ?? 'sms';
    $monthCounts = $monthCounts ?? [];
    $years = $years ?? [];
@endphp

<div class="form-group mb-3 month-filter" id="{{ $prefix }}_months_div" style="display:none;">
    <label class="mb-1">Expired Months <span class="text-danger">*</span></label>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
        <small class="text-muted mb-0">Pick the calendar month(s) of expiration to target.</small>
        <div class="btn-group btn-group-sm" role="group" aria-label="Month shortcuts">
            <button type="button" class="btn btn-outline-secondary month-select-all" data-target="{{ $prefix }}">All</button>
            <button type="button" class="btn btn-outline-secondary month-clear" data-target="{{ $prefix }}">Clear</button>
        </div>
    </div>

    <div class="border rounded p-3 bg-light mb-2">
        <div class="row">
            @foreach (range(1, 12) as $month)
                <div class="col-md-3 col-sm-4 col-6 mb-2">
                    <label class="month-chip d-flex align-items-center gap-2 mb-0">
                        <input type="checkbox" name="months[]" value="{{ $month }}"
                            class="form-check-input month-input" data-filter="{{ $prefix }}">
                        <span class="month-chip-label">
                            {{ Carbon\Carbon::create(null, $month, 1)->monthName }}
                            <span class="badge badge-light border month-chip-count">{{ $monthCounts[$month] ?? 0 }}</span>
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="border rounded p-3 bg-white mb-2">
        <small class="text-muted d-block mb-2">
            Optional: limit to specific expiration year(s).
            Leave empty to match every year. Currently matching:
            <span class="text-primary fw-bold month-filter-year-hint">Every year</span>
        </small>
        <div class="row">
            @forelse ($years as $year)
                <div class="col-md-2 col-sm-4 col-6 mb-2">
                    <label class="month-chip d-flex align-items-center gap-2 mb-0">
                        <input type="checkbox" name="years[]" value="{{ $year }}"
                            class="form-check-input year-input" data-filter="{{ $prefix }}">
                        <span class="month-chip-label">{{ $year }}</span>
                    </label>
                </div>
            @empty
                <div class="col-12"><span class="text-muted small">No expired years found.</span></div>
            @endforelse
        </div>
    </div>

    <small class="form-text text-muted">
        Selected months: <span id="{{ $prefix }}_months_summary" class="text-primary fw-bold month-selection-summary">none</span>
    </small>
</div>