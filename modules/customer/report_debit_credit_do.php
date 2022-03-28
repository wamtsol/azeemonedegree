<?php
if(!defined("APP_START")) die("No Direct Access");
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from customer where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$customer=dofetch($rs);		
	}
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["start_date"])){
	$start_date=slash($_GET["start_date"]);
	$_SESSION["customer_manage"]["report_debit_credit"][ "start_date" ]=$start_date;
}
if(isset($_SESSION["customer_manage"]["report_debit_credit"][ "start_date" ]))
	$start_date=$_SESSION["customer_manage"]["report_debit_credit"][ "start_date" ];
else
	$start_date=date("01/m/Y");
if(!empty($start_date)){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00'";
	$is_search=true;
}
if(isset($_GET["end_date"])){
	$end_date=slash($_GET["end_date"]);
	$_SESSION["customer_manage"]["report_debit_credit"][ "end_date" ]=$end_date;
}
if(isset($_SESSION["customer_manage"]["report_debit_credit"][ "end_date" ]))
	$end_date=$_SESSION["customer_manage"]["report_debit_credit"][ "end_date" ];
else
	$end_date=date("d/m/Y");
if(!empty($end_date)){
	$extra.=" and date<'".date('Y-m-d',strtotime(date_dbconvert($end_date)))." 23:59:59'";
	$is_search=true;
}
$order_by = "date";
$order = "asc";
if( isset($_GET["order_by"]) ){
	$_SESSION["customer_manage"]["report_debit_credit"]["order_by"]=slash($_GET["order_by"]);
}
if( isset( $_SESSION["customer_manage"]["report_debit_credit"]["order_by"] ) ){
	$order_by = $_SESSION["customer_manage"]["report_debit_credit"]["order_by"];
}
if( isset($_GET["order"]) ){
	$_SESSION["customer_manage"]["report_debit_credit"]["order"]=slash($_GET["order"]);
}
if( isset( $_SESSION["customer_manage"]["report_debit_credit"]["order"] ) ){
	$order = $_SESSION["customer_manage"]["report_debit_credit"]["order"];
}
$orderby = $order_by." ".$order;
$main_sql = array();
$main_sql[] = "select datetime_added as date, concat( 'Sale', if(net_price>0, '', ''),' ', '', '<a href=\"sales_manage.php?tab=edit&id=', a.id,'\" target=\"_blank\" class=\"view-link\"> View bill </a>' ) as details, 0 as debit, net_price as credit from sales a left join customer b on a.customer_id=b.id where a.status=1".(!empty($customer["id"])?" and customer_id='".$customer["id"]."'":"");
//$main_sql[] = "select datetime_added as date, bill_image, concat( 'Wastage ', if(net_price>0, 'from', 'to'),' Wastage # ', a.id, '<a href=\"purchase_manage.php?tab=edit&id=', a.id,'\" target=\"_blank\" class=\"view-link\"> View bill</a>', '<a href=\"$file_upload_root/purchase_bill/', bill_image,'\" target=\"_blank\" class=\"view-link\"> View File</a>' ) as details, net_price as debit, 0 as credit from purchase a left join supplier b on a.supplier_id=b.id where a.type=1 and a.status=1".(!empty($supplier["id"])?" and supplier_id='".$supplier["id"]."'":"");
$main_sql[] = "select datetime_added as date, concat( ' Payment ', details, '') as details, amount as debit, 0 as credit from customer_payment a left join customer b on a.customer_id=b.id where a.status=1".(!empty($customer["id"])?" and customer_id='".$customer["id"]."'":"");
$main_sql="(".implode( ' union ', $main_sql ).") as total_records";
$sql = "select * from ".$main_sql." where 1 $extra order by $orderby";
$balance = dofetch( doquery( "select sum(debit)-sum(credit) as balance from ".$main_sql." where date < '".date('Y-m-d',strtotime(date_dbconvert($start_date)))." 00:00:00'", $dblink ) );
if( $order == 'desc' ) {
	$balance = get_customer_balance( $customer["id"], date_dbconvert($end_date)." 23:59:59" );
}
else{
	$balance = get_customer_balance( $customer["id"], date_dbconvert($start_date) );
}
