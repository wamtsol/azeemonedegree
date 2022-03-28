<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=false;
$customer=dofetch(doquery("select * from customer where id = '".slash($_GET["id"])."' order by customer_name", $dblink));
if(isset($_GET["start_date"])){
	$start_date=slash($_GET["start_date"]);
	$_SESSION["customer_manage"]["report"][ "start_date" ]=$start_date;
}
if(isset($_SESSION["customer_manage"]["report"][ "start_date" ]))
	$start_date=$_SESSION["customer_manage"]["report"][ "start_date" ];
else
	$start_date=date("01/m/Y");
if(!empty($start_date)){
	$extra.=" and datetime_added>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00'";
	$is_search=true;
}
if(isset($_GET["end_date"])){
	$end_date=slash($_GET["end_date"]);
	$_SESSION["customer_manage"]["report"][ "end_date" ]=$end_date;
}
if(isset($_SESSION["customer_manage"]["report"][ "end_date" ]))
	$end_date=$_SESSION["customer_manage"]["report"][ "end_date" ];
else
	$end_date=date("d/m/Y");
if(!empty($end_date)){
	$extra.=" and datetime_added<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59'";
	$is_search=true;
}

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
    border-collapse:collapse;
	max-width:900px;
	margin:0 auto;
}
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="9">
    	<?php echo get_config( 'fees_chalan_header' )?>
    	<h2>Customer Ledger</h2>
        <p>
        	<?php
			echo "List of";
			if( !empty( $start_date ) || !empty( $end_date ) ){
				echo "<br />Date";
			}
			if( !empty( $start_date ) ){
				echo " from ".$start_date;
			}
			if( !empty( $end_date ) ){
				echo " to ".$end_date;
			}
            if( !empty( $id ) ){
				echo " Customer: ".get_field($id, "customer","customer_name");
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th>Date</th>
    <th>Transaction</th>                
    <th align="right">Amount</th>
    <th align="right">Balance</th>
</tr>
<?php 

	$balance = get_customer_balance($customer['id'], date_dbconvert($start_date));
	$sn=1;
	?>
	<tr>
		<td align="center"><?php echo $sn++;?></td>
		<td><?php echo $end_date; ?></td>
		<td>Opening Balance</td>
		<td align="right">--</td>
		<td align="right"><?php echo curr_format($balance); ?></td>
	</tr>
	<?php
	
	$sql="select id, datetime_added, 'sales' as type, net_price as amount from sales where customer_id='".$customer[ "id" ]."' $extra union select id, datetime_added as date, 'Payment' as type, -amount from customer_payment where customer_id='".$customer["id"]."' ".str_replace( "datetime_added", "datetime_added", $extra)." order by datetime_added";
	$rs=doquery($sql,$dblink);
	if(numrows($rs)>0){
		while($r=dofetch($rs)){
			$balance+=$r["amount"];
			?>
			<tr>
				<td align="center"><?php echo $sn;?></td>
				<td><?php echo datetime_convert($r["datetime_added"]); ?></td>
				<td><?php echo unslash($r["type"])." # ".$r["id"]; ?></td>
				<td align="right"><?php echo curr_format($r["amount"]); ?></td>
				<td align="right"><?php echo curr_format($balance); ?></td>
			</tr>
			<?php 
			$sn++;
			//$balance = $balance - $r["amount"];
		}
		?>
		<!-- <tr>
			<td align="center"><?php echo $sn++;?></td>
			<td><?php // echo $start_date; ?></td>
			<td>Opening Balance</td>
			<td align="right">--</td>
			<td align="right"><?php // echo curr_format($balance); ?></td>
		</tr> -->
		<?php
	}
	else{	
		?>
		<tr>
			<td colspan="5"  class="no-record">No Result Found</td>
		</tr>
		<?php
	}
?>
</table>
<?php
die;