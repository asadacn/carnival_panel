<div class="table-responsive">
    <table class="table" id="sMSLOGS-table">
        <thead>
            <tr>
                <th>@lang('models/sMSLOGS.fields.client_id')</th>
        <th>@lang('models/sMSLOGS.fields.contact')</th>
        <th>@lang('models/sMSLOGS.fields.sms')</th>
        <th>@lang('models/sMSLOGS.fields.time')</th>
        <th>@lang('models/sMSLOGS.fields.status')</th>
                {{-- <th colspan="3">@lang('crud.action')</th> --}}
            </tr>
        </thead>
        <tbody>
        @foreach($SMSLOGS as $sMSLOG)
            <tr>
            <td><a href="https://reportpanel.carnival.com.bd/partnercrm/user_details.php?carnivalid={{ $sMSLOG->client_id }}" target="_blank" rel="noopener noreferrer">{{ $sMSLOG->client_id }}</a></td>
            <td>{{ $sMSLOG->contact }}</td>
            <td>{{ $sMSLOG->sms }}</td>
            <td>{{ $sMSLOG->created_at }}</td>
            <td>{!! $sMSLOG->status? "<span class='badge badge-success'><i class='fa fa-check'></i></span>" :  "<span class='badge badge-danger'><i class='fa fa-ban'></i></span>" !!}</td>
                       {{-- <td class=" text-center">
                           {!! Form::open(['route' => ['sMSLOGS.destroy', $sMSLOG->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('sMSLOGS.show', [$sMSLOG->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('sMSLOGS.edit', [$sMSLOG->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                           </div>
                           {!! Form::close() !!}
                       </td> --}}
                   </tr>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
{{ $SMSLOGS->links() }}

