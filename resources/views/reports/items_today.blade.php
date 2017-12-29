@extends('layouts.app')


@section('content')
<div class="panel-heading">
	Sales for <span style="color: #3097d1;">{{ Carbon\Carbon::now()->format('F d, Y (l)') }}</span>
</div>
<div class="panel-body sales-details">
	<div>
		Total Sales: &#8369;{{ $total_sales_today }}<br>
		Total Transactions: {{ $sales_today->count() }}<br>
		Total Items Sold: {{ $items_sold_today->count() }}<br>
	</div>
</div>
<div class="panel-body">
	<table class="table table-hover sortable"> 
		<thead>
  		<tr>
				<th>Name</th>
				<th>Description</th>
				<th class="tr-number">Current Retail Price</th>
				<th class="tr-number">Current Wholesale Price</th>
				<th class="tr-number">Quantity Sold</th>
				<th class="tr-number">Total Transactions</th>
				<th class="tr-number">Total Sales</th>
  		</tr>
  	</thead>
		<tbody>
			@foreach ($items_sold_today as $item_sold_today)
				<?php $item = App\Item::find($item_sold_today->id); ?>
				<tr>
					<td><a href="{{ route('items.show', [$item->id] )}}" class="show-item">{{ $item->name }}</a></td>
					<td>{{ $item->description }}</td>
					<td class="tr-number">&#8369;{{ $item->retail_price }}</td>
					<td class="tr-number">&#8369;{{ $item->wholesale_price }}</td>
					<td class="tr-number">{{ $item_sold_today->total_quantity }}</td>
					<td class="tr-number">{{ $item_sold_today->total_transactions }}</td>
					<td class="tr-number">&#8369;{{ $item_sold_today->total_sales }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<?php echo $items_sold_today->render(); ?>
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
.btn.btn-danger, .btn.btn-primary, .btn.btn-default, .btn.btn-warning, .btn.btn-info{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
	font-size: 13px;
}
#new-sale:hover, #new-sale:active, #new-sale:focus, #new-sale:visited{
		text-decoration: none;
}
form{
	margin-bottom: 0;
}
#delete .btn-main-danger, #show .btn-main-info{
	background-color: transparent;
	border: none;
	padding: 0;
}
#delete .btn-main-danger{
	color: #d9534f;
}
#show .btn-main-info{
	color: #7da8c3;
}
#delete .btn-main-danger:hover, #show .btn-main-info:hover, 
#delete .btn-main-danger:active, #show .btn-main-info:active, 
#delete .btn-main-danger:focus, #show .btn-main-info:focus, 
#delete .btn-main-danger:visited, #show .btn-main-info:visited {
	border: none;
	background: none;
  text-decoration: none;
  outline: none;
  box-shadow: none;
}
#delete .btn-main-danger:hover, #delete .btn-main-danger:active, #delete .btn-main-danger:focus, #delete .btn-main-danger:visited {
  color: #d9534f;
}
#show .btn-main-info:hover, #show .btn-main-info:active, #show .btn-main-info:focus, #show .btn-main-info:visited {
  color: #7da8c3;
}
.sales-details{
	border-bottom: 1px solid #eee;
	margin-left: 15px;
	margin-right: 15px;
	font-weight: 300;
	font-size: 12px;
}
.show-item:hover{
	text-decoration: none;
}
</style>