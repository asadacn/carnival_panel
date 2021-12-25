@extends('layouts.app')
@section('title')
     @lang('models/clients.plural')
@endsection
@section('content')

    <div class="card col-sm-3 m-auto">
        <div class="card-header"><h3 >Verify Your Identity</h3></div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Please confirm your password before continuing.</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           placeholder="Password"
                           required autocomplete="current-password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                    @if ($errors->has('password'))
                        <span class="error invalid-feedback">{{ $errors->first('password') }}</span>
                    @endif
                </div>


                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Confirm Password</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mt-3 mb-1">
                <a href="{{ route('password.request') }}">Forgot Your Password?</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>

@endsection
