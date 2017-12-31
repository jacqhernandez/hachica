@extends('layouts.app')


@section('content')
<div class="panel-heading">
	<div class="row">
		<div class="col-xs-1">
			Items
		</div>
		<div class="col-xs-11">
			<a href="{{ route('items.create') }}" class="btn btn-primary pull-right" style="font-size: 10px; font-weight: 600; margin-left: 20px;"><span class="glyphicon glyphicon-plus"></span></a>
			<div class=pull-right style="width:80%">
				<form>
					<button type="submit" class="btn btn-success pull-right" style="font-size: 10px; border-bottom-left-radius: 0; border-top-left-radius: 0;"><i class="fa fa-search" aria-hidden="true"></i></button>
					{!! Form::text('search', null, array('placeholder' => 'Search Item','class' => 'form-control pull-right','id'=>'search')) !!}
				</form>
			</div>
		</div>
	</div>
	<!-- <a href="{{ route('items.create') }}" class="pull-right" id="new-item">Create New Item</a> -->
</div>
<div class="panel-body">
	<table class="table table-hover sortable"> 
		<thead>
			<tr>
				<th>Barcode</th>
				<th>Name</th>
				<th>Description</th>
				<th class="tr-number">Current Retail Price</th>
				<th class="tr-number">Current Wholesale Price</th>
				<!-- <th class="tr-number">Last Purchase Price</th> -->
				<th class="sorttable_nosort"></th><th class="sorttable_nosort"></th>
				<th class="sorttable_nosort"></th>
				<!-- <th class="sorttable_nosort"></th> -->
				<th class="sorttable_nosort"></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($items as $item)
				<tr>
					<td>{{ $item->barcode }}</td>
					<td>{{ $item->name}} </td>
					<td>{{ $item->description }}</td>
					<td class="tr-number">&#8369;{{ $item->retail_price }}</td>
					<td class="tr-number">&#8369;{{ $item->wholesale_price }}</td>
					<!-- <td class="tr-number">&#8369;{{ $item->last_purchase_price }}</td> -->
					<td></td><td></td>
					<td>
						<div id="show"><a href="{{ route('items.show', [$item->id]) }}" class="btn btn-info btn-main-info">View</a></div>
					</td>
 					<!-- <td>
						{!! Form::open(['route' => ['items.destroy', $item->id], 'method' => 'delete', 'id'=>'delete' ]) !!}
							<?php echo"
							<button id='btndelete".$item->id."' class='btn btn-danger btn-main-danger' type='button' data-toggle='modal' data-target='#confirmDelete".$item->id."'>
									Delete
		    			</button>" ?>
							<?php echo'
								<div class="modal fade" id="confirmDelete'.$item->id.'" role="dialog" aria-hidden="true">' ?>
			  				@include('includes.delete_confirm')
							<?php echo '</div>' ?>
						{!! Form::close() !!}
					</td>				 -->
					<td>
						{!! Form::open(['route' => ['items.edit', $item->id], 'method' => 'get', 'id' => 'edit' ]) !!}
						{!! Form::button('Edit', ['type' => 'submit', 'class' => 'btn btn-warning btn-main-warning']) !!}
						{!! Form::close() !!}
					</td> 
				</tr>
			@endforeach
		</tbody> 
	</table>
	<?php echo $items->render(); ?>
	<!-- <br><a href="{{ url('/items/create') }}" class="btn btn-primary">Create New Item</a> -->
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
#new-item:hover, #new-item:active, #new-item:focus, #new-item:visited{
		text-decoration: none;
}
form{
	margin-bottom: 0;
}
#delete .btn-main-danger, #edit .btn-main-warning, #show .btn-main-info{
	background-color: transparent;
	border: none;
	padding: 0;
}
#delete .btn-main-danger:hover, #edit .btn-main-warning:hover, #show .btn-main-info:hover, #delete .btn-main-danger:active, #edit .btn-main-warning:active, #show .btn-main-info:active, #delete .btn-main-danger:focus, #edit .btn-main-warning:focus, #show .btn-main-info:focus, #delete .btn-main-danger:visited, #edit .btn-main-warning:visited, #show .btn-main-info:visited {
	border: none;
	background: none;
  text-decoration: none;
  outline: none;
  box-shadow: none;
}
#delete .btn-main-danger, #delete .btn-main-danger:hover, #delete .btn-main-danger:active, #delete .btn-main-danger:focus, #delete .btn-main-danger:visited {
  color: #d9534f;
}
#edit .btn-main-warning, #edit .btn-main-warning:hover, #edit .btn-main-warning:active, #edit .btn-main-warning:focus, #edit .btn-main-warning:visited {
  color: #f0ad4e;
}
#show .btn-main-info, #show .btn-main-info:hover, #show .btn-main-info:active, #show .btn-main-info:focus, #show .btn-main-info:visited {
  color: #7da8c3;
}
#search{
	width: 50%; 
	height: 23.5px;
	margin-right:-1px;
	border-right: transparent;
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}
</style>