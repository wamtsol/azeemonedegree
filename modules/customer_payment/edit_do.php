<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_payment_edit"])){
	extract($_POST);
	$err="";
	if(empty($customer_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update customer_payment set `customer_id`='".slash($customer_id)."',`datetime_added`='".slash(datetime_dbconvert(unslash($datetime_added)))."', `amount`='".slash($amount)."',`account_id`='".slash($account_id)."',`details`='".slash($details)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		if(!empty($_FILES["payment_image"]["tmp_name"]) || isset($delete_image)){
			$prev_icon=doquery("select payment_image from customer_payment where id=$id",$dblink);
			if(numrows($prev_icon)>0){
				$p_icon=dofetch($prev_icon);
				deleteFile($file_upload_root."customer_payment_image/".$p_icon["payment_image"]);
				$sql1="Update customer_payment set payment_image='' where id='".$id."'";
				doquery($sql1,$dblink);
			}
			if(!empty($_FILES["payment_image"]["tmp_name"])){
				$payment_image=getFilename($_FILES["payment_image"]["name"], $id);
				move_uploaded_file($_FILES["payment_image"]["tmp_name"], $file_upload_root."customer_payment_image/".$payment_image);
				$sql1="Update customer_payment set payment_image='".slash($payment_image)."' where id='".$id."'";
				doquery($sql1,$dblink);
			}
		}
		unset($_SESSION["customer_payment_manage"]["edit"]);
		header('Location: customer_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_payment_manage"]["edit"][$key]=$value;
		header("Location: customer_payment_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from customer_payment where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime_added=datetime_convert($datetime_added);
		if(isset($_SESSION["customer_payment_manage"]["edit"]))
			extract($_SESSION["customer_payment_manage"]["edit"]);
	}
	else{
		header("Location: customer_payment_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: customer_payment_manage.php?tab=list");
	die;
}