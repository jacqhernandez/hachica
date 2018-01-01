@extends('layouts.app')


@section('content')
<div class="panel-heading">
	{{ $item->name }} @if (!is_null($item->description)) - {{$item->description}} @endif
</div>

<div class="panel-body item-details">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			Total Sales: &#8369;{{ $sale_items_total_amount }}<br>
			Total Transactions: {{ $sale_items->count() }}<br>
			Total Quantity Sold: {{ $sale_items_total_quantity }}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 item-prices">
			Current Retail Price: &#8369;{{ $item->retail_price}}<br>
			Current Wholesale Price: &#8369;{{ $item->wholesale_price}}<br>
			Last Purchase Price: --<!-- &#8369;{{ $item->last_purchase_price}} -->
		</div>
	</div>
	
	<div class="row" style="margin-top: 20px;">
		<div class="col-sm-6">
  		<div class="panel panel-default">
  			<div class="panel-heading">Price Trends</div>
  			<div class="panel-body">
  				<ul class="nav nav-tabs">
  					<li class="active"><a data-toggle="tab" href="#tab-retail-price-trends" class="tab-label">Retail</a></li>
    				<li><a data-toggle="tab" href="#tab-wholesale-price-trends" class="tab-label">Wholesale</a></li>
  				 </ul>
  				<div class="tab-content">
  					<div id="tab-retail-price-trends" class="tab-pane fade in active">
  						<canvas class="charts" id="retailpricetrends"></canvas>
  				 	</div>
  				 	<div id="tab-wholesale-price-trends" class="tab-pane fade">
  				 		<canvas class="charts" id="wholesalepricetrends"></canvas>
  				 	</div>
  				</div>
  			</div>
  		</div>
  	</div>
  	<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">Purchase Price Trends</div>
				<div class="panel-body">
					COMING SOON ;)
					<canvas class="charts" id="purchasepricetrends"></canvas>
				</div>
			</div>
		</div>
  </div>
</div>

<div class="panel-body">
	<table class="table table-hover sortable"> 
		<thead>
			<tr>
				<th>Transaction Date</th>
				<th>Type</th>
				<th class="tr-number">Price</th>
				<th class="tr-number">Quantity</th>
				<th class="tr-number">Total</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($sale_items as $sale_item)
			<tr>
				<td><a href="{{ route('sales.show', [$sale_item->sale_id] )}}" class="show-sale">{{ Carbon\Carbon::parse($sale_item->sale->created_at)->format('F d, Y (l) h:i:s A') }}</a></td>
				<td>{{ ucfirst($sale_item->type) }}</td>
				<td class="tr-number">&#8369;{{ $sale_item->price }}</td>
				<td class="tr-number">{{ $sale_item->quantity }}</td>
				<td class="tr-number">&#8369;{{ $sale_item->total }}</td>
			</tr>
		@endforeach
		</tbody> 
	</table>
	<?php echo $sale_items->render(); ?>
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
.item-details{
	border-bottom: 1px solid #eee;
	margin-left: 15px;
	margin-right: 15px;
	font-weight: 300;
	font-size: 12px;
}
.item-prices{
	text-align: right;
}
.show-sale:hover{
	text-decoration: none;
}
.tr-number{
	text-align: right;
}
.charts{
	padding-right: 20px;
}
.tab-label{
	font-size: 13px;
}
</style>

@section('script')
<script type="text/javascript">
	//disables toggling of chart when legend/label is clicked
	var options = {
  	legend: {
      onClick: function(event, legendItem) {}
    }
	}
	
	var retailPriceTrends = {
	  labels : <?php echo json_encode($sale_item_retail_price_changes_dates) ?>,
	  datasets : [
		  {
	      label: "Retail Prices for " + <?php echo json_encode($item->name) ?>,
	      borderColor: "#5cb85c",
	      backgroundColor: "rgba(92, 184, 92, 0.3)",
	      data : <?php echo json_encode($sale_item_retail_price_changes_prices) ?>
		  }
	  ]
	}

	Chart.Line('retailpricetrends',{
		data: retailPriceTrends,
		responsive: true,
		options : options
	});

	var wholesalePriceTrends = {
	  labels : <?php echo json_encode($sale_item_wholesale_price_changes_dates) ?>,
	  datasets : [
		  {
	      label: "Wholesale Prices for " + <?php echo json_encode($item->name) ?>,
	      borderColor: "#d9534f",
	      backgroundColor: "rgba(217, 83, 79, 0.3)",
	      data : <?php echo json_encode($sale_item_wholesale_price_changes_prices) ?>
		  }
	  ]
	}

	Chart.Line('wholesalepricetrends',{
		data: wholesalePriceTrends,
		responsive: true,
		options : options
	});

</script>
@endsection