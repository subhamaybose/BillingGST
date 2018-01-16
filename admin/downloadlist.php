<?php
ob_start();
session_start();
//echo "hello".$_SESSION["login"];
if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();
}
 include("../includes/config.php");include("sessiontime.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <td height="450" align="center" bgcolor="#FFFFFF"><table width="100%" height="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="15%" align="left" valign="top" bgcolor="#e6e6e6"> <?php include("left_menu.php");?>
        </td>
		<td width="85%" align="center" valign="top" bgcolor=""> 
		<table border="0" cellspacing="2" cellpadding="2" align="center">
		<tr>
			<td width="35%" height="30" align="center" bgcolor="#e6e6e6"><strong>DATE</strong></td>
			<td width="65%" height="30" align="center" bgcolor="#e6e6e6"><strong>BACKUP DOWNLOAD LINK</strong></td>
		</tr>
		<?php
		$sql = "SELECT * FROM `backup` ORDER BY `backup_id` DESC";
		$tok = mysql_query($sql);
		if(mysql_num_rows($tok)>0){
		while($row_db=mysql_fetch_array($tok)){
		?>
		<tr>
			<td width="35%"  height="30" align="center"><?php echo date("d/m/Y \a\\t h:i:s a",$row_db["backup_date"]);?></td>
			<td width="50%"  height="30" align="center"><a href="./backup/<?php echo $row_db["backup_file"];?>.sql"><?php echo $row_db["backup_file"];?></a></td>
		</tr>
		<?php 
		}
		}else{
		?>
		<tr>
            <td colspan="2" align="center">No Record Present</td>
        </tr>
		<?php
		}
		?>
		</table>
        </td>	
      </tr>
    </table>    </td>
  </tr>
  
  <!--<tr>
    <td height="37" background="images/footer.gif" align="right">
	Developed By<a href="http://www.dtechsystem.net/" title="Developed By DTech System" target="_blank">DTech System</a>All Rights Reserved.
	</td>
  </tr>-->
  
  <?php include("footer.inc.php");?>
  
</table>
</body>
</html>
