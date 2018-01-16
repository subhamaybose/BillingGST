<?php
ob_start();
session_start();

include("../includes/config.php");include("sessiontime.php");

$filename=$path."asmi_restore_".date("Y_m_d_h_s_i").".sql";


$restore = "$restorepath -h $connect_string -u $connect_username --password=$connect_password $connect_db < $filename";

system($restore, $return);

?>