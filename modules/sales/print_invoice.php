<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$sale=dofetch(doquery("select * from sales where id='".slash($_GET["id"])."'", $dblink));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice</title>
<link rel="stylesheet" type="text/css" href="css/invoice.css">
<style>
@font-face {
    font-family: 'NafeesRegular';
    src: url('fonts/NafeesRegular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;

}
#main{margin:5px;    max-width: 10.27in;
    margin: 5px auto;}
.nastaleeq, #name_in_urdu_text{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed;   }
.clearfix:after {
	content: "";
	display: table;
	clear: both;
}
.summary{
	text-align: right;
}
#logo img {
    width: 290px;
}
td, th {
	border: solid 1px #000;
	padding: 8px 10px;
	text-align: left;
	font-size: 18px;
}
.contact p{
	text-align: center;
	margin: 20px 0px;
	font-size: 16px;
}
#logo,#receipt{text-align:center;}
.contentbox {

}
div#signcompny {
    text-align: center;
}
#logo h6 {
    font-size: 14px;
    margin: 15px 0;
}
div#receipt {
    display: inline-block;
    max-width: 200px;
    border: 1px solid;
    margin: 0 auto;
    width: 100%;
    text-align: center;
    font-size: 14px;
    padding: 5px;
    text-transform: uppercase;
}
</style>
		<script>
		function print_page(){
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
		<div id="receipt">Customer Detail</div>
    </div>
    
    <div class="contentbox">
    	<?php
		$ts = strtotime( $sale["datetime_added"] );
		$count = dofetch(doquery( "select count(1) from sales where datetime_added >= '".date("Y-m-01 00:00:00", $ts)."' and datetime_added<'".date("Y-m-d H:i:s", $ts)."'", $dblink ));
		$invoice_id = $count["count(1)"]+1;
		?>
        <p>Invoice ID: <strong style="float:right">TT-<?php echo $invoice_id; ?>/<?php echo date("m", $ts)?>/<?php echo date("y", $ts)?></strong></p>
        <p>Date/Time: <strong style="float:right"><?php echo datetime_convert($sale["datetime_added"]); ?></strong></p>
        <p>Customer Name: <strong style="float:right"><?php echo unslash($sale["customer_name"]); ?></strong></p>
        <p>Phone: <strong style="float:right"><?php echo unslash($sale["phone"]); ?></strong></p>
        <table cellpadding="0" cellspacing="0" align="center" width="800" border="0" class="items">
            <tr>
                <th width="5%">S#</th>
                <th width="65%">Item</th>
                <th width="10%">Qty</th>
                <th width="10%">Rate</th>
                <th width="10%">Amount</th>
            </tr>
            <?php
            $items=doquery("select a.*, b.type, b.title, b.name_in_urdu, b.name_in_urdu_text from sales_items a left join items b on a.item_id=b.id where sales_id='".$sale["id"]."' order by a.id", $dblink);
            if(numrows($items)>0){
                $sn=1;
                while($item=dofetch($items)){
					$unit = array();
					$price = array();
					$pricenew = array();
					if( $item["type"] == 0 ) {
						$unit[]=$item["quantity"];
						$price[] = curr_format($item["unit_price"]);
					}
					else{
						$children = doquery("select a.quantity, b.unit_price from item_group a inner join items b on a.item_id=b.id where group_item_id = '".$item["item_id"]."'", $dblink);
						while($child=dofetch($children)){
							$unit[] = $child[ "quantity" ]*$item["quantity"];
							$pricenew[] = $item["unit_price"]/$child[ "quantity" ];
							$price[] = curr_format($child["unit_price"]);
						}
					}
                    ?>
                    <tr>
                    	<td style="text-align:center"><?php echo $sn++?></td>
                        <td style="text-align:left;"><?php echo unslash($item["title"])?><br /><?php if(empty($item["name_in_urdu_text"])) { ?><a href="<?php echo $file_upload_root?>item/<?php echo unslash($item["name_in_urdu"]); ?>" target="_blank"><img src="<?php echo $file_upload_root?>item/<?php echo unslash($item["name_in_urdu"]); ?>"  alt="image" title="" style="height:20px;" /></a><?php } else { ?><span class="nastaleeq"><?php echo unslash($item["name_in_urdu_text"]); ?></span><?php }?></td>
                        <td style="text-align:center; font-size:14px;"><?php echo implode("/",$unit)?></td>
                        <td style="text-align:right; font-size:14px;"><?php echo curr_format(implode("/",$pricenew));?></td>
                        <td style="text-align:right; font-size:14px;"><?php echo curr_format($item["total_price"])?></td>
                        
                        
                    </tr>
                    <?php
                }
				?>
				<?php
            }
            ?>
        </table>
        
        <?php if($sale["discount"]>0){?><p style="text-align:right"><strong>TOTAL</strong><strong style="float:right">Rs. <?php echo curr_format($sale["total_price"])?></strong></p>
        <p style="text-align:right"><strong>Discount</strong><strong style="float:right">Rs. <?php echo curr_format($sale["discount"])?></strong></p><?php }?>
        <p style="text-align:right"><strong>TOTAL:</strong><strong style="float:right">Rs. <?php echo curr_format($sale["net_price"])?></strong></p>
		<?php if($sale["cash_receive"]>0){?><p style="text-align:right"><strong>Cash Recieve</strong><strong style="float:right">Rs. <?php echo curr_format($sale["cash_receive"])?></strong></p><?php }?>
		<?php if($sale["cash_return"]>0){?><p style="text-align:right"><strong>Cash Return</strong><strong style="float:right">Rs. <?php echo curr_format($sale["cash_return"])?></strong></p><?php }?>
        <?php $barcode = str_repeat('0', 7-strlen($sale["id"])).$sale["id"];?>
        <p style="text-align: center; padding-top:30px;"><span class="barcode"><img src="barcode.php?text=<?php echo $barcode?>&size=20&print=true" /></span></p>
    </div>
    <div id="signcompny">Software developed by wamtSol http://wamtsol.com/ - 0346 3891 662</div> 
</div>
</body>
</html>
<?php
die;
}