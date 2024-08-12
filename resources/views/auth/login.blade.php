@extends('auth.app')
@section('title')

@section('content')
 <!-- /.login-logo -->
 <div class="login-box-body">
    <p class="login-box-msg">Sign in</p>

    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="form-group has-feedback">
            <input type="email" class="form-control" name="email" placeholder="Email">
            <span class="fa fa-envelope-o form-control-feedback"></span>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password">
            <span class="fa fa-lock form-control-feedback"></span>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="row  d-flex justify-content-center">
            <div class="col-md-4" >
                {{-- <div class="form-checkbox">
                    <input type="checkbox" class="form-check-input" id="remember-me">
                    <label class="form-check-label" for="remember-me">Remember Me</label>
                </div> --}}
                <button type="submit" class="btn btn-primary btn-block btn-flat btn-sm">Sign In</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
    {{-- <div class="d-flex justify-content-between mt-3">
        <a href="#">I forgot my password</a>
        <a href="#">New here? Sign up</a>
    </div> --}}
</div>
<!-- /.login-box-body -->

@endsection
