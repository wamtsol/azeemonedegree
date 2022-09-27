<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$status = slash($_GET["s"]);
	$id=slash($_GET["id"]);
	$rec = doquery( "select * from purchase where id='".$id."'", $dblink );
	if( numrows( $rec ) > 0 ) {
		$rec = dofetch( $rec );
		$update_stock = false;
		if( $status == 0 && $rec[ "status" ] == 1 ) { //Remove Stock
			$sign = '-';
			$update_stock = true;
		}
		else if( $status == 1 && $rec[ "status" ] == 0 ) { //Add Stock
			$sign = '+';
			$update_stock = true;
		}
		if( $update_stock ) {
			$rs=doquery("select * from purchase_items where purchase_id='".$id."'", $dblink);
			if(numrows($rs)){
				while($r=dofetch($rs)){
					$quantity=$r["quantity"];
					doquery("update items set quantity=quantity".$sign.$quantity." where id='".slash($r["item_id"])."'", $dblink);
				}
			}
		}
		if( $rec[ "supplier_payment_id" ] > 0 ) {
			doquery( "update supplier_payment set status='".$status."' where id = '".$rec[ "supplier_payment_id" ]."'", $dblink );
		}
	}
	doquery("update purchase set status='".$status."' where id='".$id."'",$dblink);
	header("Location: purchase_manage.php");
	die;
}