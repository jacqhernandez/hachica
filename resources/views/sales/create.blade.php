@extends('layouts.app')


@section('content')
<div class="panel-heading">Add Sale
</div>
<div class="panel-body">

	{!! Form::open(['route' => ['sales.store'], 'method' => 'post', 'id' => 'form' ]) !!}
	<div class="row equal">
		<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 div-search">
					<div class="form-group">   
			  		{!! Form::text('search', null, array('placeholder' => 'Search Item','class' => 'form-control','id'=>'search')) !!}
				  </div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >
					<button type="button" class="btn btn-default" id="add-item">Add Item</button>
				</div>
			</div>
		  <table class="table table-hover">
		  	<thead>
		  		<tr>
		  			<th></th>
		  			<th>Name</th>
		  			<th>Description</th>
		  			<th>Type</th>
		  			<th>Price</th>
		  			<th>Quantity</th>
		  			<th>Total</th>
		  		</tr>
		  	</thead>
		  		<tbody id="sale-items">
		  		</tbody>
		  </table>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
			Receipt:
			<br><br>
			<table id="sale-receipt-items" class="sale-receipt">
			</table><br>
			<table class="sale-receipt">
				<tr style="border-top: 1px solid white;">
					<td>Total</td>
					<td><input class='form-control' id='sale-total' type='number' name='sale-total' readonly></td>
				</tr>
				<tr class="sale-cash">
					<td>Cash</td>
					<td><input class='form-control' id='sale-cash' type='number' name='sale-cash' min='1' max='10000' step='0.01' required></td>
				</tr>
				<tr>
					<td>Change</td>
					<td><input class='form-control' id='sale-change' type='number' name='sale-change' id='sale-change' min='0.25' max='10000' step='0.01' readonly></td>
				</tr>
			</table>
		</div>
		</div>
			<button type="submit" class="btn btn-success pull-right" id="create-sale">Create Sale</button>
		
		{!! Form::close() !!}
	</div>
</div>
@endsection

@section('script')
	<script type="text/javascript">
		$('#search').focus();
		
	  var count = 0;
	  var sale_total = 0;
	  var sale_change = 0;

	  function getCount(name){
			var id = (name.split('['))[1].split(']')[0];
			return id;
	  }
	  function updateLineTotal(id){
	  	sale_item_total = parseFloat($('#sale-item-price-'+id).val())*$('#sale-item-quantity-'+id).val();
	   	$('#sale-item-total-'+id).val(sale_item_total.toFixed(2));
	  }
	  function updateReceiptItemLabel(id){
			if ($('#sale-item-name-'+id).val() !== ""){
				if ($('#sale-item-description-'+id).val() !== ""){
					$('#sale-receipt-item-label-'+id).text($('#sale-item-name-'+id).val() + " (" + $('#sale-item-description-'+id).val() + ")");
				} else {
					$('#sale-receipt-item-label-'+id).text($('#sale-item-name-'+id).val());
				}
			} else {
				$('#sale-receipt-item-label-'+id).text("---");
			}
	  }

	  function addToReceipt(count){ //only for adding rows, not changing values
  		var tr =$("<tr id='sale-receipt-item-"+count+"'/>");
			tr.append($("<td></td>", {
				id: "sale-receipt-item-label-"+count,
				text: function(){
					if($('#sale-item-name-'+count).is('input')){
						if ($('#sale-item-name-'+count).val() !== ""){
							if ($('#sale-item-description-'+count).val() !== ""){
								$(this).text($('#sale-item-name-'+count).val() + " (" + $('#sale-item-description-'+count).val() + ")");
							} else {
								$(this).text($('#sale-item-name-'+count).val());
							}
						} else {
							$(this).text("---");
						}
					} else {
						$(this).text($('#sale-item-name-'+count).text() + " (" + $('#sale-item-description-'+count).text() + ")")
					}
				}
			})).append($("<td>"+$('#sale-item-quantity-'+count).val() +" x &#8369;"+parseFloat($('#sale-item-price-'+count).val()).toFixed(2)+"</td>"
			)).append($("<td class='pull-right sale-receipt-item-total'>&#8369;"+$('#sale-item-total-'+count).val()+"</td>"
			));
		  $('#sale-receipt-items').append(tr);
	  }

	  function updateChange(sale_total){
	  	var sale_change = parseFloat($('#sale-cash').val()) - sale_total;
	  	$('#sale-change').val(sale_change.toFixed(2));
	  }

	  function updateReceipt(){ //recalculates sale receipt
	   	$('#sale-receipt-items tr').remove();
	   	sale_total = 0;
	   	for(i=0; i<$('#sale-items tr').length; i++){
	   		var id = ($('#sale-items tr')[i].id).split('-')[2];
	   		sale_total = sale_total + parseFloat($('#sale-item-total-'+id).val());
	   		addToReceipt(id);
	   	}
	   	$('#sale-total').val(sale_total.toFixed(2));  
	   	updateChange(sale_total);
	  }
	  function changedPrice(count){
	  	$('#sale-item-price-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateLineTotal(id);
				updateReceipt();
			});
	  }
	  function changedQuantity(count){
	  	$('#sale-item-quantity-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateLineTotal(id);
				updateReceipt();
			});
	  }
	  function deleteItem(count){
	  	$('#sale-item-delete-'+count).click(function(){
				var id = (this.id).split('-')[3];
				$('#sale-item-'+id).remove();
				$('#sale-receipt-item-'+id).remove();
				updateReceipt();
			});
	  }

		$('#search').autocomplete({
			source: "{{ route('sales.search') }}",
			select: function(key, value){
				count++;
				console.log(value);
				var tr =$("<tr id='sale-item-"+count+"' data-item-id="+value.item.id+" />");
				tr.append($("<input hidden readonly name='sale-items["+count+"][item-id]' value="+value.item.id+">"
			  )).append($("<td><div class='sale-item-delete' id='sale-item-delete-"+count+"'><i class='fa fa-remove fa-lg'></i></div></td>"
				)).append($("<td />", {
					text: value.item.name,
					id: "sale-item-name-"+count
				})).append($("<td />",{
					text: value.item.description,
					id: "sale-item-description-"+count
				})).append($("<td class='td-radio'> <input class='sale-item-type' checked='checked' name='sale-items["+count+"][type]' type='radio' value='1'> Retail<br><input class='sale-item-type' name='sale-items["+count+"][type]' type='radio' value='2'>Wholesale </td>"
				)).append($("<td><input class='form-control sale-item-input' id='sale-item-price-"+count+"' type='number' min='1' max='999.99' step='0.01' name='sale-items["+count+"][price]' value="+value.item.retail_price+" required></td>"
				)).append($("<td><input class='form-control sale-item-input' id='sale-item-quantity-"+count+"' type='number' min='1' max='100' step='1' name='sale-items["+count+"][quantity]' value='1' required></td>"
				)).append($("<td><input class='form-control sale-item-input sale-item-total' id='sale-item-total-"+count+"' type='number' step='0.01' name='sale-items["+count+"][total]' readonly></td>"
				));
				
				$('#sale-items').append(tr);

				updateLineTotal(count);
				sale_total = sale_total + parseFloat($('#sale-item-total-'+count).val());
				$('#sale-total').val(sale_total.toFixed(2));
				updateChange(sale_total);
				addToReceipt(count);

			  $('input[name="sale-items['+count+'][type]"]').change(function(){
					var id = getCount(this.name);
			  	if(this.value == 1){
			  		$('#sale-item-price-'+id).val(value.item.retail_price);
			  	}
			  	else{
			  		$('#sale-item-price-'+id).val(value.item.wholesale_price);
			  	}
			  	updateLineTotal(id);
			  	updateReceipt();
			  });

			  changedPrice(count);
			  changedQuantity(count);
				deleteItem(count);

				$('#search').val(""); //to clear autocomplete form value on select
				return false; //to clear autocomplete form value on select
			},
			response: function(event, ui){
				//console.log(ui.content)
				for(i=0; i<ui.content.length; i++){
					if ($('#search').val() === ui.content[i].barcode){
						ui.item = ui.content[i];
						$(this).data('ui-autocomplete')._trigger('select','autocompleteselect',ui);
						$(this).autocomplete('close');
						break;
					}
				}
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) { //to show details in autocomplete results
      return $( "<li>"
      ).append( "<div>" + item.name + " (" + item.description + ") - PHP" + parseFloat(item.retail_price).toFixed(2) +" <div class='autocomplete-subtext pull-right'> Barcode: " + item.barcode + "</div></div>"
      ).appendTo( ul );
    };

    $('#add-item').click(function(){
    	count++;
    	var tr =$("<tr id='sale-item-"+count+"'/>");
    	tr.append($("<input hidden readonly name='sale-items["+count+"][item-id]'>"
		  )).append($("<td><div class='sale-item-delete sale-item-new-delete' id='sale-item-delete-"+count+"'><i class='fa fa-remove fa-lg'></i></div></td>"
			)).append($("<td><input class='form-control sale-item-input sale-item-new-input' name='sale-items["+count+"][name]' id='sale-item-name-"+count+"' placeholder='Name' required></td>"
			)).append($("<td><input class='form-control sale-item-input sale-item-new-input' name='sale-items["+count+"][description]' id='sale-item-description-"+count+"' placeholder='Description'></td>"
			)).append($("<td class='td-radio'> <input class='sale-item-type' checked='checked' name='sale-items["+count+"][type]' type='radio' value='1'> Retail<br><input class='sale-item-type' name='sale-items["+count+"][type]' type='radio' value='2'> Wholesale </td>"
			)).append($("<td><input class='form-control sale-item-input ' id='sale-item-price-"+count+"' type='number' min='1' max='999.99' step='0.01' name='sale-items["+count+"][price]' value='1' required></td>"
			)).append($("<td><input class='form-control sale-item-input' id='sale-item-quantity-"+count+"' type='number' min='1' max='100' step='1' name='sale-items["+count+"][quantity]' value='1' required></td>"
			)).append($("<td><input class='form-control sale-item-input sale-item-total' id='sale-item-total-"+count+"' type='number' step='0.01' name='sale-items["+count+"][total]' readonly></td>"
			));
			$('#sale-items').append(tr);

			updateLineTotal(count);
			sale_total = sale_total + parseFloat($('#sale-item-total-'+count).val());
			$('#sale-total').val(sale_total.toFixed(2));
			updateReceipt(sale_total);
			//addToReceipt(count);
			changedPrice(count);

			changedQuantity(count);
			deleteItem(count);

			$('#sale-item-name-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateReceiptItemLabel(id);
			});

			$('#sale-item-description-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateReceiptItemLabel(id);
			});
			
    })

    $('#sale-cash').bind('keyup mouseup', function () {
    	updateChange(parseFloat($('#sale-total').val()));
		});

	</script>
@endsection

<style>
.ui-menu-item{
	font-family: 'Roboto', sans-serif;
	font-weight: 300;
	font-size: 12px;
}
.autocomplete-subtext{
	font-weight: 100;
}
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
.table{
	font-weight: 100;
	font-size: 14px;
}
th{
	font-family: 'Raleway', sans-serif;
	font-weight: 300;
	color: #3097d1;
}
.div-search{
	padding-right: 0!important;
}
#add-item{
	width: 100%;
	text-align: center;
}
#create-sale{
	margin-right: -10px;
	margin-top: -10px;
}
.sale-item-input,{
	border: none;
	background: transparent;
}
.sale-item-total{
	text-align:right;
}
.sale-receipt{
	width: 100%;
	color: white;
	font-family: 'Roboto', sans-serif;
	font-weight: 100;
	font-size: 14px;
}
.sale-receipt>tr{
	border: transparent;
	font-size: 12px;
}
.sale-receipt-item-total{
	padding-right: 25px;
}
#sale-total, #sale-cash, #sale-change{
	display: inline;
	white-space: nowrap;
	float: left;
}
#sale-total[readonly], #sale-change[readonly]{
	background-color: transparent;
	color: white;
	border: none;
	box-shadow: none;
	text-align: right;
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
	padding-bottom: 10px;
	margin-bottom: 35px;
	color:white;
}
.sale-item-delete{
	padding-top: 4px;
	padding-bottom: 1px;
}
.sale-item-new-input{
	border-color: transparent!important;
	box-shadow: none!important;
  margin-left: -12px!important;
}
.sale-item-new-input:focus{
	border: 1px solid #98cbe8!important;
	outline:0!important;
	-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
	box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
}
/*.sale-item-new-input:focus{
	border-color:#98cbe8!important;
	outline:0!important;
	-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
	box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
}*/
.sale-item-new-delete{
	margin-top: 6px!important;
}
.fa-remove{
	color: #bf5329;
	cursor: pointer;
}
.fa-remove:hover{
	color: #aa4a24;
}
input[type=radio]{
	border:0px;
  height: 10px;
}
.td-radio{
	font-size: 12px;
	width: 15%;
}
@media (max-width: 991px){
	.col-lg-3.col-md-4.col-sm-12.col-xs-12{
		margin-left: 15px;
		margin-right: 15px;
	}
}
#sale-cash::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
#sale-cash{
	text-align: right; 
	float:right;
	margin-right: 15px;
	width: 80%;
	font-size: 12px;
}
.sale-cash{
	font-size: 12px;
}
</style>
