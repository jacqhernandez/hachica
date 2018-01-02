@extends('layouts.app')


@section('content')
<div class="panel-heading">
	Purchase # {{ $purchase->id }} - <span style="color: #3097d1;">{{ $purchase->supplier }}</span>
	<div class="pull-right">{{ Carbon\Carbon::parse($purchase->purchase_date)->format('F d, Y (l)') }}</div>
	<!-- <a href="{{ route('purchases.index') }}" class="pull-right" style="">Back</a> -->
</div>

<div class="panel-body">
	<div class="row equal">
		<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
		  <table class="table table-hover sortable">
		  	<thead>
		  		<tr>
						<th>Name</th>
						<th>Description</th>
						<th class="tr-number">Price</th>
						<th class="tr-number">Quantity</th>
						<th class="tr-number">Total</th>
		  		</tr>
		  	</thead>
		  		<tbody>
		  			@foreach ($purchase->purchaseItems as $purchase_item)
							<tr>
								<td><a href="{{ route('items.show', [$purchase_item->item] )}}" class="show-item">{{ $purchase_item->item->name }}</a></td>
								<td>{{ $purchase_item->item->description }}</td>
								<td class="tr-number">&#8369;{{ $purchase_item->price }}</td>
								<td class="tr-number">{{ $purchase_item->quantity }}</td>
								<td class="tr-number">&#8369;{{ $purchase_item->total }}</td>
							</tr>
						@endforeach
		  		</tbody>
		  </table>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
			Receipt:<br><br>
			<table id="purchase-receipt-items" class="purchase-receipt">
				@foreach ($purchase->purchaseItems as $purchase_item)
					<tr>
						<td class="purchase-receipt-item-label">{{ $purchase_item->item->name }}</td>
						<td class="purchase-receipt-item-price-quantity">{{ $purchase_item->quantity }} x &#8369;{{ $purchase_item->price }}</td>
						<td class="purchase-receipt-item-total">&#8369;{{ $purchase_item->total }}</td>
					</tr>
				@endforeach
			</table><br>
			<table class="purchase-receipt">
				<tr id="purchase-total">
					<td class="purchase-total">Total</td>
					<td class="pull-right purchase-numbers purchase-total"><strong>{{ $purchase->total_amount }}</strong></td>
				</tr>
				<tr style="font-size: 12px;">
					<td>Cash</td>
					<td class="pull-right purchase-numbers">{{ $purchase->payment }}</td>
				</tr>
				<tr>
					<td>Change</td>
					<td class="pull-right purchase-numbers"><strong>{{ $purchase->change }}</strong></td>
				</tr>
			</table>
		</div>
		</div>
			<a href="{{ route('purchases.index') }}" class="btn btn-success pull-right" id="create-purchase">Back to Purchases</a>
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
#new-purchase:hover, #new-purchase:active, #new-purchase:focus, #new-purchase:visited{
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
#create-purchase{
	margin-right: -10px;
	margin-top: -10px;
}
.purchase-receipt{
	width: 100%;
	color: white;
	font-family: 'Roboto', sans-serif;
	font-weight: 100;
	font-size: 14px;
}
#purchase-receipt-items tr{
	border: transparent;
	font-size: 12px;
}
.purchase-receipt-item-total, .purchase-numbers{
	padding-right: 25px;
}
#purchase-total{
	border-top: 1px solid white;
}
.purchase-total{
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
.purchase-receipt-item-label{
	padding-right: 20px;
}
.purchase-receipt-item-price-quantity{
	width: 30%;
}
.purchase-receipt-item-total{
	vertical-align: middle;
	text-align: right;
}
</style>