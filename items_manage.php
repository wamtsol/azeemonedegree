<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'items_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "print", "report", "update_items", "stock_csv");
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
	$_SESSION["items"]["list"]["q"]=$q;
}
if(isset($_SESSION["items"]["list"]["q"]))
	$q=$_SESSION["items"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and title like '%".$q."%'";
	$is_search=true;
}
if(isset($_GET["stock"])){
	$stock=slash($_GET["stock"]);
	$_SESSION["items"]["list"]["stock"]=$stock;
}
if(isset($_SESSION["items"]["list"]["stock"]))
	$stock=$_SESSION["items"]["list"]["stock"];
else
	$stock="";
if($stock != ""){
	if( $stock == "0" ){
		$extra.=" and quantity>low_stock_quantity";
	}
	if( $stock == "1" ){
		$extra.=" and quantity<=low_stock_quantity";
	}
	if( $stock == "2" ){
		$extra.=" and quantity=0";
	}
	$is_search=true;
}
$order_by = "title";
$order = "asc";
if( isset($_GET["order_by"]) ){
	$_SESSION["items"]["list"]["order_by"]=slash($_GET["order_by"]);
}
if( isset( $_SESSION["items"]["list"]["order_by"] ) ){
	$order_by = $_SESSION["items"]["list"]["order_by"];
}
if( isset($_GET["order"]) ){
	$_SESSION["items"]["list"]["order"]=slash($_GET["order"]);
}
if( isset( $_SESSION["items"]["list"]["order"] ) ){
	$order = $_SESSION["items"]["list"]["order"];
}
$orderby = $order_by." ".$order;
//$adminId = '0';
//if($_SESSION["logged_in_admin"]["admin_type_id"]!=1){
	$extra.= "and admin_id = '".$_SESSION["logged_in_admin"]["id"]."'";
	$adminId = $_SESSION["logged_in_admin"]["id"];
//}
$sql="select * from items where 1 $extra order by $orderby";
switch($tab){
	case 'add':
		include("modules/item/add_do.php");
	break;
	case 'edit':
		include("modules/item/edit_do.php");
	break;
	case 'delete':
		include("modules/item/delete_do.php");
	break;
	case 'status':
		include("modules/item/status_do.php");
	break;
	case 'bulk_action':
		include("modules/item/bulkactions.php");
	break;
	case 'print':
		include("modules/item/print.php");
		die;
	break;
	case 'report':
		include("modules/item/report.php");
		die;
	break;
	case 'update_items':
		include("modules/item/update_item.php");
		die;
	break;
	case 'stock_csv':
		include("modules/item/stock_csv.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
	  
		switch($tab){
			case 'list':
				include("modules/item/list.php");
			break;
			case 'add':
				include("modules/item/add.php");
			break;
			case 'edit':
				include("modules/item/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>