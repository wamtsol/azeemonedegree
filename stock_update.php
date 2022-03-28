<?php
include("include/db.php");
include("include/utility.php");
$purchase_items = doquery("SELECT item_id, sum(quantity) FROM `purchase_items` group by item_id ", $dblink);
if( numrows($purchase_items) > 0 ){
	while( $purchase_item = dofetch( $purchase_items ) ) {
		$group_items = doquery( "select group_item_id, quantity from item_group where item_id='".$purchase_item[ "item_id" ]."'", $dblink );
		if( numrows( $group_items ) > 0 ){
			while( $group_item = dofetch( $group_items ) ){
				$sales_item = dofetch(doquery("SELECT sum(quantity) FROM `sales_items` where item_id='".$group_item[ "group_item_id" ]."'", $dblink));
				$total_sold = $sales_item[ "sum(quantity)" ] * $group_item[ "quantity" ];
				doquery( "update items set quantity='".($purchase_item[ "sum(quantity)" ] - $total_sold)."' where id='".$purchase_item[ "item_id" ]."'", $dblink );
			}
		}
		else{
			doquery( "update items set quantity='".$purchase_item[ "sum(quantity)" ]."' where id='".$purchase_item[ "item_id" ]."'", $dblink );
		}
	}
}