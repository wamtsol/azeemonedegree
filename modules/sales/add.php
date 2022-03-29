<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["sales_manage"]["add"])){
    extract($_SESSION["sales_manage"]["add"]);
}
else{
	$customer_id="";
	$datetime_added=date("d/m/Y H:i A");
	$items=array();
	$discount = 0;
	$payment_account_id = 0;
	$payment_amount = 0;
    $cash_receive = 0;
    $cash_return = 0;
}
?>
<div class="page-header">
	<h1 class="title">Add New Bill</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Bill</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="sales_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>


<form action="sales_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd" class="form-horizontal form-horizontal-left">
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="datetime_added">Date <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="customer_id">Customer Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="customer_id" id="" class="margin-btm-5 selectbox">
                	<option value="">Select Customer</option>
                    <?php
                    $rs = doquery( "select * from customer where status=1 order by customer_name", $dblink );
					if( numrows( $rs ) > 0 ) {
						while( $r = dofetch( $rs ) ) {
							?>
							<option value="<?php echo $r[ "id" ]?>"<?php echo $customer_id==$r["id"]?" selected":""?>><?php echo unslash($r[ "customer_name" ])." (".get_customer_balance($r["id"]).")";?></option>
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
                <label class="form-label">Packages <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <div class="panel-body table-responsive">
                    <table class="table table-hover list">
                        <thead>
                            <tr>
                                <th width="2%" class="text-center">S.no</th>
                                <th>Package</th>
                                <th width="10%" class="text-right">Price</th>
                                <th width="15%" class="text-right">Total Package</th>
                                <th width="12%" class="text-right">Total Price</th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sn=1;
                            if(count($items)>0){
                                foreach($items as $item){
                                    ?>
                                    <tr class="sale_item">
                                        <td class="text-center serial_number"><?php echo $sn;?></td>
                                        <td>
                                        	<?php
												$groupItem='';
												$singleItem='';
                                                $sql="select * from items where status=1 order by title";
                                                $rs=doquery($sql, $dblink);
                                                if(numrows($rs)>0){
                                                    while($r=dofetch($rs)){
														
															$singleItem .='<option value="'.$r["id"].'"'.($item==$r["id"]?" selected":"").'> '. unslash($r["title"]) .'  </option>';  
                                                    }
                                                }
                                                ?>
                                                <select name="items[]" class="item_select" >
                                                	<option value="">Select Package</option>
 
                                                		<?php echo $singleItem;?>

                                            	</select>
                                                <span class="qty"></span>
                                        </td>
                                        <td><input type="text" name="unit_price[]" class="unit_price" id="unit_price<?php echo $sn?>" name="unit_price[]" value="<?php echo $unit_price[$sn-1]?>" /></td>
                                        <td class="text-right"><input type="text" class="quantity" name="quantity[]" id="quantity<?php echo $sn?>" value="<?php echo $quantity[$sn-1]?>" /></td>
                                        <td class="text-right"><input type="text" class="total_price"  id="total_price<?php echo $sn?>" value="" /></td>                        
                                        <td class="text-center"><a href="#" data-id="<?php echo $sn?>" class="add_list_item" data-container_class="sale_item">Add</a> - <a href="#" data-id="<?php echo $sn?>" class="delete_list_item" data-container_class="sale_item">Delete</a></td>
                                    </tr>
                                    <?php 
                                    $sn++;
                                }
                            }
                            else{
                            ?>
                            <tr class="sale_item">
                                <td class="text-center serial_number"><?php echo $sn;?></td>
                                <td>
                                        <?php
										$groupItem='';
										$singleItem='';
                                        $sql="select * from items where status=1 order by title";
                                        $rs=doquery($sql, $dblink);
                                        if(numrows($rs)>0){
                                            while($r=dofetch($rs)){
												
												$singleItem .='<option value="'.$r["id"].'">'. unslash($r["title"]) .'  </option>';																
                                                ?>
												<?php
                                            }
                                        }
                                        ?>
                                        
                                      <select name="items[]" class="item_select">
                                        <option value="">Select Package</option>  

                                                	<?php echo $singleItem;?>
                                    </select>
                                    <span class="qty"></span>
                                </td>
                                <td><input type="text" class="unit_price" name="unit_price[]" id="unit_price<?php echo $sn?>"  value="" /></td>
                                <td class="text-right"><input type="text" class="quantity" name="quantity[]" id="quantity<?php echo $sn?>" value="1" /></td>
                                <td class="text-right"><input type="text" class="total_price" id="total_price<?php echo $sn?>" value="" /></td>                        
                                <td class="text-center"><a href="#" data-id="<?php echo $sn?>" class="add_list_item" data-container_class="sale_item">Add</a> - <a href="#" class="delete_list_item" data-container_class="sale_item">Delete</a></td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <th colspan="4" class="text-right">Total Package</th>
                                <th class="text-right grand_total_item"></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Discount</th>
                                <th class="text-right"><input type="number" class="discount" name="discount" id="discount" value="<?php echo $discount?>" style="text-align:right" data-container_class="sale_item" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Total Price</th>
                                <th class="text-right grand_total_price"></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Cash Receive</th>
                                <th class="text-right"><input type="number" class="cash_receive" name="cash_receive" id="cash_receive" value="<?php echo $cash_receive?>" style="text-align:right" data-container_class="sale_item" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Cash Return</th>
                                <th class="text-right"><input type="number" class="cash_return" name="cash_return" id="cash_return" value="<?php echo $cash_return?>" style="text-align:right" data-container_class="sale_item" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Payment Account</th>
                                <th class="text-right"><select name="payment_account_id" id="payment_account_id">
                                        <option value="">Select Account</option>
                                        <?php
                                        $rs = doquery( "select * from account where status = 1 order by title", $dblink );
                                        if( numrows( $rs ) > 0 ) {
                                            while( $r = dofetch( $rs ) ) {
                                                ?>
                                                <option value="<?php echo $r["id"]?>"<?php echo $payment_account_id==$r["id"]||empty($payment_account_id)&&$r["is_petty_cash"]==1?"selected":"";?>><?php echo unslash($r["title"]); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            
                            <tr>
                                <th colspan="4" class="text-right">Payment Amount</th>
                                <th class="text-right"><input type="text" class="payment_amount" name="payment_amount" id="payment_amount" value="<?php echo $payment_amount?>" style="text-align:right" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    	</div>
    </div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SAVE AND PRINT" class="btn btn-default btn-l" name="sales_add" title="Submit Record" />
                <input type="submit" value="SAVE" class="btn btn-success btn-l" name="sales_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>
