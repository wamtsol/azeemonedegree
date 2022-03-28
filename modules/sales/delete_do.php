<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$sales = dofetch(doquery( "select * from sales where id = '".$id."' ", $dblink ));
	$deleted_items = doquery( "select item_id, quantity from sales_items where sales_id='".$id."'", $dblink );
	if( numrows( $deleted_items ) > 0 ) {
		while( $deleted_item = dofetch( $deleted_items ) ) {
			doquery("update items set quantity=quantity+".$deleted_item[ "quantity" ]." where id='".slash($deleted_item["item_id"])."'", $dblink);
		}
	}
	doquery("delete from sales_items where sales_id='".$id."'",$dblink);
	if( $sales[ "customer_payment_id" ] > 0 ) {
		doquery( "delete from customer_payment where id = '".$sales[ "customer_payment_id" ]."'", $dblink );
	}
	doquery("delete from sales where id='".$id."'",$dblink);
	header("Location: sales_manage.php");
	die;
}


