<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();

}

?><?php 
include("../includes/config.php");include("sessiontime.php");

$mode=$_REQUEST['mode'];
$transporter_id=$_REQUEST['transporter_id'];
function chkDuplicateUser($transporter_name,$transporter_id=""){

	if($transporter_name==""){
	return false;
	
	}
	if($transporter_id!="")
	$sql_user="select * from transporter_master where transporter_name='$transporter_name' and transporter_id!='$transporter_id'";
	else
	$sql_user="select * from transporter_master where transporter_name='$transporter_name'";
	$rs_usr=mysql_query($sql_user);
	if(mysql_num_rows($rs_usr)>0) 
	return false;
	else
	return true;

}
if(isset($_POST['bsave'])){
	if( $transporter_id==""){	
		if(chkDuplicateUser(ucwords($_POST['transporter_name']))){
			$sql_insert="INSERT INTO transporter_master (transporter_name )";
			$sql_insert.="VALUES('".trim(addslashes(ucwords($_POST['transporter_name'])))."')";

			 //echo $sql_insert;
			 
				if(mysql_query($sql_insert))	{
					$_SESSION['err_msg']='Message : Record Added';
					$mode="add";
					header("location:./transporter_list.php");
					exit();	
				}	else	{
				$_SESSION['err_msg']="Error : <p>Wrong procedure: Oparation Failed.</p>";	
				//header("location: register.php");
				//exit();		
				}
			}else{
				$_SESSION['err_msg']='Error : In Transporter name already exists';
			
			}	
		}else{ 
			if(chkDuplicateUser(ucwords($_POST['transporter_name']),$_POST['transporter_id'])){
				$sql_edit="UPDATE transporter_master SET transporter_name='".trim(addslashes(ucwords($_POST['transporter_name'])))."'";
				$sql_edit.=" WHERE transporter_id='".trim($_POST['transporter_id'])."'";
		 		//echo $sql_edit;
				 
				if(mysql_query($sql_edit))	{
					$_SESSION['err_msg']='Record Updated';
					$mode='edit';
					header("location:./transporter_list.php");
					exit();	
				}	else	{
					$_SESSION['err_msg']="Error :<p>Wrong procedure: Oparation Failed.</p>";	
				
				}
			}else{
				$_SESSION['err_msg']='Error : Ed Transporter name already exists';
			
			}	
	
	}
 }
if($mode=="del"){
	$sql_delete="DELETE FROM transporter_master WHERE transporter_id='".trim($_REQUEST['transporter_id'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	$_SESSION['err_msg']='Record Deleted';
	header("location: #");
	exit();

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A.S.M.I.</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

<link href="../includes/CalendarControl.css"
      rel="stylesheet" type="text/css">
<script src="../includes/CalendarControl.js"
        language="javascript"></script>
		<script language="JavaScript" type="text/javascript">
function IsNill(frm)
{
	   if(frm.transporter_name.value.length=="")
               {
                       alert("Please enter name");
                       frm.transporter_name.focus();
                       return false;        
               }
	
       return true;
}
function chng(showid){
  if(showid=="r"){
 	document.getElementById("rec").style.display='block';
	document.getElementById("advt").style.display='none';
 }else if(showid=="a"){
 	document.getElementById("advt").style.display='block';
	document.getElementById("rec").style.display='none';
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
			   <?php
 
if($transporter_id !=""){
 $sql_edit=mysql_query("SELECT * FROM transporter_master WHERE transporter_id ='$transporter_id '") or die(mysql_error());
	$r_edit= mysql_fetch_array($sql_edit);
	 
}	
?>
              <table width="99%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?=$mode?>" />
				  <input name="transporter_id" type="hidden" value="<?=$_REQUEST["transporter_id"]?>" />

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Transporter </strong></td>
                      </tr>
                      <tr>
                        <td width="123" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?=$_SESSION['err_msg']?>&nbsp;</td><? $_SESSION['err_msg']=""; ?>
						<td width="84">&nbsp;</td>
                      </tr>
                      <td>Transporter Name (*):</td>
                      <td height="20"><input name="transporter_name" class="input" id="transporter_name" tabindex="3" value="<?=$r_edit["transporter_name"]?>" size="50" /></td>
                    </tr>
                     
                    <tr>
                      <td>&nbsp;</td>
                      <td> 
                        <input type="submit" name="bsave" value="Submit " />
                        <input type="reset" name="cancelbutton" value="Cancel" onclick="JavaScript:window.location.href='transporter_list.php'" />
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
