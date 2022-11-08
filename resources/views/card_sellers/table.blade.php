<div class="table-responsive">
    <table class="table" id="cardSellers-table">
        <thead>
            <tr>
                <th>@lang('models/cardSellers.fields.seller')</th>
        <th>@lang('models/cardSellers.fields.contact')</th>
        <th>@lang('models/cardSellers.fields.store_title')</th>
        <th>@lang('models/cardSellers.fields.address')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($cardSellers as $cardSeller)
            <tr>
                       <td>{{ $cardSeller->seller }}</td>
            <td>{{ $cardSeller->contact }}</td>
            <td>{{ $cardSeller->store_title }}</td>
            <td>{{ $cardSeller->address }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['cardSellers.destroy', $cardSeller->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('cardSellers.show', [$cardSeller->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('cardSellers.edit', [$cardSeller->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
