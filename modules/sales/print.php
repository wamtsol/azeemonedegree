<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$sale=dofetch(doquery("select * from sales where id='".slash($_GET["id"])."'", $dblink));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Bill</title>
<style>
@font-face {
    font-family: 'NafeesRegular';
    src: url('fonts/NafeesRegular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;

}
.nastaleeq, #name_in_urdu_text{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed;   }
.clearfix:after {
	content: "";
	display: table;
	clear: both;
}
#main {
width:71mm;
border:0;
}
a {
	color: #5D6975;
	text-decoration: underline;
}
body {
	position: relative;
	margin: 0;
	color: #000;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	padding: 0px
}
p{margin:0 0 5px 0}
#logo {
	text-align: center;
	margin-bottom: 10px;
}
#logo img {
    width: 74%;
}
#right_title {
	font-size: 18px;
	font-style: italic;
	font-weight: bolder;
	float: right;
	margin-right: 5px;
	text-decoration: underline;
}
#center_title {
	font-size: 22px;
	font-style: normal;
	font-weight: bold;
	float: right;
	padding-top: 45px;
	text-transform: uppercase
}
#inv_status {
	margin-bottom: 30px;
	font-size: 14px;
}
#inv_status_alrt {
	font-size: 16px;
	font-weight: bold;
	text-align: center;
	border: thin solid #666;
	float: right;
	margin-right: 5px;
	position: relative;
	padding-top: 5px;
	padding-right: 30px;
	padding-bottom: 5px;
	padding-left: 30px;
}
#project {
	float: left;
	font-size: 14px;
}
#project div {
	margin-bottom: 5px;
}
#customer {
	float: right;
	text-align: center;
	line-height: 1em;
}
#jbnum {
	width: 200px;
	padding: 5px;
	line-height: 1em;
	margin-bottom: 5px;
	background-color: #444;
	color: #fff;
}
#customer span {
	color: #000000;
	text-align: left;
	width: 52px;
	margin-right: 10px;
	display: inline-block;
	font-size: 13px;
}
#company {
	float: right;
	text-align: right;
}
table {
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0;
	margin-bottom: 10px;
}
table tr:nth-child(2n-1) td {
	background: #F5F5F5;
}
table th, table td {
	text-align: left;
}
table th {
    border: 1px solid #fff;
    color: #fff;
    font-weight: bold;
    line-height: 0.9em;
    padding: 10px 0;
    text-align: center;
	background-color:#000;
    white-space: nowrap;
}
.data-table td{border:1px solid #afafaf;}
.data-table td strong{text-align:right;display:block}
#th_center {
	text-align: center;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #666666;
}
#cinfo_table {
	height: auto;
	width: 49%;
	float: left;
}
#cinfo_table_cntr {
	height: auto;
	width: 260px;
	margin-left: 266px;
}
#cinfo_table_rgt {
	height: auto;
	width: 49%;
	float: right;
}
#inchk_table {
	float: left;
	width: 393px;
}
#inchk_table td {
	border: thin solid #CCCCCC;
	padding-top: 1px;
	padding-bottom: 1px;
	line-height: 1.5em;
}
#othrd_table {
	float: right;
	width: 393px;
}
#othrd_table td {
	border: thin solid #CCC;
	padding-top: 1px;
	padding-bottom: 1px;
	line-height: 1.5em;
}
.tableamount {
	text-align: right;
}
#acc {
	border: thin solid #000;
	padding-right: 15px;
	display: block;
	line-height: 20px;
}
#rbr {
	border-right-width: thin;
	border-right-style: solid;
	border-right-color: #000;
	background-color: #ccc;
	width: 100px;
	white-space: nowrap;
	float: left;
	padding-left: 10px;
}
#acc span {
	margin-left: 15px;
}
table .service, table .desc {
	text-align: left;
}
table td {
	text-align: right;
	padding-top: 10px;
	padding-right: 2px;
	padding-bottom: 10px;
	padding-left: 2px;
	font-size:12px;
}
table tr{ font-size:10px}
table td.service, table td.desc {
	vertical-align: top;
}
table td.unit, table td.qty, table td.total {
	font-size: 1.2em;
}
table td.grand {
	border-top: 1px solid #5D6975;
	;
}
#notices {
	margin-top: 20px;
	float: left;
	clear: both;
	width: 100%;
}

#signcompny {
    border-top: thin solid #000;
    margin: 15px 0 0;
    padding-top: 10px;
    text-align: center;
}
#signcus {
	text-align: center;
	border-top-width: thin;
	border-top-style: solid;
	border-top-color: #000;
	margin-right: 5px;
	margin-top: 100px;
}
footer {
	color: #5D6975;
	width: 100%;
	height: 30px;
	position: absolute;
	bottom: 0;
	border-top: 1px solid #C1CED9;
	padding: 8px 0;
	text-align: center;
}
.comnme {
	font-size: 22px;
	font-weight: bold;
}
.contentbox{display:block}

#logo {
    display: block;
    font-size: 20px;
    font-weight: bold;
    margin: 10px ​auto;
    padding: 5px;
}
#receipt {
    border: 1px solid;
    border-radius: 3px;
    display: block;
    font-size: 18px;
    font-weight: bold;
    line-height: 13px;
    margin: 5px auto 10px;
    padding: 5px;
    text-align: center;
    width: 82px;
}

#logo > p {
    font-size: 11px;
    font-weight: normal;
    padding: 5px;
}
h6 {
    margin: 0px 0 0;
}
</style>
		<script>
		function print_page(){
			window.print(); return;
			printer = '<?php echo get_config( 'thermal_printer_title' );?>';
			printers = jsPrintSetup.getPrintersList().split(",");
			if( printers.indexOf( printer ) !== -1 ) {
				jsPrintSetup.setPrinter( printer );
				jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);
				// set top margins in millimeters
				jsPrintSetup.setOption('marginTop', 0);
				jsPrintSetup.setOption('marginBottom', 0);
				jsPrintSetup.setOption('marginLeft', 0);
				jsPrintSetup.setOption('marginRight', 0);
				// set page header
				jsPrintSetup.setOption('headerStrLeft', '');
				jsPrintSetup.setOption('headerStrCenter', '');
				jsPrintSetup.setOption('headerStrRight', '');
				// set empty page footer
				jsPrintSetup.setOption('footerStrLeft', '');
				jsPrintSetup.setOption('footerStrCenter', '');
				jsPrintSetup.setOption('footerStrRight', '');
				jsPrintSetup.setOption('printBGColors', 1);
				// Suppress print dialog
				jsPrintSetup.setSilentPrint(true);
				// Do Print
				jsPrintSetup.printWindow(window);
				// Restore print dialog
				//jsPrintSetup.setSilentPrint(false);
			}
			else {
				alert( printer + " is not installed." );
			}
			
		}
        </script>
</head>
<body onload="print_page();">
<div id="main">
    <div id="logo"><img src="<?php echo $file_upload_root;?>config/<?php echo get_config("reciept_logo");?>" />
    <?php echo get_config("address_phone")?>
    </div>
    <div id="receipt">BILL</div>
	<p style="text-align: center;font-weight: bold;">
		<?php
			$payment_amounts = doquery("select * from customer_payment where id = '".$sale["customer_payment_id"]."'", $dblink);
			if(numrows($payment_amounts)>0){
				$payment_amount = dofetch($payment_amounts);
				echo "PAID"; 
			}
			else{
				echo "NOT PAID";
			}
		?>
	</p>
    <div class="contentbox">
    	<?php
		// $ts = strtotime( $sale["datetime_added"] );
		// $count = dofetch(doquery( "select count(1) from sales where datetime_added >= '".date("Y-m-01 00:00:00", $ts)."' and datetime_added<'".date("Y-m-d H:i:s", $ts)."'", $dblink ));
		// $invoice_id = $count["count(1)"]+1;
		$balance = 0;
        $balance = get_customer_balance($sale["customer_id"], $sale["datetime_added"]);
		?>
        <p>Bill #: <strong style="float:right"><?php echo $sale["id"]; ?></strong></p>
        <p>Date/Time: <strong style="float:right"><?php echo datetime_convert($sale["datetime_added"]); ?></strong></p>
        <p>Customer Name: <strong style="float:right"><?php echo get_field($sale["customer_id"], "customer", "customer_name"); ?></strong></p>
        <p>Phone: <strong style="float:right"><?php echo get_field($sale["customer_id"], "customer", "phone"); ?></strong></p>
        <table cellpadding="0" cellspacing="0" align="center" width="800" border="0" class="items">
            <tr>
                <th width="5%">Qty</th>
                <th width="65%">Package</th>
                <!-- <th width="10%">Qty</th> -->
                <th width="10%">Rate</th>
                <th width="10%">Amount</th>
            </tr>
            <?php
            $items=doquery("select a.*, b.title from sales_items a left join items b on a.item_id=b.id where sales_id='".$sale["id"]."' order by a.id", $dblink);
            if(numrows($items)>0){
                $sn=1;
                while($item=dofetch($items)){
					$unit = array();
					$price = array();
					$pricenew = array();
			
						$unit[]=$item["quantity"];
						$price[] = $item["unit_price"];
					
                    ?>
                    <tr>
                    	<td style="text-align:center"><?php echo implode("/",$unit)?></td>
                        <td style="text-align:left;"><?php echo unslash($item["title"])?></td>
                        <!-- <td style="text-align:center; font-size:9px;"><?php echo implode("/",$unit)?></td> -->
                        <td style="text-align:right; font-size:9px;"><?php echo implode("/",$price);?></td>
                        <td style="text-align:right; font-size:9px;"><?php echo curr_format($item["total_price"])?></td>
                        
                        
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <hr style="border:0; border-top:1px solid #999">
        <?php if($sale["discount"]>0){?><p><strong>TOTAL</strong><strong style="float:right">Rs. <?php echo curr_format($sale["total_price"])?></strong></p>
        <p><strong>Discount</strong><strong style="float:right">Rs. <?php echo curr_format($sale["discount"])?></strong></p><?php }?>
        <p><strong>Remaining</strong><strong style="float:right">Rs. <?php echo $balance;?></strong></p>
		<p><strong>Current Bill</strong><strong style="float:right">Rs. <?php echo curr_format($sale["net_price"]);?></strong></p>
		<p><strong>TOTAL</strong><strong style="float:right">Rs. <?php echo curr_format($balance+$sale["net_price"]);?></strong></p>
		<p>
			<strong>Paid</strong>
			<strong style="float:right">Rs. 
			<?php
				$paid = 0;
				$payment_amounts = doquery("select * from customer_payment where id = '".$sale["customer_payment_id"]."'", $dblink);
				if(numrows($payment_amounts)>0){
					$payment_amount = dofetch($payment_amounts);
					echo curr_format($payment_amount["amount"]); 
					$paid = curr_format($payment_amount["amount"]);
				}
				else{
					echo "0";
				}
			?>
			</strong>
		</p>
		<p><strong>Remaining Balance</strong><strong style="float:right">Rs. <?php echo curr_format(get_customer_balance($sale['customer_id']));?></strong></p>
		<?php $barcode = str_repeat('0', 7-strlen($sale["id"])).$sale["id"];?>
        <!-- <p style="text-align: center; padding-top:30px;"><span class="barcode"><img src="barcode.php?text=<?php echo $barcode?>&size=20&print=true" /></span></p> -->
    </div>
    <div id="signcompny"></div> 
	<!-- <div id="signcompny">Software developed by wamtSol http://wamtsol.com/ - 0346 3891 662</div>  -->
</div>
</body>
</html>
<?php
die;
}