@extends('layouts.app')


@section('content')
<div class="panel-heading">Add Purchase
</div>
{!! Form::open(['route' => ['purchases.store'], 'method' => 'post', 'id' => 'form' ]) !!}
<div class="panel-body purchase-details">
	<div class="row form-group">
	  <label for="purchase-supplier" class="col-md-2 form-label">Supplier</label>
	  <div class="col-md-4">
	    <input id="purchase-supplier" type="text" class="form-control pull-right" name="purchase-supplier" required>
	  </div>
	</div>
	<div class="row form-group">
	  <label for="purchase-date" class="col-md-2 form-label">Date of Purchase</label>
	  <div class="col-md-4">
	    <input class="form-control pull-right" type="date" id="purchase-date" name="purchase-date" required>
	  </div>
	</div>
</div>
<div class="panel-body">
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
		  			<th>Price</th>
		  			<th>Quantity</th>
		  			<th>Total</th>
		  		</tr>
		  	</thead>
		  		<tbody id="purchase-items">
		  		</tbody>
		  </table>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
			Receipt:
			<br><br>
			<table id="purchase-receipt-items" class="purchase-receipt">
			</table><br>
			<table class="purchase-receipt">
				<tr style="border-top: 1px solid white;">
					<td>Total</td>
					<td><input class='form-control' id='purchase-total' type='number' name='purchase-total' readonly></td>
				</tr>
				<tr class="purchase-cash">
					<td>Cash</td>
					<td><input class='form-control' id='purchase-cash' type='number' name='purchase-cash' min='1' max='10000' step='0.01' required></td>
				</tr>
				<tr>
					<td>Change</td>
					<td><input class='form-control' id='purchase-change' type='number' name='purchase-change' id='purchase-change' min='0.25' max='10000' step='0.01' readonly></td>
				</tr>
			</table>
		</div>
		</div>
			<button type="submit" class="btn btn-success pull-right" id="create-purchase">Create Purchase</button>
		
		{!! Form::close() !!}
	</div>
</div>
@endsection

@section('script')
	<script type="text/javascript">
		$('#search').focus();
		
	  var count = 0;
	  var purchase_total = 0;
	  var purchase_change = 0;

	  function getCount(name){
			var id = (name.split('['))[1].split(']')[0];
			return id;
	  }
	  function updateLineTotal(id){
	  	purchase_item_total = parseFloat($('#purchase-item-price-'+id).val())*$('#purchase-item-quantity-'+id).val();
	   	$('#purchase-item-total-'+id).val(purchase_item_total.toFixed(2));
	  }
	  function updateReceiptItemLabel(id){
			if ($('#purchase-item-name-'+id).val() !== ""){
				if ($('#purchase-item-description-'+id).val() !== ""){
					$('#purchase-receipt-item-label-'+id).text($('#purchase-item-name-'+id).val() + " (" + $('#purchase-item-description-'+id).val() + ")");
				} else {
					$('#purchase-receipt-item-label-'+id).text($('#purchase-item-name-'+id).val());
				}
			} else {
				$('#purchase-receipt-item-label-'+id).text("---");
			}
	  }

	  function addToReceipt(count){ //only for adding rows, not changing values
  		var tr =$("<tr id='purchase-receipt-item-"+count+"'/>");
			tr.append($("<td></td>", {
				id: "purchase-receipt-item-label-"+count,
				class: "purchase-receipt-item-label",
				text: function(){
					if($('#purchase-item-name-'+count).is('input')){
						if ($('#purchase-item-name-'+count).val() !== ""){
						} else {
							$(this).text("---");
						}
					} else {
						$(this).text($('#purchase-item-name-'+count).text());
					}
				}
			})).append($("<td class='purchase-receipt-item-price-quantity'>"+$('#purchase-item-quantity-'+count).val() +" x &#8369;"+parseFloat($('#purchase-item-price-'+count).val()).toFixed(2)+"</td>"
			)).append($("<td class='purchase-receipt-item-total'>&#8369;"+$('#purchase-item-total-'+count).val()+"</td>"
			));
		  $('#purchase-receipt-items').append(tr);
	  }

	  function updateChange(purchase_total){
	  	var purchase_change = parseFloat($('#purchase-cash').val()) - purchase_total;
	  	$('#purchase-change').val(purchase_change.toFixed(2));
	  }

	  function updateReceipt(){ //recalculates purchase receipt
	   	$('#purchase-receipt-items tr').remove();
	   	purchase_total = 0;
	   	for(i=0; i<$('#purchase-items tr').length; i++){
	   		var id = ($('#purchase-items tr')[i].id).split('-')[2];
	   		purchase_total = purchase_total + parseFloat($('#purchase-item-total-'+id).val());
	   		addToReceipt(id);
	   	}
	   	$('#purchase-total').val(purchase_total.toFixed(2));  
	   	updateChange(purchase_total);
	  }
	  function changedPrice(count){
	  	$('#purchase-item-price-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateLineTotal(id);
				updateReceipt();
			});
	  }
	  function changedQuantity(count){
	  	$('#purchase-item-quantity-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateLineTotal(id);
				updateReceipt();
			});
	  }
	  function deleteItem(count){
	  	$('#purchase-item-delete-'+count).click(function(){
				var id = (this.id).split('-')[3];
				$('#purchase-item-'+id).remove();
				$('#purchase-receipt-item-'+id).remove();
				updateReceipt();
			});
	  }

	  $('#search').keypress(function(event){
	  	if(event.which == '10' || event.which == '13'){
	  		event.preventDefault();
	  	}
	  })

		$('#search').autocomplete({
			source: "{{ route('purchases.search') }}",
			select: function(key, value){
				count++;
				console.log(value);
				var tr =$("<tr id='purchase-item-"+count+"' data-item-id="+value.item.id+" />");
				tr.append($("<input hidden readonly name='purchase-items["+count+"][item-id]' value="+value.item.id+">"
			  )).append($("<td><div class='purchase-item-delete' id='purchase-item-delete-"+count+"'><i class='fa fa-remove fa-lg'></i></div></td>"
				)).append($("<td />", {
					text: value.item.name,
					id: "purchase-item-name-"+count,
					class: "purchase-item-name"
				})).append($("<td />",{
					text: value.item.description,
					id: "purchase-item-description-"+count
				})).append($("<td><input class='form-control purchase-item-input' id='purchase-item-price-"+count+"' type='number' min='1' max='9999.99' step='0.01' name='purchase-items["+count+"][price]' value='0' required></td>"
				)).append($("<td><input class='form-control purchase-item-input' id='purchase-item-quantity-"+count+"' type='number' min='1' max='100' step='1' name='purchase-items["+count+"][quantity]' value='1' required></td>"
				)).append($("<td><input class='form-control purchase-item-input purchase-item-total' id='purchase-item-total-"+count+"' type='number' step='0.01' name='purchase-items["+count+"][total]' readonly></td>"
				));
				
				$('#purchase-items').append(tr);

				updateLineTotal(count);
				purchase_total = purchase_total + parseFloat($('#purchase-item-total-'+count).val());
				$('#purchase-total').val(purchase_total.toFixed(2));
				updateChange(purchase_total);
				addToReceipt(count);

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
			var description = item.description;
			var barcode = item.barcode;
			if (!item.description){
				description = "--";
			}
			if (!item.barcode){
				barcode = "--"
			}
      return $( "<li>"
      ).append( "<div>" + item.name + " (" + description + ") - PHP" +" <div class='autocomplete-subtext pull-right'> Barcode: " + barcode + "</div></div>"
      ).appendTo( ul );
    };

    $('#add-item').click(function(){
    	count++;
    	var tr =$("<tr id='purchase-item-"+count+"'/>");
    	tr.append($("<input hidden readonly name='purchase-items["+count+"][item-id]'>"
		  )).append($("<td><div class='purchase-item-delete purchase-item-new-delete' id='purchase-item-delete-"+count+"'><i class='fa fa-remove fa-lg'></i></div></td>"
			)).append($("<td><input class='form-control purchase-item-input purchase-item-new-input' name='purchase-items["+count+"][name]' class='purchase-item-name' id='purchase-item-name-"+count+"' placeholder='Name' required></td>"
			)).append($("<td><input class='form-control purchase-item-input purchase-item-new-input' name='purchase-items["+count+"][description]' id='purchase-item-description-"+count+"' placeholder='Description'></td>"
			)).append($("<td><input class='form-control purchase-item-input ' id='purchase-item-price-"+count+"' type='number' min='1' max='9999.99' step='0.01' name='purchase-items["+count+"][price]' value='0' required></td>"
			)).append($("<td><input class='form-control purchase-item-input' id='purchase-item-quantity-"+count+"' type='number' min='1' max='100' step='1' name='purchase-items["+count+"][quantity]' value='1' required></td>"
			)).append($("<td><input class='form-control purchase-item-input purchase-item-total' id='purchase-item-total-"+count+"' type='number' step='0.01' name='purchase-items["+count+"][total]' readonly></td>"
			));
			$('#purchase-items').append(tr);

			updateLineTotal(count);
			purchase_total = purchase_total + parseFloat($('#purchase-item-total-'+count).val());
			$('#purchase-total').val(purchase_total.toFixed(2));
			updateReceipt(purchase_total);
			changedPrice(count);

			changedQuantity(count);
			deleteItem(count);

			$('#purchase-item-name-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateReceiptItemLabel(id);
			});

			$('#purchase-item-description-'+count).bind('keyup mouseup', function () {
				var id = getCount(this.name);
				updateReceiptItemLabel(id);
			});
			
    })

    $('#purchase-cash').bind('keyup mouseup', function () {
    	updateChange(parseFloat($('#purchase-total').val()));
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
	font-size: 13px;
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
#create-purchase{
	margin-right: -10px;
	margin-top: -10px;
}
.purchase-item-input,{
	border: none;
	background: transparent;
}
.purchase-item-total{
	text-align:right;
}
.purchase-receipt{
	width: 100%;
	color: white;
	font-family: 'Roboto', sans-serif;
	font-weight: 100;
	font-size: 14px;
}
.purchase-receipt>tr{
	border: transparent;
	font-size: 12px;
}
.purchase-receipt-item-total{
	padding-right: 25px;
}
#purchase-total, #purchase-cash, #purchase-change{
	display: inline;
	white-space: nowrap;
	float: left;
}
#purchase-total[readonly], #purchase-change[readonly]{
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
.purchase-item-delete{
	padding-top: 4px;
	padding-bottom: 1px;
}
.purchase-item-new-input{
	border-color: transparent!important;
	box-shadow: none!important;
  margin-left: -12px!important;
}
.purchase-item-new-input:focus{
	border: 1px solid #98cbe8!important;
	outline:0!important;
	-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
	box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
}
/*.purchase-item-new-input:focus{
	border-color:#98cbe8!important;
	outline:0!important;
	-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
	box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(152,203,232,.6)!important;
}*/
.purchase-item-new-delete{
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
#purchase-cash::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
#purchase-cash{
	text-align: right; 
	float:right;
	margin-right: 15px;
	width: 80%;
	font-size: 12px;
}
.purchase-cash{
	font-size: 12px;
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
.purchase-item-name{
	width: 20%;
}
.purchase-details{
	border-bottom: 1px solid #eee;
	margin-left: 15px;
	margin-right: 15px;
	margin-top: 10px;
	font-weight: 300;
	font-size: 12px;
}
input[type="date"]::-webkit-inner-spin-button {
    display: none;
    -webkit-appearance: none;
}
#purchase-supplier, #purchase-date{
	height: 23.5px;
	font-size: 12px;
}
</style>
