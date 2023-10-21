<div class="table-responsive">
    <table class="table" id="hotspotClients-table">
        <thead>
            <tr>
                <th>@lang('models/hotspotClients.fields.name')</th>
        <th>@lang('models/hotspotClients.fields.contact')</th>
        <th>@lang('models/hotspotClients.fields.cable')</th>
        <th>@lang('models/hotspotClients.fields.cable_owner')</th>
        <th>@lang('models/hotspotClients.fields.onu_mac')</th>
        <th>@lang('models/hotspotClients.fields.onu_owner')</th>
        <th>@lang('models/hotspotClients.fields.adrress')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($hotspotClients as $hotspotClient)
            <tr>
                       <td>{{ $hotspotClient->name }}</td>
            <td>{{ $hotspotClient->contact }}</td>
            <td>{{ $hotspotClient->cable }}</td>
            <td>{{ $hotspotClient->cable_owner }}</td>
            <td>{{ $hotspotClient->onu_mac }}</td>
            <td>{{ $hotspotClient->onu_owner }}</td>
            <td>{{ $hotspotClient->adrress }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['hotspotClients.destroy', $hotspotClient->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('hotspotClients.show', [$hotspotClient->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('hotspotClients.edit', [$hotspotClient->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                           </div>
                           {!! Form::close() !!}
                       </td>
                   </tr>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
