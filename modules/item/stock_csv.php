<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=StockList.csv");
    $fh = fopen( 'php://output', 'w' );
    fputcsv($fh,array('Package List'));
    fputcsv($fh,array('S.no','Name','Rate/Piece','Package Price','Quantity'));
    $sn=1;
    //$total = 0;
    while($r=dofetch($rs)){
        $unit = array();
			$unit[]=1;
		
		
       // $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            unslash($r["title"]),
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
