@extends('layouts.app')
@section('title')
   Bulk SMS
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">Bulk SMS</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('sMSTEMPALTES.index') }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-md-6">
                       <div class="card">
                           <div class="card-body ">
                            <div class="modal-body">
                                <select class="border border-secondary form-control mb-3">
                                    <option value="">Select Clients Group</option>

                                    <option value="expired">Expired</option>
                                    <option value="registered">Registered</option>

                                </select>
                                <select id="temp" class="border border-secondary form-control mb-3">
                                    <option value="">Select From Template</option>

                                    @foreach ($templates as $template)
                                        <option value="{{ $template->sms_template }}">{{ $template->title }}</option>
                                    @endforeach

                                </select>
                                <form id="sms_form" action="">
                                    <input id="client_id" type="hidden" name="client_id">
                                    <label for="">Write Message ( <small id="sms-counter">
                                            {{-- <li>Encoding: <span class="encoding"></span></li> --}}
                                            {{-- <li>Length: <span class="length"></span></li> --}}
                                            <span>Messages: <span class="messages"></span></span> /
                                            {{-- <li>Per Message: <span class="per_message"></span></li> --}}
                                            <span>Remaining: <span class="remaining"></span></span>
                                        </small> )
                                    </label>
                                    <textarea name="sms-body" id="sms-body" style="min-height: 140px;" class="form-control border border-success"
                                        placeholder="Write your message here .."></textarea>
                                </form>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger m-1" data-dismiss="modal">Cancel</button>
                                <button type="button" onclick="resetText()" class="btn btn-warning m-1">Reset</button>
                                <button type="button" onclick="sendSMS()" class="btn btn-success m-1">Send</button>
                            </div>
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
<script src="{{ asset('js/sms_counter.min.js') }}"></script>
<script> $('#sms-body').countSms('#sms-counter')</script>


<script>

            //SMS MODAL TEMPLATE SELECTION
            $('#temp').on('change', function() {

            $('#sms-body').val(this.value)
            $('#sms-body').countSms('#sms-counter')

            //alert( this.value );
            });

</script>
@endsection

