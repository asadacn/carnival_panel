@extends('layouts.app')
@section('title') Due Bills Dashboard @endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Due Bills Dashboard</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Total Due</h6>
                        <h3>৳ {{ number_format($totalDue, 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Unpaid Bills</h6>
                        <h3>{{ $unpaidCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Overdue Bills</h6>
                        <h3>{{ $overdueCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Paid This Month</h6>
                        <h3>৳ {{ number_format($paidThisMonth, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4>Recent Bills</h4></div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                @forelse($recentBills as $bill)
                                <tr>
                                    <td>{{ $bill->client->name ?? '-' }}</td>
                                    <td>{{ $bill->month_year }}</td>
                                    <td><span class="badge bg-info">৳ {{ number_format($bill->amount, 0) }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No bills</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4>Overdue Bills</h4></div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                @forelse($overdueBills as $bill)
                                <tr>
                                    <td>{{ $bill->client->name ?? '-' }}</td>
                                    <td>{{ $bill->month_year }}</td>
                                    <td><span class="badge bg-danger">৳ {{ number_format($bill->remaining_balance, 0) }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No overdue bills</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
