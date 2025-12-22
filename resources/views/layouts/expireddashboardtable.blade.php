
<div class="table-responsive">
    <table class="table table-sm table-bordered" id="clients">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('models/clients.fields.name')</th>
                <th>@lang('models/clients.fields.username')</th>

                <th>@lang('models/clients.fields.contact')</th>
                <th>@lang('models/clients.fields.address')</th>
                <th>@lang('models/clients.fields.package')</th>
                <th>@lang('models/clients.fields.billing_type')</th>
                <th>@lang('models/clients.fields.comment')</th>
            </tr>
        </thead>
         <tbody>

        @foreach($clients as $client)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $client->name }}</td>
                <td>
                    @if($client->isp_code === 'bijoy')
                        {{-- Bijoy ISP এর জন্য লিংক --}}
                        <a href="https://selfcare.bijoy.net/pay/{{ $client->username }}" target="_blank" class="text-primary fw-bold">
                            {{ $client->username }}
                        </a>
                    @else
                        {{-- ডিফল্ট অথবা Carnival ISP এর জন্য লিংক --}}
                        <a href="https://reportpanel.carnival.com.bd/zonecrm/user_details.php?carnivalid={{ $client->username }}" target="_blank" class="text-primary fw-bold">
                            {{ $client->username }}
                        </a>
                    @endif
                </td>
            <td><a href="tel:{{ $client->contact }}">{{ $client->contact }}</a></td>
            <td>{{ $client->address }}</td>
            <td>{{ $client->package }}</td>
            <td class="text-capitalize">{{ $client->billing_type }}</td>
            <td class="text-capitalize">{{ $client->comment ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
