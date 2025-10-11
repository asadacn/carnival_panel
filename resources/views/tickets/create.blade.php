@extends('layouts.app')
@section('title','New Ticket')

@section('content')
<div class="section">
    <div class="section-header">
        <h3>Create Ticket</h3>
    </div>
    <div class="section-body">
        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Client</label>
                <select name="client_id" class="form-control">
                    @foreach($clients as $id=>$name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Complain Type</label>
                <select name="complain_type_id" class="form-control">
                    @foreach($types as $id=>$name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option>low</option>
                    <option selected>medium</option>
                    <option>high</option>
                </select>
            </div>
            <button class="btn btn-success">Submit</button>
        </form>
    </div>
</div>
@endsection
