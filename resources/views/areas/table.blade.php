<div class="table-responsive">
    <table class="table" id="areas-table">
        <thead>
            <tr>
                <th>@lang('models/areas.fields.code')</th>
        <th>@lang('models/areas.fields.area')</th>
        <th>@lang('models/areas.fields.description')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($areas as $area)
            <tr>
                       <td>{{ $area->code }}</td>
            <td>{{ $area->area }}</td>
            <td>{{ $area->description }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['areas.destroy', $area->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('areas.show', [$area->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('areas.edit', [$area->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
