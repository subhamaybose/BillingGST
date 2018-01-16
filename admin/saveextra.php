<?php
include("../includes/config.php");include("sessiontime.php");
include("../includes/utils.inc.php");
include('convert.php');
$r_r_cn_no=$_REQUEST["r_r_cn_no"];
$freight=$_REQUEST["freight"];
$bill_no=$_REQUEST["bill_no"];
$packing_qty=$_REQUEST["packing_qty"];
$f_cgst=$_REQUEST["f_cgst"];
$f_sgst=$_REQUEST["f_sgst"];
$f_igst=$_REQUEST["f_igst"];
$net_amount=$_REQUEST["net_amount"];
$net_amount=round($net_amount,0);
 $sql_chk_old = "update sales_order set r_r_cn_no='$r_r_cn_no',freight='$freight',net_amount='$net_amount',packing_qty='$packing_qty',freight_cgst='$f_cgst',freight_sgst='$f_sgst',freight_igst='$f_igst' where bill_no = '" . trim($bill_no) . "'";
 //echo $sql_chk_old;
 mysql_query($sql_chk_old);
 $dr_cr='D';
 post_Update_ledger($bill_no,$net_amount ,$dr_cr);

 $conversion_obj = new Convert($net_amount, $currency="Only"); $prn_net_amount=$conversion_obj->display();
 echo $prn_net_amount;
?>