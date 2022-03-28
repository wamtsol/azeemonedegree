<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=StockList.csv");
    $fh = fopen( 'php://output', 'w' );
    if($type==0){
        $type = "Simple";
    }
    elseif($type==1){
        $type = "Group";
    }
    else{
        $type = "All Stock";
    }
    fputcsv($fh,array('Stock List'));
    fputcsv($fh,array('Type:', $type));
    fputcsv($fh,array('S.no','Name','Packing','Rate/Piece','Packet Price','Quantity'));
    $sn=1;
    //$total = 0;
    while($r=dofetch($rs)){
        $unit = array();
		if( $r["type"] == 0 ) {
			$unit[]=1;
		}
		else{
			$children = doquery("select quantity from item_group where group_item_id = '".$r["id"]."'", $dblink);
			while($child=dofetch($children)){
				$unit[] = $child[ "quantity" ];
			}
		}
       // $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            unslash($r["title"]),
            implode("/", $unit),
            curr_format($r["unit_price"]/$unit[0]),
            curr_format($r["unit_price"]),
            unslash($r["quantity"])
        ));
    }
    //fputcsv($fh,array('','','Total:',curr_format($total)));
    fclose($fh);
}
die;
?>
