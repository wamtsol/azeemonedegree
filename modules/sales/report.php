<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_pkg = $total_price = 0;
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  11px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 11px;
	vertical-align:top;
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
.text-right{ text-align:right;}
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="8">
    	<h1><?php echo get_config( 'site_title' )?></h1>
    	<h2>Bill List</h2>
        <p>
        	<?php
			echo "List of";
			if( !empty( $date_from ) || !empty( $date_to ) ){
				echo "<br />Date";
			}
			if( !empty( $date_from ) ){
				echo " from ".$date_from;
			}
			if( !empty( $date_to ) ){
				echo " to ".$date_to."<br>";
			}
			if( !empty( $customer_id ) ){
				echo " Customer: ".get_field($customer_id, "customer","customer_name")."<br>";
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="5%" align="center">Bill #</th>
	<th width="10%">Date</th>
    <th width="20%">Customer Name</th>
	<th width="10%">Phone</th>
    <th align="right" width="12%">Total Package</th>
    <th align="right" width="12%">Total Price</th>
	<th align="right" width="12%">Payment Amount</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		// $ts = strtotime( $r["datetime_added"] );
		// $count = dofetch(doquery( "select count(1) from sales where datetime_added >= '".date("Y-m-01 00:00:00", $ts)."' and datetime_added<'".date("Y-m-d H:i:s", $ts)."'", $dblink ));
		// $invoice_id = $count["count(1)"]+1;
		$total_pkg += $r["total_items"];
		$total_price += $r["net_price"];
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
           	<td align="center"><?php echo $r["id"]?></td>
			<td><?php echo datetime_convert($r["datetime_added"]); ?></td>
			<td><?php echo unslash($r["customer_name"]); ?></td>
			<td><?php echo unslash($r["phone"]); ?></td>
			<td align="right"><?php echo unslash($r["total_items"]); ?></td>
			<td align="right"><?php echo curr_format(unslash($r["net_price"])); ?></td>
			<td class="text-right">
				<?php
					$payment_amounts = doquery("select * from customer_payment where id = '".$r["customer_payment_id"]."'", $dblink);
					if(numrows($payment_amounts)>0){
						$payment_amount = dofetch($payment_amounts);
						echo curr_format($payment_amount["amount"]); 
					}
					else{
						echo "Payment not received";
					}
				?>
			</td>
        </tr>
		<?php
	}
}
?>
<tr>
    <th colspan="5" style="text-align:right;">Total</th>
    <th style="text-align:right;"><?php echo curr_format($total_pkg);?></th>
	<th style="text-align:right;"><?php echo curr_format($total_price);?></th>
	<th></th>
</tr>
</table>
<?php
die;