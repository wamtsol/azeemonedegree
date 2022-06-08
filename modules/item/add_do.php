<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["items_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO items (admin_id, title, unit_price, quantity, low_stock_quantity) VALUES ('".$adminId."', '".slash($title)."','".slash($unit_price)."','".slash($quantity)."', '".slash($low_stock_quantity)."')";
		doquery($sql,$dblink);
		$id = inserted_id();
		// if(!empty($_FILES["name_in_urdu"]["tmp_name"])){
		// 	$name_in_urdu=getFilename($_FILES["name_in_urdu"]["name"], $id." ".$title);
		// 	move_uploaded_file($_FILES["name_in_urdu"]["tmp_name"], $file_upload_root."item/".$name_in_urdu);
		// 	$sql="Update items set name_in_urdu='".$name_in_urdu."' where id=$id";
		// 	doquery($sql,$dblink);
		// }
		unset($_SESSION["items_manage"]["add"]);
		header('Location: items_manage.php?tab=list&msg='.url_encode("Successfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["items_manage"]["add"][$key]=$value;
		header('Location: items_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}