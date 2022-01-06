<div class="table-responsive">
    <table class="table" id="collectors-table">
        <thead>
            <tr>
                <th>@lang('models/collectors.fields.name')</th>
        <th>@lang('models/collectors.fields.fathers_name')</th>
        <th>@lang('models/collectors.fields.contact')</th>
        <th>@lang('models/collectors.fields.address')</th>
        <th>@lang('models/collectors.fields.nid')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($collectors as $collector)
            <tr>
                       <td>{{ $collector->name }}</td>
            <td>{{ $collector->fathers_name }}</td>
            <td>{{ $collector->contact }}</td>
            <td>{{ $collector->address }}</td>
            <td>{{ $collector->nid }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['collectors.destroy', $collector->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('collectors.show', [$collector->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('collectors.edit', [$collector->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
