@extends('layouts.app')

@section('content')

<div class="panel-heading">Register</div>

<div class="panel-body">
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="row form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-1 form-label">Name</label>
            <div class="col-md-4">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="help-block">{{ $errors->first('name') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class="col-md-1 form-label">Username</label>
            <div class="col-md-4">
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                @if ($errors->has('username'))
                    <span class="help-block">{{ $errors->first('username') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-1 form-label">E-Mail Address</label>
            <div class="col-md-4">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-1 form-label">Password</label>
            <div class="col-md-4">
                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" required>
                @if ($errors->has('password'))
                    <span class="help-block">{{ $errors->first('password') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="col-md-1 form-label">Confirm Password</label>
            <div class="col-md-4">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="row form-group">
					<div class="col-md-1"></div>
				  <div class="col-md-4">
				  	<button type="submit" class="btn btn-success pull-right">Register</button>
    				<a href="{{ route('login') }}" class="btn btn-default pull-right" id="back-login">Back to Login</a>  	
					</div>
				</div>

    </form>
</div>
        
  
@endsection

<style>
.form-label{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
	color: #3097d1;
	font-size: 14px;
}
@media (min-width: 992px){
	.col-md-1{
		width: 10%!important;
	}
}
.btn.btn-success, .btn.btn-default, .btn.btn-danger{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
}
#back-login{
	margin-right: 10px;
}
</style>
