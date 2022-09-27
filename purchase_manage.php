<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'purchase_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "get_unit_price","get_quantity", "report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["purchase"]["list"]["date_from"]=$date_from;
}
if(isset($_SESSION["purchase"]["list"]["date_from"]))
	$date_from=$_SESSION["purchase"]["list"]["date_from"];
else
	$date_from="";
if($date_from != ""){
	$extra.=" and datetime_added>='".datetime_dbconvert($date_from)."'";
	$is_search=true;
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["purchase"]["list"]["date_to"]=$date_to;
}
if(isset($_SESSION["purchase"]["list"]["date_to"]))
	$date_to=$_SESSION["purchase"]["list"]["date_to"];
else
	$date_to="";
if($date_to != ""){
	$extra.=" and datetime_added<'".datetime_dbconvert($date_to)."'";
	$is_search=true;
}
if(isset($_GET["item_id"])){
	$item_id=slash($_GET["item_id"]);
	$_SESSION["purchase"]["list"]["item_id"]=$item_id;
}
if(isset($_SESSION["purchase"]["list"]["item_id"]))
	$item_id=$_SESSION["purchase"]["list"]["item_id"];
else
	$item_id="";
if($item_id!=""){
	$extra.=" and id in (select purchase_id from purchase_items where item_id = '".$item_id."')";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["purchase"]["list"]["q"]=$q;
}
if(isset($_SESSION["purchase"]["list"]["q"]))
	$q=$_SESSION["purchase"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and (supplier_name like '%".$q."%')";
	$is_search=true;
}
$adminId = '0';
if($_SESSION["logged_in_admin"]["admin_type_id"]!=1){
	$extra.= "and admin_id = '".$_SESSION["logged_in_admin"]["id"]."'";
	$adminId = $_SESSION["logged_in_admin"]["id"];
	$adminIdN = " and admin_id = '".$_SESSION["logged_in_admin"]["id"]."'";
}
else{
	$adminIdN = "";
}
$sql="select * from purchase where 1 $extra order by datetime_added desc, ts desc";
switch($tab){
	case 'add':
		include("modules/purchase/add_do.php");
	break;
	case 'edit':
		include("modules/purchase/edit_do.php");
	break;
	case 'delete':
		include("modules/purchase/delete_do.php");
	break;
	case 'status':
		include("modules/purchase/status_do.php");
	break;
	case 'bulk_action':
		include("modules/purchase/bulkactions.php");
	break;
	case 'report':
		include("modules/purchase/report.php");
		die;
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/purchase/list.php");
			break;
			case 'add':
				include("modules/purchase/add.php");
			break;
			case 'edit':
				include("modules/purchase/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>