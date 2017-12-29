@extends('layouts.app')

@section('content')
<div class="panel-heading">Dashboard as of <span style="color: #3097d1;">{{ Carbon\Carbon::now()->format('F d, Y (l)') }}</span></div>
<div class="panel-body">
<!--     @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    You are logged in! -->
  <div class="row">
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading panel-heading-dashboard">
          <div class="row"> 
            <div class="col-xs-3">
               <i class="fa fa-money fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">&#8369;{{ $total_sales_today }}</div>
              <div>Total Sales Today</div>
            </div>
          </div>
        </div>
          <a href="{{ route('reports.sales_today') }}">
            <div class="panel-footer">
              <span class="pull-left">View Sales</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
              <div class="clearfix"></div>
            </div>
          </a>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-green">
        <div class="panel-heading panel-heading-dashboard">
          <div class="row">
            <div class="col-xs-3">
               <i class="fa fa-shopping-cart fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">{{ $sales_today->count() }}</div>
              <div>Total Transactions Today</div>
            </div>
          </div>
        </div>
          <a href="{{ route('reports.sales_today') }}">
              <div class="panel-footer">
                  <span class="pull-left">View Transactions</span>
                  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                  <div class="clearfix"></div>
              </div>
          </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-yellow">
        <div class="panel-heading panel-heading-dashboard">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-tags fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">{{ $total_items_sold_today }}</div>
              <div>Total Items Sold Today</div>
            </div>
          </div>
      	</div>
      	<a href="{{ route('reports.items_today')}}">
          <div class="panel-footer">
            <span class="pull-left">View Items</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
    	</div>
  	</div>
  	<div class="col-lg-3 col-md-6">
      <div class="panel panel-red">
        <div class="panel-heading panel-heading-dashboard">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-shopping-bag fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">&#8369;{{ $total_sales_month }}</div>
              <div>Total Sales This Month</div>
            </div>
          </div>
        </div>
        <a href="{{ route('reports.sales_month') }}">
          <div class="panel-footer">
            <span class="pull-left">View Sales</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div class="row">
  	<div class="col-lg-6">
  		<div class="panel panel-default">
    		<div class="panel-heading panel-heading-details">Top Sellable Items
    		</div>
    		<div class="panel-body">
    			<table class="table table-hover sortable"> 
						<thead>
							<tr>
								<th class="sorttable_nosort"></th>
								<th class="sorttable_nosort">Item</th>
								<th class="tr-number">Quantity Sold</th>
								<th class="tr-number">Total Transactions</th>
								<th class="tr-number sorttable_nosort">Total Sales</th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 0 ?>
							@foreach ($sellable_items as $sellable_item)
								<?php 
									$count++;
									$item = App\Item::find($sellable_item->id);
								?>
								<tr>
									<td>{{ $count }}</td>
									<td><a href="{{ route('items.show', [$item->id] )}}" class="show-item">{{ $item->name }} @if (!is_null($item->description)) ({{$item->description}}) @endif</a></td>
									<td class="tr-number">{{ $sellable_item->total_quantity }}</td>
									<td class="tr-number">{{ $sellable_item->total_transactions }}</td>
									<td class="tr-number">&#8369;{{ $sellable_item->total_sales }}</td>
								</tr>
							@endforeach
						</tbody> 
					</table>
					<?php echo $sellable_items->render(); ?>
    		</div>
			</div>
  	</div>
  	<div class="col-lg-6">
  		<div class="panel panel-default">
    		<div class="panel-heading panel-heading-details">Items Not Selling
    		</div>
    		<div class="panel-body">
    			<table class="table table-hover"> 
						<thead>
							<tr>
								<th class="sorttable_nosort"></th>
								<th class="sorttable_nosort">Item</th>
								<th class="tr-number">Quantity Sold</th>
								<th class="tr-number">Total Transactions</th>
								<th class="tr-number sorttable_nosort">Total Sales</th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 0 ?>
							@foreach ($items_not_selling as $item_not_selling)
								<?php 
									$count++;
									$item = App\Item::find($item_not_selling->id);
								?>
								<tr>
									<td>{{ $count }}</td>
									<td><a href="{{ route('items.show', [$item->id] )}}" class="show-item">{{ $item->name }} @if (!is_null($item->description)) ({{$item->description}}) @endif</a></td>
									@if (is_null($item_not_selling->total_quantity))
										<td class="tr-number">0</td>
										<td class="tr-number">0</td>
										<td class="tr-number">&#8369;0.00</td>
									@else
										<td class="tr-number">{{ $item_not_selling->total_quantity }}</td>
										<td class="tr-number">{{ $item_not_selling->total_transactions }}</td>
										<td class="tr-number">&#8369;{{ $item_not_selling->total_sales }}</td>
									@endif
								</tr>
							@endforeach
						</tbody> 
					</table>
					<?php echo $items_not_selling->render(); ?>
    		</div>
			</div>
  	</div>
  </div>

  <div class="row">
  	<div class="col-sm-6">
  		<div class="panel panel-default">
  			<div class="panel-heading">Sales</div>
  			<div class="panel-body">
  				<ul class="nav nav-tabs">
  					<li class="active"><a data-toggle="tab" href="#tab-chart-day-sales">Last 30 Days</a></li>
    				<li><a data-toggle="tab" href="#tab-chart-week-sales">Last 12 Weeks</a></li>
    				<li><a data-toggle="tab" href="#tab-chart-month-sales">Last 12 Months</a></li>
  				 </ul>
  				<div class="tab-content">
  					<div id="tab-chart-day-sales" class="tab-pane fade in active">
  						<canvas class="charts" id="chartdaysales"></canvas>
  						
  				 	</div>
  				 	<div id="tab-chart-week-sales" class="tab-pane fade">
  				 		<canvas class="charts" id="chartweeksales"></canvas>
  				 	</div>
  				 	<div id="tab-chart-month-sales" class="tab-pane fade">
  				 		<canvas class="charts" id="chartmonthsales"></canvas>
  				 	</div>
  				</div>
  			</div>
  		</div>
  	</div>
  	<div class="col-sm-6">
  		<div class="panel panel-default">
  			<div class="panel-heading">Transactions</div>
  			<div class="panel-body">
  				<ul class="nav nav-tabs">
  					<li class="active"><a data-toggle="tab" href="#tab-chart-day-transactions">Last 30 Days</a></li>
    				<li><a data-toggle="tab" href="#tab-chart-week-transactions">Last 12 Weeks</a></li>
    				<li><a data-toggle="tab" href="#tab-chart-month-transactions">Last 12 Months</a></li>
  				 </ul>
  				<div class="tab-content">
  					<div id="tab-chart-day-transactions" class="tab-pane fade in active">
  						<canvas class="charts" id="chartdaytransactions"></canvas>
  						
  				 	</div>
  				 	<div id="tab-chart-week-transactions" class="tab-pane fade">
  				 		<canvas class="charts" id="chartweektransactions"></canvas>
  				 	</div>
  				 	<div id="tab-chart-month-transactions" class="tab-pane fade">
  				 		<canvas class="charts" id="chartmonthtransactions"></canvas>
  				 	</div>
  				</div>
  			</div>
  		</div>
  	</div>
  </div>


</div>


@endsection

<style>
.panel-body, .panel-heading{
	font-family: 'Roboto';
	font-size: 13px;
	font-weight: 300;
}
.panel-heading.panel-heading-dashboard{
	font-family: 'Roboto', sans-serif;
}
.huge{
	font-size: 30px;
	font-weight: 100;
}
.panel.panel-green {
    border-color: #5cb85c;
}
.panel-green .panel-heading {
    border-color: #5cb85c;
    color: #fff;
    background-color: #5cb85c;
}
.panel-green a {
    color: #5cb85c;
}
.panel-green a:hover {
    color: #3d8b3d;
}
.panel.panel-yellow {
    border-color: #f0ad4e;
}
.panel-yellow .panel-heading {
    border-color: #f0ad4e;
    color: #fff;
    background-color: #f0ad4e;
}
.panel-yellow a {
    color: #f0ad4e;
}
.panel-yellow a:hover {
    color: #df8a13;
}
.panel.panel-red {
    border-color: #d9534f;
}
.panel-red .panel-heading {
    border-color: #d9534f;
    color: #fff;
    background-color: #d9534f;
}
.panel-red a {
    color: #d9534f;
}
.panel-red a:hover {
    color: #b52b27;
}
.panel-default>.panel-heading.panel-heading-details{
	background-color:#f5f8fa;
}
.table{
	font-weight: 300;
	font-size: 12px;
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
.charts{
	padding-right: 20px;
}
</style>

@section('script')
<script type="text/javascript">
	var last_thirty_days = [moment().format('MMM D')];
	for (i = 1; i < 30; i++){
		last_thirty_days.unshift(moment().subtract(i,'days').format('MMM D'));
	}

	//disables toggling of chart when legend/label is clicked
	var options = {
  	legend: {
      onClick: function(event, legendItem) {}
    }
	}
	
	var chartDayTransactions = {
	  labels : last_thirty_days,
	  datasets : [
		  {
	      label: "Total Transactions for the Last 30 Days",
	      borderColor: "#5cb85c",
	      backgroundColor: "rgba(92, 184, 92, 0.3)",
	      data : <?php echo json_encode($transactions_last_thirty_days) ?>
		  }
	  ]
	}

	Chart.Line('chartdaytransactions',{
		data: chartDayTransactions,
		responsive: true,
		options : options
	});

	var chartDaySales = {
	  labels : last_thirty_days,
	  datasets : [
		  {
	      label: "Total Sales for the Last 30 Days",
	      borderColor: "#5cb85c",
	      backgroundColor: "rgba(92, 184, 92, 0.3)",
	      data : <?php echo json_encode($sales_last_thirty_days) ?>
		  }
	  ]
	}

	Chart.Line('chartdaysales',{
		data: chartDaySales,
		responsive: true,
		options : options
	});

	
	var last_twelve_weeks = [(moment().startOf('week').format('MMM D').toString()).concat(' - ', moment().endOf('week').format('MMM D').toString())];
	for (i = 1; i < 12; i++){
		last_twelve_weeks.unshift((moment().subtract(i,'weeks').startOf('week').format('MMM D').toString()).concat(' - ', moment().subtract(i,'weeks').endOf('week').format('MMM D').toString()));
	}

	var chartWeekTransactions = {
	  labels : last_twelve_weeks,
	  datasets : [
		  {
	      label: "Total Transactions for the Last Twelve Weeks",
	      borderColor: "#f0ad4e",
	      backgroundColor: "rgba(240, 173, 78, 0.3)",
	      data : <?php echo json_encode($transactions_last_twelve_weeks) ?>
		  }
	  ]
	}

	Chart.Line('chartweektransactions',{
		data: chartWeekTransactions,
		responsive: true,
		options : options
	});

	var chartWeekSales = {
	  labels : last_twelve_weeks,
	  datasets : [
		  {
	      label: "Total Sales for the Last 12 Weeks",
	      borderColor: "#f0ad4e",
	      backgroundColor: "rgba(240, 173, 78, 0.3)",
	      data : <?php echo json_encode($sales_last_twelve_weeks) ?>
		  }
	  ]
	}

	Chart.Line('chartweeksales',{
		data: chartWeekSales,
		responsive: true,
		options : options
	});

	var last_twelve_months = [moment().startOf('month').format('MMM YYYY')];
	for (i = 1; i < 12; i++){
		last_twelve_months.unshift(moment().subtract(i,'months').startOf('month').format('MMM YYYY'));
	}

	var chartMonthTransactions = {
	  labels : last_twelve_months,
	  datasets : [
		  {
	      label: "Total Transactions for the Last 12 Months",
	      borderColor: "#d9534f",
	      backgroundColor: "rgba(217, 83, 79, 0.3)",
	      data : <?php echo json_encode($transactions_last_twelve_months) ?>
		  }
	  ]
	}

	Chart.Line('chartmonthtransactions',{
		data: chartMonthTransactions,
		responsive: true,
		options : options
	});

	var chartMonthSales = {
	  labels : last_twelve_months,
	  datasets : [
		  {
	      label: "Total Sales for the Last 12 Months",
	      borderColor: "#d9534f",
	      backgroundColor: "rgba(217, 83, 79, 0.3)",
	      data : <?php echo json_encode($sales_last_twelve_months) ?>
		  }
	  ]
	}

	Chart.Line('chartmonthsales',{
		data: chartMonthSales,
		responsive: true,
		options : options
	});


</script>
@endsection