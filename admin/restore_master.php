<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();
}

?><?php 
include("../includes/config.php");include("sessiontime.php");
 if($_POST["restore_file_name"]!=""){
 	$filename=$path.$_POST['restore_file_name'];
	$restore = "$restorepath -h $connect_string -u $connect_username --password=$connect_password $connect_db < $filename";
	 
	system($restore, $return);
	$_SESSION['err_msg']="database restored from file".$filename;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A.S.M.I.</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="111" align="center" valign="bottom" background="images/header.gif"><table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="26%"><img src="images/logo.jpg" width="100" height="107" /></td>
        <td width="70%" align="center" class="header">Associated Scientific Manufacturing Industries (<?php echo $_SESSION['fin_year']."-".($_SESSION['fin_year']+1)?>)</td>
        <td width="4%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="420" align="center" bgcolor="#FFFFFF"><table width="100%" height="420" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="200" align="left" valign="top" bgcolor="#e6e6e6"><? include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
			    
              <table width="99%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?=$_SERVER['PHP_SELF']?>" method="post"   name="frmregistration" id="frmregistration"  >
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Select Restore File </strong></td>
                      </tr>
                      <tr>
                        <td width="123" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?=$_SESSION['err_msg']?>&nbsp;</td><? $_SESSION['err_msg']=""; ?>
						<td width="84">&nbsp;</td>
                      </tr>
                      <td>File Name (*):</td>
                      <td height="20"> <input name="restore_file_name" type="file" id="restore_file_name"></td>
                    </tr>
                     
                    <tr>
                      <td>&nbsp;</td>
                      <td> 
                        <input type="submit" name="bsave" value="Submit " />
                         
                       </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
				  </form></td>
                </tr>
            </table></td>
      </tr>
    </table>    
	</td>
  </tr>
  <!--<tr>
    <td height="37" background="images/footer.gif">&nbsp;</td>
  </tr>-->
  <?php include("footer.inc.php");?>
</table>
</body>
</html>
