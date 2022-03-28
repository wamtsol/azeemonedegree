<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=town.csv");
    $fh = fopen( 'php://output', 'w' );
    if($town==1){
        $town = "Home Town";
    }
    elseif($town==2){
        $town = "Out Of Town";
    }
    else{
        $town = "All Town";
    }
    fputcsv($fh,array('Town:', $town));
    fputcsv($fh,array('S.no','Date/Time','Customer','Address','Phone Number'));
    $sn=1;
    //$total = 0;
    while($r=dofetch($rs)){
       // $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            datetime_convert($r["datetime_added"]),
            get_field($r["customer_id"], "customer", "customer_name" ),
            unslash($r["address"]),
            unslash($r["phone"])
        ));
    }
    //fputcsv($fh,array('','','Total:',curr_format($total)));
    fclose($fh);
}
die;
?>
