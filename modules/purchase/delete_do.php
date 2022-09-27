<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$purchase = doquery( "select * from purchase where id = '".$id."' ", $dblink );
	if( numrows( $purchase ) > 0 ) {
		$purchase = dofetch( $purchase );
		if( $purchase[ "status" ]==1 ) {
			$rs=doquery("select * from purchase_items where purchase_id='".$id."'", $dblink);
			if(numrows($rs)){
				while($r=dofetch($rs)){
					$quantity=$r["quantity"];
					doquery("update items set quantity=quantity-".$quantity." where id='".slash($r["item_id"])."'", $dblink);
				}
			}
		}
		doquery("delete from purchase_items where purchase_id='".$id."'",$dblink);
		if( $purchase[ "supplier_payment_id" ] > 0 ) {
			doquery( "delete from supplier_payment where id = '".$purchase[ "supplier_payment_id" ]."'", $dblink );
		}
		doquery("delete from purchase where id='".$id."'",$dblink);
	}
	header("Location: purchase_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}