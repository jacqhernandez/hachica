@extends('layouts.app')

@section('content')
<div class="panel-heading">Edit Item
</div>
<div class="panel-body">
	{!! Form::model($item, ['method' => 'patch','route' => ['items.update', $item->id]]) !!}
	@include('items._form')
	{!! Form::close() !!}
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
</style>
