@extends('layouts.app')


@section('content')
<div class="panel-heading" id="panel-heading">
	<div>
		Sales <?php if (!empty($label)) { echo $label; }?>
		<span class="pull-right">	<a href="{{ route('sales.create') }}" class="btn btn-primary pull-right" style="font-size: 10px; font-weight: 600; margin-left: 20px;"><span class="glyphicon glyphicon-plus"></span></a></span>
	</div>
	<div class="pull-right " id="sales-dates">
		<form>
			<button type="submit" class="btn btn-success pull-right" style="font-size: 10px; border-bottom-left-radius: 0; border-top-left-radius: 0;"><i class="fa fa-search" aria-hidden="true"></i></button>
			<input placeholder="To" class="form-control pull-right" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="filter-date-two" name="to">
			<input placeholder="From" class="form-control pull-right" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="filter-date-one" name="from">
		</form>
	</div>
</div>
<div class="panel-body sales-details">
	<div>
		Total Sales: &#8369;{{ $total_sales }}<br>
		Total Transactions: {{ $sales->count() }}<br>
		Total Items Sold: {{ $total_items_sold }}
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
			@foreach ($sales as $sale)
				<tr>
					<td>{{ Carbon\Carbon::parse($sale->created_at)->format('F d, Y (l) h:i:s A') }}</td>
					<td class="tr-number">&#8369;{{ $sale->total_amount }}</td>
					<td></td><td></td><td></td><td></td>	
					<td>
						<div id="show"><a href="{{ route('sales.show', [$sale->id]) }}" class="btn btn-info btn-main-info">View</a></div>
					</td>
 					<!-- <td>
						{!! Form::open(['route' => ['sales.destroy', $sale->id], 'method' => 'delete', 'id'=>'delete' ]) !!}
							<?php echo"
							<button id='btndelete".$sale->id."' class='btn btn-danger btn-main-danger' type='button' data-toggle='modal' data-target='#confirmDelete".$sale->id."'>
									Delete
		    			</button>" ?>
							<?php echo'
								<div class="modal fade" id="confirmDelete'.$sale->id.'" role="dialog" aria-hidden="true">' ?>
			  				@include('includes.delete_confirm')
							<?php echo '</div>' ?>
						{!! Form::close() !!}
					</td>			 -->
				</tr>
			@endforeach
		</tbody> 
	</table>
	<?php echo $sales->render(); ?>
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
#filter-date-one, #filter-date-two{
	width: 150px;
	height: 23.5px;
}
#filter-date-two{
	margin-right:-1px;
	border-right: transparent;
	border-radius: 0;
}
#filter-date-one{
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}
input[type="date"]::-webkit-inner-spin-button {
    display: none;
    -webkit-appearance: none;
}
.label-date{
	color: #3097d1;
}
#sales-dates{
	margin-top: -22px;
}
@media (max-width: 800px){
	#sales-dates{
		margin-top: 10px;
		margin-right: -52px;
	}
	#panel-heading{
		padding-bottom: 45px;
	}
}
.sales-details{
	border-bottom: 1px solid #eee;
	margin-left: 15px;
	margin-right: 15px;
	font-weight: 300;
	font-size: 12px;
}
</style>
