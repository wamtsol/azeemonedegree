<?php
if(!defined("APP_START")) die("No Direct Access");
//$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=balance_sheet.csv");
    $fh = fopen( 'php://output', 'w' );
    fputcsv($fh,array('','ASSETS'));
    fputcsv($fh,array('CURRENT ASSETS'));
    $total = 0;
    $account_payable = array();
    $sql="select * from account";
    $rs=doquery($sql, $dblink);
    if( numrows($rs) > 0){
        while($r=dofetch($rs)){
            $balance = get_account_balance( $r[ "id" ] );
            if($balance!=0){
                if( $balance >= 0 ) {
                    $total += $balance;
                    fputcsv($fh,array(
                        unslash($r["title"] ),
                        '',
                        curr_format( $balance )
                    ));
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

    fputcsv($fh,array('SUPPLIERS'));
    $supplier_payable = array();
    $sql="select * from supplier";
    $rs=doquery($sql, $dblink);
    if( numrows($rs) > 0){
        while($r=dofetch($rs)){
            $balance = -1*get_supplier_balance( $r[ "id" ] );
            if($balance!=0){
                if( $balance >= 0 ) {
                    $total += $balance;
                    fputcsv($fh,array(
                        unslash($r["supplier_name"] ),
                        '',
                        curr_format( $balance )
                    ));
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

    fputcsv($fh,array('CUSTOMERS'));
    $customer_payable = array();
    $sql="select * from customer";
    $rs=doquery($sql, $dblink);
    if( numrows($rs) > 0){
        while($r=dofetch($rs)){
            $balance = get_customer_balance( $r[ "id" ] );
            if($balance!=0){
                if( $balance >= 0 ) {
                    $total += $balance;
                    fputcsv($fh,array(
                        unslash($r["customer_name"] ),
                        '',
                        curr_format( $balance )
                    ));
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
    fputcsv($fh,array('Store'));
    $store_payable = array();
    $sql="select * from store";
    $rs=doquery($sql, $dblink);
    if( numrows($rs) > 0){
        while($r=dofetch($rs)){
            $balance = get_store_balance( $r[ "id" ] );
            if($balance!=0){
                if( $balance >= 0 ) {
                    $total += $balance;
                    fputcsv($fh,array(
                        unslash($r["title"] ),
                        '',
                        curr_format( $balance )
                    ));
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
        while($r=dofetch($rs)){
            $balance = get_user_balance( $r[ "id" ] );
            if($balance!=0){
                if( $balance >= 0 ) {
                    $total += $balance;
                    fputcsv($fh,array(
                        '','',
                        unslash($r["name"]),
                        curr_format( $balance )
                    ));
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
    fputcsv($fh,array(
        'Total',
        '',
        curr_format( $total )
    ));

    fputcsv($fh,array(''));
    fputcsv($fh,array('','LIABILITIES'));
    if( count($account_payable) > 0){
        fputcsv($fh,array('ACCOUNTS'));
        $total = 0;
        foreach( $account_payable as $account ){
            $total += $account[ "balance" ];
            fputcsv($fh,array(
                $account["name"],
                '',
                curr_format( $account[ "balance" ] )
            ));
        }
    }
    if( count($supplier_payable) > 0 ) {
        fputcsv($fh,array('SUPPLIERS'));
        foreach( $supplier_payable as $account ){
            $total += $account[ "balance" ];
            fputcsv($fh,array(
                $account["name"],
                '',
                curr_format( $account[ "balance" ] )
            ));
        }
    }
    if( count($customer_payable) > 0 ) {
        fputcsv($fh,array('Customers'));
        foreach( $customer_payable as $account ){
            $total += $account[ "balance" ];
            fputcsv($fh,array(
                $account["name"],
                '',
                curr_format( $account[ "balance" ] )
            ));
        }
    }
    if( count($store_payable) > 0 ) {
        fputcsv($fh,array('Store'));
        foreach( $store_payable as $account ){
            $total += $account[ "balance" ];
            fputcsv($fh,array(
                $account["name"],
                curr_format( $account[ "balance" ] )
            ));
        }
    }
    fputcsv($fh,array(
        'Total',
        '',
        curr_format( $total )
    ));
    fclose($fh);
}
die;
?>
