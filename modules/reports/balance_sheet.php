<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Balance Sheet</h1>
  	<ol class="breadcrumb">
    	<li class="active">Balance Sheet</li>
  	</ol>
    <span class="right"><a class="btn print-btn " href="report_manage.php?tab=report_csv">CSV</a></span>
</div>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="50%">Assets</th>
                <th>Liabilities</th>
            </tr>
    	</thead>
    	<tbody>
        	<tr>
				<td>
					<table  class="table table-hover list">
						<thead>
                            <tr>
                                <th colspan="2">Current Assets</th>
                            </tr>
                        </thead>
						<?php 
						$total = 0;
						$account_payable = array();
						$sql="select * from account";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = get_account_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["title"] ); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$account_payable[] = array(
											"name" => unslash($r["title"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						?>
						<thead>
                            <tr>
                                <th colspan="2">Suppliers</th>
                            </tr>
                        </thead>
						<?php
						$supplier_payable = array();
						$sql="select * from supplier";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = -1*get_supplier_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["supplier_name"] ); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$supplier_payable[] = array(
											"name" => unslash($r["supplier_name"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						?>
						<thead>
                            <tr>
                                <th colspan="2">Customers</th>
                            </tr>
                        </thead>
						<?php
						$customer_payable = array();
						$sql="select * from customer";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = get_customer_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["customer_name"] ); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$customer_payable[] = array(
											"name" => unslash($r["customer_name"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						?>
						<thead>
                            <tr>
                                <th colspan="2">Store</th>
                            </tr>
                        </thead>
						<?php
						$store_payable = array();
						$sql="select * from store";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = get_store_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["title"] ); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$store_payable[] = array(
											"name" => unslash($r["title"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						$sql="select * from admin where status=1 order by name";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = get_user_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["name"]); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$account_payable[] = array(
											"name" => unslash($r["name"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right"><?php echo curr_format( $total )?></th>
                        </tr>
                  	</table>
              	</td>
                <td>
					<table class="table table-hover list">
						<?php 
						if( count($account_payable) > 0){
							?>
							<thead>
                                <tr>
                                    <th colspan="2">Accounts</th>
                                </tr>
                            </thead>
							<?php
							$sn=1;
							$total = 0;
							foreach( $account_payable as $account ){
								$total += $account[ "balance" ];
								?>
								<tr>
									<td><?php echo $account["name"]; ?></td>
									<td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
								</tr>
								<?php 
								$sn++;
							}
						}
							if( count($supplier_payable) > 0 ) {
								?>
								<thead>
									<tr>
										<th colspan="2">Suppliers</th>
									</tr>
								</thead>
								<?php
								foreach( $supplier_payable as $account ){
									$total += $account[ "balance" ];
									?>
									<tr>
										<td><?php echo $account["name"]; ?></td>
										<td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
									</tr>
									<?php 
									$sn++;
								}
							}
							if( count($customer_payable) > 0 ) {
								?>
								<thead>
									<tr>
										<th colspan="2">Customers</th>
									</tr>
								</thead>
								<?php
								foreach( $customer_payable as $account ){
									$total += $account[ "balance" ];
									?>
									<tr>
										<td><?php echo $account["name"]; ?></td>
										<td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
									</tr>
									<?php 
									$sn++;
								}
							}
							if( count($store_payable) > 0 ) {
								?>
								<thead>
									<tr>
										<th colspan="2">Store</th>
									</tr>
								</thead>
								<?php
								foreach( $store_payable as $account ){
									$total += $account[ "balance" ];
									?>
									<tr>
										<td><?php echo $account["name"]; ?></td>
										<td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
									</tr>
									<?php 
									$sn++;
								}
							}
							?>
                            <tr>
                            	<th>Total</th>
                                <th class="text-right"><?php echo curr_format( $total )?></th>
                            </tr>
                  	</table>
              	</td>
           	</tr>
    	</tbody>
  	</table>
</div>
