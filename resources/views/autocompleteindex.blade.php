@extends('layouts.app')

@section('content')
	<div class="panel-heading">
		Autocomplete Test
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-9 col-md-8 no-padding-left no-padding-right">
				<div class="form-group">   
		  	{!! Form::text('search', null, array('placeholder' => 'Search Text','class' => 'form-control','id'=>'search')) !!}
			  </div>
			  <table class="table table-hover">
			  	<thead>
			  		<tr>
			  			<th>Name</th>
			  			<th>Description</th>
			  			<th>Type</th>
			  			<th>Price</th>
			  			<th>Quantity</th>
			  			<th>Total</th>
			  		</tr>
			  	</thead>
			  	{!! Form::open(['route' => ['sale.register'], 'method' => 'post', 'id' => 'form' ]) !!}
			  		<tbody id="sale-items">
			  		</tbody>
			  	{!! Form::close() !!}
			  </table>
			</div>
			<div class="col-lg-3 col-md-4">
				Total
				<input class='form-control' id='sale-total' type='number' name='sale-total' readonly>
			</div>
		</div>
	</div>
@endsection

@section('script')

		<script type="text/javascript">
	  var count = 0;
	  var sale_total = 0;
	  //$('#sale-total').val(sale_total);
		$('#search').autocomplete({
			source: "{{ route('autocomplete-search') }}",
			select: function(key, value){
				count++;
				console.log(value);
				var tr =$("<tr id='sale-item-"+count+"' data-item-id="+value.item.id+" />");
				tr.append($("<td />", {
					text: value.item.name
				})).append($("<td />",{
					text: value.item.description
				})).append($("<td> <input checked='checked' name='type"+count+"'type='radio' value='retail'> Retail <input name='type"+count+"' type='radio' value='wholesale'> Wholesale </td>"
				)).append($("<td><input class='form-control sale-item-input' id='sale-item-price-"+count+"' type='number' min='1' max='999.99' step='0.01' name='rows["+count+"][sale-item-price]' value="+value.item.retail_price+"></td>"
				)).append($("<td><input class='form-control sale-item-input' id='sale-item-quantity-"+count+"' type='number' min='1' max='100' step='1' name='rows["+count+"][sale-item-quantity]' value='1'></td>"
				)).append($("<td><input class='form-control sale-item-input' id='sale-item-total-"+count+"' type='number' name='rows["+count+"][sale-item-total]' readonly></td>"
				));
				
				$('#sale-items').append(tr);
				$('#sale-item-total-'+count).val($('#sale-item-price-'+count).val()*$('#sale-item-quantity-'+count).val());
				sale_total = sale_total + parseInt($('#sale-item-total-'+count).val());
					$('#sale-total').val(sale_total);

				$('.sale-item-input').bind('keyup mouseup', function () {
					id = this.id.substr(this.id.length-1);
			   	$('#sale-item-total-'+id).val($('#sale-item-price-'+id).val()*$('#sale-item-quantity-'+id).val());
			   	sale_total = 0;
			   	for(i=1; i<=count; i++){
			   		sale_total = sale_total + parseInt($('#sale-item-total-'+i).val());
			   	}
			   	$('#sale-total').val(sale_total);
				});		
			}
		});

	</script>
	
@endsection

<style>
.table{
	font-weight: 100;
	font-size: 14px;
}
th{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
	color: #3097d1;
}
.sale-item-input{
	border: none;
	background: transparent;
}
.form-control[readonly]{
	background-color: #3097d1!important;
	color: white!important;
}
</style>

