<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["purchase_edit"])){
	extract($_POST);
	$err="";
	if(empty($datetime_added) || count($items)==0)
		$err="Fields with (*) are Mandatory.<br />";
	$items_array=array();
	$i=0;
	foreach($items as $item){
		if(!empty($item)){
			if(array_key_exists($item, $items_array)){
				$items_array[$item]["quantity"]+=$quantity[$i];
			}
			else{
				$items_array[$item]=array(
					"unit_price" => $unit_price[$i],
					"quantity" => $quantity[$i]
				);
			}
		}
		$i++;
	}
	if($err==""){
		$sql="Update purchase set `datetime_added`='".slash(datetime_dbconvert($datetime_added))."',`supplier_id`='".slash($supplier_id)."' where id='".$id."'";
		doquery($sql,$dblink);
		$grand_total_price=$quantity=0;
		foreach($items_array as $item_id=>$item){
			$r=dofetch(doquery("select unit_price from items where id='".slash($item_id)."'", $dblink));
			$total_price=$item["unit_price"]*$item["quantity"];
			$grand_total_price+=$total_price;
			$quantity+=$item["quantity"];
			$prev=doquery("select id, quantity from purchase_items where purchase_id='".$id."' and item_id='".$item_id."'", $dblink);
			if(numrows($prev)){
				$prev=dofetch($prev);
				doquery("update purchase_items set `unit_price`='".$item["unit_price"]."', `quantity`='".$item["quantity"]."', `total_price`='".$total_price."' where id='".$prev["id"]."'", $dblink);
				doquery("update items set quantity=quantity+".($item["quantity"]-$prev["quantity"])." where id='".slash($item_id)."'", $dblink);
			}
			else{
				doquery("insert into purchase_items(purchase_id, item_id, unit_price, quantity, total_price) values('".$id."', '".$item_id."', '".$item["unit_price"]."', '".$item["quantity"]."', '".$total_price."')", $dblink);
				doquery("update items set quantity=quantity+".$item["quantity"]." where id='".slash($item_id)."'", $dblink);	
			}
		}
		doquery("update purchase set total_items=".$quantity.", discount='".$discount."', total_price='".$grand_total_price."', net_price='".($grand_total_price-$discount)."' where id='".$id."'", $dblink);
		$items = doquery( "select * from purchase_items where purchase_id='".$id."' and item_id not in (".implode($items, ",").")", $dblink );
		if( numrows( $items ) > 0 ) {
			while( $item = dofetch( $items ) ) {
				doquery("update items set quantity=quantity-".$item["quantity"]." where id='".slash($item[ "item_id" ])."'", $dblink);
				doquery( "delete from purchase_items where id='".$item[ "id" ]."'", $dblink );
			}
		}
		$purchase = dofetch( doquery( "select * from purchase where id = '".$id."' ", $dblink ) );
		if( !empty( $payment_account_id ) && $payment_amount > 0 ) {
			if( !empty( $purchase[ "supplier_payment_id" ] ) ){
				doquery( "update supplier_payment set amount = '".slash($payment_amount)."', account_id = '".$payment_account_id."', datetime_added = '".datetime_dbconvert($datetime_added)."' where id = '".$purchase[ "supplier_payment_id" ]."'", $dblink );
			}
			else{
				doquery( "insert into supplier_payment(supplier_id, account_id, datetime_added, amount, details) values( '".$supplier_id."', '".slash( $payment_account_id )."', '".datetime_dbconvert($datetime_added)."', '".slash($payment_amount)."', 'Payment againset Purchase #".$id."' )", $dblink );
				$supplier_payment_id = inserted_id();
				doquery( "update purchase set supplier_payment_id = '".$supplier_payment_id."' where id = '".$id."'", $dblink );
			}
		}
		else if( !empty( $purchase[ "supplier_payment_id" ] ) ){
			doquery( "delete from supplier_payment where id = '".$purchase[ "supplier_payment_id" ]."'", $dblink );
			doquery( "update purchase set supplier_payment_id = 0 , payment_amount = 0 where id = '".$id."'", $dblink );
		}
		unset($_SESSION["purchase_manage"]["edit"]);
		header('Location: purchase_manage.php?tab=list&msg='.url_encode("Successfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["purchase_manage"]["edit"][$key]=$value;
		header('Location: purchase_manage.php?tab=edit&err='.url_encode($err));
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from purchase where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$datetime_added=date_convert($datetime_added);
		$items=$unit_price=$quantity=array();
		$rs=doquery("select * from purchase_items where purchase_id='".$id."' order by id", $dblink);
		if(numrows($rs)){
			while($r=dofetch($rs)){
				$items[]=$r["item_id"];
				$unit_price[]=$r["unit_price"];
				$quantity[]=$r["quantity"];
			}
		}
		$payment_account_id = "";
		$payment_amount = 0;
		$payment_account_id = 0;
		if( $supplier_payment_id > 0 ) {
			$supplier_payment = doquery( "select * from supplier_payment where id = '".$supplier_payment_id."'", $dblink );
			if( numrows( $supplier_payment ) > 0 ) {
				$supplier_payment = dofetch( $supplier_payment );
				$payment_account_id = $supplier_payment[ "account_id" ];
				$payment_amount = $supplier_payment[ "amount" ];
			}
		}
		if(isset($_SESSION["purchase_manage"]["edit"]))
			extract($_SESSION["purchase_manage"]["edit"]);
	}
	else{
		header("Location: purchase_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: purchase_manage.php?tab=list");
	die;
}