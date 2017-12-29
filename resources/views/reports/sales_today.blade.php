@extends('layouts.app')


@section('content')
<div class="panel-heading">
	Sales for <span style="color: #3097d1;">{{ Carbon\Carbon::now()->format('F d, Y (l)') }}</span>
</div>
<div class="panel-body sales-details">
	<div>
		Total Sales: &#8369;{{ $total_sales_today }}<br>
		Total Transactions: {{ $sales_today->count() }}<br>
		Total Items Sold: {{ $total_items_sold_today }}
	</div>
</div>
<div class="panel-body">
	<table class="table table-hover sortable"> 
		<thead>
			<tr>
				<th>Transaction Date</th>
				<th class="tr-number">Total Amount</th>
				<th class="sorttable_nosort"></th>
				<th class="sorttable_nosort"></th>
				<th class="sorttable_nosort"></th><th class="sorttable_nosort"></th><th class="sorttable_nosort"></th><th class="sorttable_nosort"></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($sales_today as $sale)
				<tr>
					<td>{{ Carbon\Carbon::parse($sale->created_at)->format('F d, Y (l) h:i:s A') }}</td>
					<td class="tr-number">&#8369;{{ $sale->total_amount }}</td>
					<td></td><td></td><td></td><td></td>	
					<td>
						<div id="show"><a href="{{ route('sales.show', [$sale->id]) }}" class="btn btn-info btn-main-info">View</a></div>
					</td>
				</tr>
			@endforeach
		</tbody> 
	</table>
	<?php echo $sales_today->render(); ?>
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
</style>