@extends('layouts.app')


@section('content')
<div class="panel-heading">
	Sale # {{ $sale->id }}
	<div class="pull-right">{{ Carbon\Carbon::parse($sale->created_at)->format('F d, Y (l) - h:i:s A') }}</div>
	<!-- <a href="{{ route('sales.index') }}" class="pull-right" style="">Back</a> -->
</div>

<div class="panel-body">
	<div class="row equal">
		<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
		  <table class="table table-hover sortable">
		  	<thead>
		  		<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Type</th>
						<th class="tr-number">Price</th>
						<th class="tr-number">Quantity</th>
						<th class="tr-number">Total</th>
						<!-- <th class="sorttable_nosort"></th>
						<th class="sorttable_nosort"></th> -->
						<!-- <th></th><th></th> -->
		  		</tr>
		  	</thead>
		  		<tbody>
		  			@foreach ($sale->saleItems as $sale_item)
							<tr>
								<td><a href="{{ route('items.show', [$sale_item->item] )}}" class="show-item">{{ $sale_item->item->name }}</a></td>
								<td>{{ $sale_item->item->description }}</td>
								<td>{{ ucfirst($sale_item->type) }}</td>
								<td class="tr-number">&#8369;{{ $sale_item->price }}</td>
								<td class="tr-number">{{ $sale_item->quantity }}</td>
								<td class="tr-number">&#8369;{{ $sale_item->total }}</td>


								<!-- <td></td><td></td> -->
								<!-- <td>
									{!! Form::open(['route' => ['sale_items.edit', $sale_item->id], 'method' => 'get', 'id' => 'edit' ]) !!}
									{!! Form::button('Edit', ['type' => 'submit', 'class' => 'btn btn-warning btn-main-warning']) !!}
									{!! Form::close() !!}
								</td> 
								<td>DELETE LINK</td> -->
							</tr>
						@endforeach
		  		</tbody>
		  </table>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
			Receipt:<br><br>
			<table id="sale-receipt-items" class="sale-receipt">
				@foreach ($sale->saleItems as $sale_item)
					<tr>
						<!-- <td>{{ $sale_item->item->name }} ({{ $sale_item->item->description }})</td> -->
						<td class="sale-receipt-item-label">{{ $sale_item->item->name }}</td>
						<td class="sale-receipt-item-price-quantity">{{ $sale_item->quantity }} x &#8369;{{ $sale_item->price }}</td>
						<td class="sale-receipt-item-total">&#8369;{{ $sale_item->total }}</td>
					</tr>
				@endforeach
			</table><br>
			<table class="sale-receipt">
				<tr id="sale-total">
					<td class="sale-total">Total</td>
					<td class="pull-right sale-numbers sale-total"><strong>{{ $sale->total_amount }}</strong></td>
				</tr>
				<tr style="font-size: 12px;">
					<td>Cash</td>
					<td class="pull-right sale-numbers">{{ $sale->payment }}</td>
				</tr>
				<tr>
					<td>Change</td>
					<td class="pull-right sale-numbers"><strong>{{ $sale->change }}</strong></td>
				</tr>
			</table>
		</div>
		</div>
			<a href="{{ route('sales.index') }}" class="btn btn-success pull-right" id="create-sale">Back to Sales</a>
	</div>
</div>

@endsection

<style>
.table{
	font-weight: 300;
	font-size: 13px;
	color: #777;
}
th{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
	color: #3097d1;
}
.tr-number{
	text-align: right;
}
.show-item:hover{
	text-decoration: none;
}
.btn.btn-danger, .btn.btn-primary, .btn.btn-default, .btn.btn-warning, .btn.btn-info, .btn.btn-success{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
}
#new-sale:hover, #new-sale:active, #new-sale:focus, #new-sale:visited{
		text-decoration: none;
}
form{
	margin-bottom: 0;
}
#delete .btn-main-danger, #edit .btn-main-warning{
	background-color: transparent;
	border: none;
	padding: 0;
}
#delete .btn-main-danger{
	color: #d9534f;
}
#edit .btn-main-warning{
	color: #f0ad4e;
}
#delete .btn-main-danger:hover, #edit .btn-main-warning:hover, 
#delete .btn-main-danger:active, #edit .btn-main-warning:active, 
#delete .btn-main-danger:focus, #edit .btn-main-warning:focus, 
#delete .btn-main-danger:visited, #edit .btn-main-warning:visited {
	border: none;
	background: none;
  text-decoration: none;
  outline: none;
  box-shadow: none;
}
#delete .btn-main-danger:hover
#delete .btn-main-danger:active 
#delete .btn-main-danger:focus
#delete .btn-main-danger:visited {
  color: #d9534f;
}
#edit .btn-main-warning:hover
#edit .btn-main-warning:active 
#edit .btn-main-warning:focus
#edit .btn-main-warning:visited {
  color: #f0ad4e;
}
#create-sale{
	margin-right: -10px;
	margin-top: -10px;
}
.sale-receipt{
	width: 100%;
	color: white;
	font-family: 'Roboto', sans-serif;
	font-weight: 100;
	font-size: 14px;
}
#sale-receipt-items tr{
	border: transparent;
	font-size: 12px;
}
.sale-receipt-item-total, .sale-numbers{
	padding-right: 25px;
}
#sale-total{
	border-top: 1px solid white;
}
.sale-total{
	padding-top: 15px;
}
.panel-body{
	margin-right: 20px;
}
.equal {
  display: flex;
  display: -webkit-flex;
  flex-wrap: wrap;
}
.col-lg-3.col-md-4.col-sm-12.col-xs-12{
	border-radius: 6px;
	background-color:#3097d1;
	padding-top: 10px;
	padding-bottom: 20px;
	margin-bottom: 35px;
	color:white;
}
.sale-receipt-item-label{
	padding-right: 20px;
}
.sale-receipt-item-price-quantity{
	width: 30%;
}
.sale-receipt-item-total{
	vertical-align: middle;
	text-align: right;
}
</style>