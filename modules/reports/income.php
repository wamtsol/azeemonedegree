<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["income"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["income"]["date_from"]))
	$date_from=$_SESSION["reports"]["income"]["date_from"];
else
	$date_from=date("1/m/Y");;

if($date_from != ""){
	$extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["income"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["income"]["date_to"]))
	$date_to=$_SESSION["reports"]["income"]["date_to"];
else
	$date_to=date("d/m/Y");;

if($date_to != ""){
	$extra.=" and datetime_added<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if( empty( $extra ) ) {
	$extra = ' and 1=0 ';
}
$order_by = "datetime_added";
$order = "desc";
$orderby = $order_by." ".$order;
?>
<div class="page-header">
	<h1 class="title">Reports</h1>
  	<ol class="breadcrumb">
    	<li class="active">Income report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <input type="hidden" name="tab" value="income" />
                <div class="col-sm-3">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="From" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off">
                </div>
                <div class="col-sm-3">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="To" class="form-control date-picker"  value="<?php echo $date_to?>" autocomplete="off">
                </div>
                
                <div class="col-sm-2 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<?php
		$sql="select sum(total_items), sum(total_price), sum(discount), sum(net_price) from sales where 1 $extra";
		$total=dofetch(doquery($sql, $dblink));
		$sql="select sum(total_price)-sum(discount) as total from purchase where status = 1 $extra";
		$purchase_total=dofetch(doquery($sql, $dblink));
		?>
    	<tr>
            <th class="text-right">Total Items Sold</th>
            <th class="text-right"><?php echo $total[ "sum(total_items)" ]?></th>
        </tr>
        <tr>
            <th class="text-right">Total Price</th>
            <th class="text-right">Rs. <?php echo curr_format($total[ "sum(total_price)" ])?></th>
        </tr>
        <tr>
            <th class="text-right">Total Discount</th>
            <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(discount)" ])?></th>
        </tr>
        <tr class="head">
            <th class="text-right">Net Total</th>
            <th class="text-right" >Rs. <?php echo curr_format($total[ "sum(net_price)" ])?></th>
        </tr>
        <!-- <tr>
            <th class="text-right">Total Purchase</th>
            <th class="text-right" >Rs. <?php echo curr_format($purchase_total[ "total" ])?></th>
        </tr>
        <?php
		$total = 0;
		$rs = doquery( "select title, sum(amount) as total, datetime_added from expense a left join expense_category b on a.expense_category_id = b.id where a.status=1 $extra group by a.expense_category_id", $dblink );
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				if( $r[ "total" ] > 0 ){
					$total += $r[ "total" ];
					?>
					<tr>
						<td class="text-right"><?php echo unslash( $r[ "title" ] )?></td>
						<td class="text-right" >Rs. <?php echo curr_format($r[ "total" ])?></td>
					</tr>	
					<?php
				}
			}
		}
		?>
        <tr class="head">
            <th class="text-right">Total Expense</th>
            <th class="text-right" ><?php echo curr_format($total)?></th>
        </tr> -->
  	</table>
</div>
