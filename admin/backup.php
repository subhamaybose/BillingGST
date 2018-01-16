<?php
ob_start();
session_start();

include("../includes/config.php");include("sessiontime.php");

$filename=$path."asmi_backup_".date("Y_m_d_h_i_s_a").".sql";


//$backup = "$mysqldumppath --opt -h $connect_string -u $connect_username  --p $connect_password $connect_db  > $filename";
$backup = "$mysqldump -h $connect_string -u $connect_username  --p $connect_password $connect_db  > $filename";
//echo $backup;

system($backup, $return);
$_SESSION['err_msg']="database backup taken into  file".$filename;

header('location:restore_master.php');
exit();
?>