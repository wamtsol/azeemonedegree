<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_add"])){
	extract($_POST);
	$err="";
	if(empty($customer_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO customer (admin_id, customer_name, phone, address, balance) VALUES ('".$adminId."', '".slash($customer_name)."','".slash($phone)."','".slash($address)."','".slash($balance)."')";
		doquery($sql,$dblink);
		unset($_SESSION["customer_manage"]["add"]);
		header('Location: customer_manage.php?tab=list&msg='.url_encode("Successfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_manage"]["add"][$key]=$value;
		header('Location: customer_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}