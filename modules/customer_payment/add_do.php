<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($customer_id))
		$err="Fields with (*) are Mandatory.<br />";
	if(!empty($_FILES["payment_image"]["tmp_name"]) && !in_array($_FILES["payment_image"]["type"],$imagetypes)){
		$err.="Image format not supported. <br>";
	}
	if($err==""){
		$sql="INSERT INTO customer_payment (admin_id, customer_id, datetime_added, amount, account_id, details) VALUES ('".$adminId."', '".slash($customer_id)."','".slash(datetime_dbconvert($datetime_added))."','".slash($amount)."','".slash($account_id)."','".slash($details)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		if(!empty($_FILES["payment_image"]["tmp_name"])){
			$payment_image=getFilename($_FILES["payment_image"]["name"], $id);
			move_uploaded_file($_FILES["payment_image"]["tmp_name"], $file_upload_root."customer_payment_image/".$payment_image);
			$sql1="Update customer_payment set payment_image='".$payment_image."' where id=$id";
			doquery($sql1,$dblink);
		}
		unset($_SESSION["customer_payment_manage"]["add"]);
		header('Location: customer_payment_manage.php?tab=list&msg='.url_encode("Successfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_payment_manage"]["add"][$key]=$value;
		header('Location: customer_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}