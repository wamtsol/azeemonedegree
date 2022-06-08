<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title">Manage Package</h1>
  	<ol class="breadcrumb">
    	<li class="active">All the administrators who can use the manage package</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="items_manage.php?tab=add" class="btn btn-light editproject">Add New Record</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <!-- <a class="btn print-btn" href="items_manage.php?tab=report"><i class="fa fa-print" aria-hidden="true"></i></a> -->
            <!-- <a class="btn btn-primary" href="items_manage.php?tab=update_items"><i  aria-hidden="true"></i>Update Item</a> -->
            <a class="btn btn-primary" href="items_manage.php?tab=stock_csv"><i  aria-hidden="true"></i>Package CSV</a>
        </div> 
    </div>  
</div>        
  	</div>
    
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-3">
                	<select name="stock" id="stock">
                    	<option value="">Select Stock</option>
                        <option value="0"<?php if($stock=="0") echo ' selected="selected"';?>>In Stock</option>
                        <option value="1"<?php if($stock=="1") echo ' selected="selected"';?>>Low Stock</option>
                        <option value="2"<?php if($stock=="2") echo ' selected="selected"';?>>Out of Stock</option>
                    </select>
                </div>
                <div class="col-sm-3 col-xs-8">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
                </div>
                <div class="col-sm-3 col-xs-2 text-left">
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
                <th width="5%" class="text-center">S.no</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <!-- <th width="15%">Urdu Name</th> -->
                <th>
                	<a href="items_manage.php?order_by=title&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Title
                    	<?php
						if( $order_by == "title" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
                    </a>
                </th>
                <th class="text-right" width="9%">
                	<a href="items_manage.php?order_by=unit_price&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Unit Price
                        <?php
						if( $order_by == "unit_price" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
 					</a>
                </th>
                <th class="text-right" width="9%">
                	<a href="items_manage.php?order_by=quantity&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    	Quantity
                		<?php
						if( $order_by == "quantity" ) {
							?>
							<span class="sort-icon">
                                <i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
                            </span>
							<?php
						}
						?>
 					</a>
                </th>
                <th class="text-center" width="5%">Status</th>
                <th class="text-center" width="5%">Actions</th>
            </tr>
    	</thead>
    	<tbody>
			<?php 
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <!-- <td class="text-black"><span class="nastaleeq"><?php echo unslash($r["name_in_urdu_text"]); ?></span></td> -->
                        <td><?php echo unslash($r["title"]); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["unit_price"])); ?></td>
                        <td class="text-right"><?php echo unslash($r["quantity"]); ?></td>
                        <td class="text-center"><a href="items_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                        <td align="center">
                            <a href="items_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <!-- <a href="items_manage.php?tab=print&id=<?php echo $r['id'];?>" class="barcode_print_button"><img title="Print Label" alt="Print" src="images/view.png"></a>&nbsp;&nbsp; -->
                            <a onclick="return confirm('Are you sure you want to delete')" href="items_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="3" class="actions">
                        <select name="bulk_action" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="4" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "items", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="7"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>
