<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["supplier_payment_manage"]["add"])){
	extract($_SESSION["supplier_payment_manage"]["add"]);	
}
else{
	$supplier_id="";
	$datetime_added=date("d/m/Y H:i A");
	$amount="";
	$account_id="";
	$details = "";
}
?>
<div class="page-header">
	<h1 class="title">Add New Supplier Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Supplier Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="supplier_payment_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="supplier_payment_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="supplier_id">Supplier Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="supplier_id" title="Choose Option">
                    <option value="0">Select Supplier</option>
                    <?php
                    $res=doquery("select * from supplier where status=1 order by id", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($supplier_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["supplier_name"]); ?></option>
                     <?php			
                        }			
                    }
                    ?>
                </select>
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="datetime_added">Datetime</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter datetime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="amount">Amount</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="account_id">Paid By </label>
            </div>
            <div class="col-sm-10">
                <select name="account_id" id="account_id" title="Choose Option" class="select_search">
                    <option value="">Select Account</option>
                    <?php
                    $res=doquery("select * from account where status=1 order by id", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($rec["is_petty_cash"] == 1)?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                     	<?php			
                        }			
                    }
                    ?>
                </select>
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="details">Details</label>
            </div>
            <div class="col-sm-10">
                <textarea title="Enter details" name="details" id="details" class="form-control"><?php echo $details?></textarea>
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="supplier_payment_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>