<div class="table-responsive">
    <table class="table" id="sMSTEMPALTES-table">
        <thead>
            <tr>
                <th>@lang('models/sMSTEMPALTES.fields.title')</th>
        <th>@lang('models/sMSTEMPALTES.fields.sms_template')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($sMSTEMPALTES as $sMSTEMPALTE)
            <tr>
                       <td>{{ $sMSTEMPALTE->title }}</td>
            <td>{{ $sMSTEMPALTE->sms_template }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['sMSTEMPALTES.destroy', $sMSTEMPALTE->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('sMSTEMPALTES.show', [$sMSTEMPALTE->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('sMSTEMPALTES.edit', [$sMSTEMPALTE->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
