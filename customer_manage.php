<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report", "print", "report_debit_credit", "report_debit_credit_print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["customer_manage"]["q"]=$q;
}
if(isset($_SESSION["customer_manage"]["q"]))
	$q=$_SESSION["customer_manage"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and customer_name like '%".$q."%'";
	$is_search=true;
}
$adminId = '0';
if($_SESSION["logged_in_admin"]["admin_type_id"]!=1){
	$extra.= "and admin_id = '".$_SESSION["logged_in_admin"]["id"]."'";
	$adminId = $_SESSION["logged_in_admin"]["id"];
}
switch($tab){
	case 'add':
		include("modules/customer/add_do.php");
	break;
	case 'edit':
		include("modules/customer/edit_do.php");
	break;
	case 'delete':
		include("modules/customer/delete_do.php");
	break;
	case 'status':
		include("modules/customer/status_do.php");
	break;
case 'report':
		include("modules/customer/report_do.php");
	break;
	case 'bulk_action':
		include("modules/customer/bulkactions.php");
	break;
case 'print':
		include("modules/customer/print_do.php");
	break;
case 'report_debit_credit':
	include("modules/customer/report_debit_credit_do.php");
break;
case 'report_debit_credit_print':
	include("modules/customer/report_debit_credit_print.php");
break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/customer/list.php");
			break;
			case 'add':
				include("modules/customer/add.php");
			break;
			case 'edit':
				include("modules/customer/edit.php");
			break;
			case 'report':
				include("modules/customer/report.php");
			break;
			case 'report_debit_credit':
				include("modules/customer/report_debit_credit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>