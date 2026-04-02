@extends('layouts.app')
@section('title') Payment Report @endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Payment Report</h1>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Summary</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>Total Amount</h6>
                                <h4>৳ {{ number_format($totalAmount, 0) }}</h4>
                            </div>
                        </div>
                    </div>
                    @foreach($byMethod as $method => $amount)
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>{{ ucfirst($method) }}</h6>
                                <h4>৳ {{ number_format($amount, 0) }}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
