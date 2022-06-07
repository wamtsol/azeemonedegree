<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title">Manage Bill</h1>
  	<ol class="breadcrumb">
    	<li class="active">All Billing</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="sales_manage.php?tab=add" class="btn btn-light editproject">Add New Record</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="sales_manage.php?tab=report"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <span class="col-sm-1 text-to">From</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" autocomplete="off">
                </div>
                <span class="col-sm-1 text-to">To</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker" value="<?php echo $date_to?>" autocomplete="off">
                </div>
                <div class="col-sm-2">
                	<select name="customer_id" id="customer_id" class="custom_select">
                        <option value=""<?php echo ($customer_id=="")? " selected":"";?>>Select Customer</option>
                        <?php
                            $res=doquery("select * from customer where status = 1 and admin_id = '".$adminId."' order by customer_name ",$dblink);
                            if(numrows($res)>=0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>" <?php echo($customer_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["customer_name"])?></option>
                                <?php
                                }
                            }	
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
                </div>
                <div class="col-sm-2 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="2%" class="text-center">S.no</th>
                <th class="text-center" width="3%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="5%">Bill #</th>
                <th width="12%">
                	<a href="sales_manage.php?order_by=datetime_added&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                        Date
                        <?php
                            if( $order_by == "datetime_added" ) {
                                ?>
                                <span class="sort-icon">
                                    <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                                </span>
                                <?php
                            }
                            ?>
 					</a>
                </th>
                <th>Customer Name</th>
                <th width="8%">Phone</th>
                <th class="text-right" width="8%">Package</th>
                <th class="text-right" width="6%">
                	<a href="sales_manage.php?order_by=total_price&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                		Price
                        <?php
                            if( $order_by == "total_price" ) {
                                ?>
                                <span class="sort-icon">
                                    <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                                </span>
                                <?php
                            }
                            ?>
                    </a>
                </th>
                <th class="text-right" width="8%">Remaining</th>
                <th class="text-right" width="9%">Grand Total</th>
                <th width="12%" class="text-right">Payment Amount</th>
                <th class="text-center" width="5%">Status</th>
                <th class="text-center" width="10%">Actions</th>
            </tr>
    	</thead>
    	<tbody>
			<?php 
            
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){    
                    // $ts = strtotime( $r["datetime_added"] );
                    // $count = dofetch(doquery( "select count(1) from sales where datetime_added >= '".date("Y-m-01 00:00:00", $ts)."' and datetime_added<'".date("Y-m-d H:i:s", $ts)."'", $dblink ));
                    // $invoice_id = $count["count(1)"]+1;         
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td><?php echo $r["id"];?></td>
                        <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                        <td><?php echo unslash($r["customer_name"]); ?></td>
                        <td><?php echo unslash($r["phone"]); ?></td>
                        <td class="text-right"><?php echo unslash($r["total_items"]); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["net_price"])); ?></td>
                        <td class="text-right"><?php echo curr_format(get_customer_balance($r['customer_id'], datetime_convert($r["datetime_added"]))); ?></td>
                        <td class="text-right"><?php echo curr_format(get_customer_balance($r['customer_id'], datetime_convert($r["datetime_added"])) + $r["net_price"]); ?></td>  
                        <td class="text-right">
                            <?php
                                $payment_amounts = doquery("select * from customer_payment where id = '".$r["customer_payment_id"]."'", $dblink);
                                if(numrows($payment_amounts)>0){
                                    $payment_amount = dofetch($payment_amounts);
                                    echo curr_format($payment_amount["amount"]); 
                                }
                                else{
                                    echo "Payment not received";
                                }
                            ?>
                        </td>                        
                        <td class="text-center"><a href="sales_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
                            <?php
                            if($r["status"]==0){
                                ?>
                                <img src="images/offstatus.png" alt="Off" title="Set Status On">
                                <?php
                            }
                            else{
                                ?>
                                <img src="images/onstatus.png" alt="On" title="Set Status Off">
                                <?php
                            }
                            ?>
                        </a></td>
                        <td class="text-center">
                            <a href="sales_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a href="sales_manage.php?tab=print&id=<?php echo $r['id'];?>"><img title="Print Record" alt="Print" src="images/view.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="sales_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                            <!-- <a style="font-size: 18px;vertical-align: middle;margin-left: 5px;" href="sales_manage.php?tab=print_invoice&id=<?php echo $r['id'];?>"><i class="fa fa-print" aria-hidden="true"></i></a>&nbsp;&nbsp; -->
                        </td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="7" class="actions">
                        <select name="bulk_action" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="6" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "sales", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="13"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
