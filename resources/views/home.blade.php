@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Dashboard</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="card rounded">

                                        <div class="card-body">
                                            <h2 class="h4">Total Clients</h2>
                                            <h5 class="card-title">{{$clients->count()}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card rounded">

                                        <div class="card-body">
                                            <h2 class="h4">Expiring Soon</h2>
                                            <h5 class="card-title">{{$expiring_soon}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card rounded">

                                        <div class="card-body">
                                            <h2 class="h4">Expired Today</h2>
                                            <h5 class="card-title">{{$expired_today}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card rounded">

                                        <div class="card-body">
                                            <h2 class="h4">Monthly Expired </h2>
                                            <h5 class="card-title">{{$expired_this_month}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card rounded">

                                        <div class="card-body">
                                            <h2 class="h4">SMS Avilable</h2>
                                            <h5 class="card-title">{{sms_balance()}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6"><div class="card">
                                    <div class="card-header text-success">
                                    <h3>Registered Clients - {{$registered_clients}}</h3>
                                </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                            <tr>
                                                <th>Package</th>
                                                <th>Total Clients</th>
                                                <th>Total Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                              //  dd($package)
                                            @endphp
                                            @foreach ($clients_by_package as $client)

                                            <tr>
                                                <td>{{$client->package}}</td>
                                                <td>{{$client->total}} </td>
                                                <td>{{takaFormat($client->total* $package[$client->package])}} Tk.</td>
                                            </tr>
                                                @php
                                                    $total += $client->total* $package[$client->package];
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                </div></div>
                                {{-- <div class="col-sm-3">{{$total}}</div> --}}

                            </div>

                        </div>
                        <div class="card-footer">
                            <p>
                                <a class="btn btn-primary text-uppercase" onclick="toggle()">
                                  Calculate Commission
                                </a>

                              </p>
                              <div style="display: none" id="collapseCommission">
                                <div class="card card-body">
                                    <h4>Estimated Total Value : {{$total}} Tk. / Commission: {{takaFormat($total*.4)}} Tk.</h4></div>
                                </div>
                              </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    function toggle(){
    $('#collapseCommission').toggle('fast');
    }
</script>
@endsection
