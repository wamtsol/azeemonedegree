<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title"><?php echo $customer["customer_name"]?></h1>
  	<ol class="breadcrumb">
    	<li class="active"><?php echo $customer["phone"]?></li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="customer_manage.php?tab=list" class="btn btn-light editproject">Back to List</a>
            <a class="btn print-btn" href="customer_manage.php?tab=report_debit_credit_print&id=<?php echo $customer["id"];?>"><i class="fa fa-print" aria-hidden="true"></i></a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <input type="hidden" name="tab" value="report_debit_credit" />
                <input type="hidden" name="id" value="<?php echo $customer["id"]?>" />
                <span class="col-sm-2 text-to">From</span>
                <div class="col-sm-3">
                    <input type="text" title="Enter Date From" name="start_date" id="start_date" placeholder="" class="form-control date-picker"  value="<?php echo $start_date?>" >
                </div>
                <span class="col-sm-2 text-to">To</span>
                <div class="col-sm-3">
                    <input type="text" title="Enter Date To" name="end_date" id="end_date" placeholder="" class="form-control date-picker"  value="<?php echo $end_date?>" >
                </div>
                
                <div class="col-sm-2 text-left">
                    <!-- <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" /> -->
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
                <th width="5%" class="text-center">S.no</th>
                <th>
                    <a href="customer_manage.php?tab=report_debit_credit&id=<?php echo $customer["id"]?>order_by=date&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                        Date
                        <?php
                        if( $order_by == "date" ) {
                            ?>
                            <span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
                            <?php
                        }
                        ?>
                    </a>
                </th>
                <th>Details</th>
                <th class="text-right">Debit</th>
                <th class="text-right" >Credit</th>
                <th class="text-right" >Balance</th>
            </tr>
    	</thead>
    	<tbody>
			<?php 
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				?>
				<tr>
                	<td colspan="2"></td>
                    <td><?php echo $order == 'desc'?'Closing':'Opening'?> Balance</td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo curr_format( $balance )?></td>
                </tr>
				<?php
                $invoice_id = 0;
				while($r=dofetch($rs)){  
                    // $ts = strtotime( $r["date"] );
                    // $count = dofetch(doquery( "select count(1) from sales where datetime_added >= '".date("Y-m-01 00:00:00", $ts)."' and datetime_added<'".date("Y-m-d H:i:s", $ts)."'", $dblink ));
                    // $invoice_id = $count["count(1)"]+1;           
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo datetime_convert($r["date"]); ?></td>
                        <td><?php echo unslash($r["details"]); ?></td>
                        <td class="text-right"><?php echo $r["debit"]>0?curr_format($r["debit"]):''; ?></td>
                        <td class="text-right"><?php echo $r["credit"]>0?curr_format($r["credit"]):''; ?></td>
                        <td class="text-right"><?php if($order == 'asc'){$balance += ($r["credit"])-$r["debit"]*($order == 'desc'?'-1':1);} echo curr_format( $balance ); if($order == 'desc'){$balance += ($r["credit"]-$r["debit"])*($order == 'desc'?'-1':1);} ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                	<td colspan="2"></td>
                    <td><?php echo $order != 'desc'?'Closing':'Opening'?> Balance</td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo curr_format( $balance )?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
