<?php
if(!defined("APP_START")) die("No Direct Access");

if(isset($_GET["action"]) && $_GET["action"]!=""){
	$bulk_action=$_GET["action"];
	$id=explode(",",urldecode($_GET["Ids"]));	
	$err="";
	if($bulk_action=="null"){
		$err.="Select Action. <br>";
	}
	if(!isset($_GET["Ids"]) || $_GET["Ids"]==""){
		$err.="Select Records. <br>";	
	}
	if(empty($err)){
		if($bulk_action=="delete"){
			$i=0;
			while($i<count($id)){
				$sales = doquery( "select * from sales where id = '".$id[$i]."' ", $dblink );
				if( numrows( $sales ) > 0 ) {
					$sales = dofetch( $sales );
					if( $sales[ "status" ]==1 ) {
						$rs=doquery("select * from sales_items where sales_id='".$id[$i]."'", $dblink);
						if(numrows($rs)){
							while($r=dofetch($rs)){
								$quantity=$r["quantity"];
								doquery("update items set quantity=quantity+".$quantity." where id='".slash($r["item_id"])."'", $dblink);
							}
						}
					}
					doquery("delete from sales_items where sales_id='".$id[$i]."'",$dblink);
					if( $sales[ "customer_payment_id" ] > 0 ) {
						doquery( "delete from customer_payment where id = '".$sales[ "customer_payment_id" ]."'", $dblink );
					}
					doquery("delete from sales where id='".$id[$i]."'",$dblink);
				}
				$i++;
			}
			header("Location: sales_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				$rec = doquery( "select * from sales where id='".$id[$i]."'", $dblink );
				if( numrows( $rec ) > 0 ) {
					$rec = dofetch( $rec );
					if( $rec[ "status" ] == 0 ) { //Add Stock
						$rs=doquery("select * from sales_items where sales_id='".$id[$i]."'", $dblink);
						if(numrows($rs)){
							while($r=dofetch($rs)){
								$quantity=$r["quantity"];
								doquery("update items set quantity=quantity-".$quantity." where id='".slash($r["item_id"])."'", $dblink);
							}
						}
					}
					if( $rec[ "customer_payment_id" ] > 0 ) {
						doquery( "update customer_payment set status=1 where id = '".$rec[ "customer_payment_id" ]."'", $dblink );
					}
				}
				doquery("update sales set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: sales_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				$rec = doquery( "select * from sales where id='".$id[$i]."'", $dblink );
				if( numrows( $rec ) > 0 ) {
					$rec = dofetch( $rec );
					if( $rec[ "status" ] == 1 ) { //Remove Stock
						$rs=doquery("select * from sales_items where sales_id='".$id[$i]."'", $dblink);
						if(numrows($rs)){
							while($r=dofetch($rs)){
								$quantity=$r["quantity"];
								doquery("update items set quantity=quantity+".$quantity." where id='".slash($r["item_id"])."'", $dblink);
							}
						}
					}
					if( $rec[ "customer_payment_id" ] > 0 ) {
						doquery( "update customer_payment set status=0 where id = '".$rec[ "customer_payment_id" ]."'", $dblink );
					}
				}
				doquery("update sales set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: sales_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: sales_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}