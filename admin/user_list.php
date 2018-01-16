<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();

}
$mode=$_REQUEST['mode'];


?><?php 
include("../includes/config.php");include("sessiontime.php");
if($mode=="del"){
	$sql_delete="DELETE FROM user_master WHERE user_id='".trim($_REQUEST['user_id'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	$_SESSION['err_msg']='Record Deleted';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A.S.M.I.</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function delChk(user_id){
if(confirm("Do you want to delete the user ? ")){
 window.location.href="./user_list.php?user_id="+user_id+"&mode=del";
}

}

</script>
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
              <table width="80%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="30" colspan="2" background="images/login_bg.gif"><img src="images/login_bg.gif" width="15" height="30" align="absmiddle" /><strong>User List</strong></td>
						<td align="right"  background="images/login_bg.gif"><input name="adbutton" type="button" class="btn" value="Add" onclick="JavaScript:window.location.href='user_master.php?mode=add'" /></td>
                      </tr>
                      <tr>
                        <td width="139" height="30" align="center" bgcolor="#e6e6e6"><strong>User Name</strong></td>
						<!--<td width="219" align="center" bgcolor="#e6e6e6"><strong>Email Id</strong></td>-->
						<td width="219" align="center" bgcolor="#e6e6e6"><strong>Status</strong></td>
                        <td width="158" align="center" bgcolor="#e6e6e6"><strong>Action</strong></td>
                      </tr>
					   <?php
					  $sql_usr="select * from user_master order by user_id ";
					  $rs_usr=mysql_query($sql_usr);
					  if(mysql_num_rows($rs_usr)>0){
					  while($row_usr=mysql_fetch_array($rs_usr)){
					  ?>
					  
                      <tr>
                        <td height="30" align="center"><?php echo $row_usr['user_name']; ?></td>
						<!--<td align="center"><?php //echo $row_usr['email']; ?></td>-->
						<td align="center"><?php echo $row_usr['status']; ?></td>
						 
                        <td align="center"> 
                           <input name="edbutton" type="button" class="btn" value="Edit" onclick="JavaScript:window.location.href='./user_master.php?user_id=<?php echo $row_usr['user_id']; ?>&mode=edit'" /> &nbsp; 
						  <? if ($row_usr['user_name']!="admin"){ ?>
 						 <input name="delbutton" type="button" class="btn" value="Delete" onclick="JavaScript:delChk('<?php echo $row_usr['user_id']; ?>')" />
						 <? } ?>
                          </td>
                      </tr>
                      <?php
					   }
					   
					  }else{
					  ?>
					   <tr>
					    <td colspan="4" align="center">No Record Present</td>
                      </tr>
					  <?php
					  }
					   
					   ?>
                      <tr>
                        <td align="center">&nbsp;</td>
						<!--<td align="center">&nbsp;</td>-->
						<td height="30" align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
						<!--<td align="center">&nbsp;</td>-->
                      </tr>
                  </table></td>
                </tr>
            </table></td>
      </tr>
    </table>    </td>
  </tr>
  <!--<tr>
    <td height="37" background="images/footer.gif">&nbsp;</td>
  </tr>-->
  <?php include("footer.inc.php");?>
</table>
</body>
</html>
