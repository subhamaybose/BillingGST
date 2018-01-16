<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();

}
?>

<?php 
include("../includes/config.php");include("sessiontime.php");

$mode=$_REQUEST['mode'];
$party_id=$_REQUEST['party_id'];
function chkDuplicateUser($party_code,$party_id=""){

	if($party_code==""){
	return false;
	
	}
	if($party_id!="")
	$sql_user="select * from party_master where party_code='$party_code' and party_id!='$party_id'";
	else
	$sql_user="select * from party_master where party_code='$party_code' ";
	$rs_usr=mysql_query($sql_user);
	if(mysql_num_rows($rs_usr)>0) 
	return false;
	else
	return true;

}
if(isset($_POST['bsave'])){
	if( $party_id==""){	
		if(chkDuplicateUser(strtoupper($_POST['party_code']))){
		$sql_insert="INSERT INTO party_master (party_code,party_name,party_address,party_phone,party_mobile,party_vat_no,party_vat_pcent,party_cst_no,party_cst_pcent,party_pan_no,party_tax_deposit,party_trade_discount,party_type,credit_limit)";
			
			$sql_insert.="VALUES('".trim(strtoupper($_POST['party_code']))."', '".trim(addslashes(ucwords($_POST['party_name'])))."','".trim(addslashes(ucwords($_POST['party_address'])))."',";
			$sql_insert.="'".trim($_POST['party_phone'])."','".trim($_POST['party_mobile'])."','".trim($_POST['party_vat_no'])."',";
			$sql_insert.="'".trim($_POST['party_vat_pcent'])."','".trim($_POST['party_cst_no'])."','".trim($_POST['party_cst_pcent'])."',";
			$sql_insert.="'".trim($_POST['party_pan_no'])."','".trim($_POST['party_tax_deposit'])."','".trim($_POST['party_trade_discount'])."',";
			$sql_insert.="'".trim($_POST['party_type'])."','".trim($_POST['credit_limit'])."')";
			
			 //echo $sql_insert;
			 
				if(mysql_query($sql_insert))	{
					$_SESSION['err_msg']='Message : Record Added';
					$mode="add";
					header("location:./party_list.php?".$qstring);
					exit();	
				}	else	{
				$_SESSION['err_msg']="Error : <p>Wrong procedure: Oparation Failed.</p>";	
				//header("location: register.php");
				//exit();		
				}
			}else{
				$_SESSION['err_msg']='Error : Party Code already exists';
			}
				
		}else{ 
			if(chkDuplicateUser($_POST['party_code'],$_POST['party_id'])){
				$sql_edit="UPDATE party_master SET party_code='".trim(strtoupper($_POST['party_code']))."', ";
				$sql_edit.="party_name='".trim(addslashes(ucwords($_POST['party_name'])))."',party_address='".trim(addslashes(ucwords($_POST['party_address'])))."',";
 				$sql_edit.="party_phone='".trim($_POST['party_phone'])."',party_mobile='".trim($_POST['party_mobile'])."',";
				$sql_edit.="party_vat_no='".trim($_POST['party_vat_no'])."',party_vat_pcent='".trim($_POST['party_vat_pcent'])."',";
				$sql_edit.="party_cst_no='".trim($_POST['party_cst_no'])."',party_cst_pcent='".trim($_POST['party_cst_pcent'])."',";
 				$sql_edit.="party_pan_no='".trim($_POST['party_pan_no'])."',party_tax_deposit='".trim($_POST['party_tax_deposit'])."',party_trade_discount='".trim($_POST['party_trade_discount'])."',";
				$sql_edit.="party_type='".trim($_POST['party_type'])."',credit_limit='".trim($_POST['credit_limit'])."'";
  				$sql_edit.=" WHERE party_id='".trim($_POST['party_id'])."'";
		 		//echo $sql_edit;
				 
				if(mysql_query($sql_edit))	{
					$_SESSION['err_msg']='Record Updated';
					$mode='edit';
					header("location:./party_list.php?".$qstring);
					exit();	
				}	else	{
					$_SESSION['err_msg']="Error :<p>Wrong procedure: Oparation Failed.</p>";	
				
				}
			}else{
				$_SESSION['err_msg']='Error : Duplicate Party Code';
			
			}	
	
	}
 }
if($mode=="del"){
	$sql_delete="DELETE FROM party_master WHERE party_id='".trim($_REQUEST['party_id'])."'";
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

	   if(frm.party_code.value.length=="")
               {
                       alert("Please enter code");
                       frm.party_code.focus();
                       return false;        
               }
	   
	   if(frm.party_name.value.length=="")
               {
                       alert("Please enter name");
                       frm.party_name.focus();
                       return false;        
               }

	   if(frm.party_phone.value.length=="")
               {
                       alert("Please enter phone");
                       frm.party_phone.focus();
                       return false;        
               }
		
       return true;
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
        <td width="200" align="center" valign="top" bgcolor="#e6e6e6"><br />
          <? include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
			   <?php
 
if($party_id !=""){
 $sql_edit=mysql_query("SELECT * FROM party_master WHERE party_id ='$party_id '") or die(mysql_error());
	$r_edit= mysql_fetch_array($sql_edit);
	 
}	
?>
              <table width="400" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?=$mode?>" />
				  <input name="party_id" type="hidden" value="<?=$_REQUEST["party_id"]?>" />
				  

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Party </strong></td>
                      </tr>
                      <tr>
                        <td width="123" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?=$_SESSION['err_msg']?>&nbsp;</td><? $_SESSION['err_msg']=""; ?>
						<td width="84">&nbsp;</td>
                      </tr>
                    <tr>
                      <td>Type :</td>
                      <td height="20">
					  <select name="party_type" id="party_type" tabindex="1">
                        <option value="" selected="selected">--Select--</option>
                        <option value="Both" <? if($r_edit["party_type"]=="Both") echo "selected"; ?>>Both</option>
                        <option value="Customer" <? if($r_edit["party_type"]=="Customer") echo "selected"; ?>>Customer</option>
                        <option value="Vendor" <? if($r_edit["party_type"]=="Vendor") echo "selected"; ?>>Vendor</option>
 					   </select>
					  </td>
                    </tr>
					<tr>
                      <td>
                        <label>Code (*): </label></td>
                      <td height="20"><input name="party_code" class="input" id="party_code" value="<?=$r_edit["party_code"]?>" tabindex="2" size="20" /></td>
                    </tr>
					<tr>
                      <td>Name (*):</td>
                      <td height="20"><input name="party_name" class="input" id="party_name" tabindex="3" value="<?=$r_edit["party_name"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Address :</td>
                      <td height="20"><textarea name="party_address" tabindex="4" cols="20" rows="3" style="width:250px;"><?=$r_edit["party_address"]?></textarea></td>
                    </tr>
                    <tr>
                      <td>Phone No (*):</td>
                      <td height="20"><input name="party_phone" class="input" id="party_phone" tabindex="5" value="<?= $r_edit["party_phone"]?>" size="20" /></td>
                    </tr> 
					<tr>
                      <td>Mobile :</td>
                      <td height="20"><input name="party_mobile" class="input" id="party_mobile" tabindex="6" value="<?= $r_edit["party_mobile"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Vat No :</td>
                      <td height="20"><input name="party_vat_no" class="input" id="party_vat_no" tabindex="7" value="<?= $r_edit["party_vat_no"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Percentage :</td>
                      <td height="20"><input name="party_vat_pcent" class="input" id="party_vat_pcent" tabindex="8" value="<?= $r_edit["party_vat_pcent"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>CST No :</td>
                      <td height="20"><input name="party_cst_no" class="input" id="party_cst_no" tabindex="9" value="<?= $r_edit["party_cst_no"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Percentage :</td>
                      <td height="20"><input name="party_cst_pcent" class="input" id="party_cst_pcent" tabindex="10" value="<?= $r_edit["party_cst_pcent"]?>" size="20" /></td>
                    </tr>
                    <tr>
                      <td>Pan No:</td>
                      <td height="20"><input name="party_pan_no" class="input" id="party_pan_no" tabindex="11" value="<?=$r_edit["party_pan_no"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Tax Deposited:</td>
                      <td height="20"><input name="party_tax_deposit" class="input" id="party_tax_deposit" tabindex="12" value="<?=$r_edit["party_tax_deposit"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Trade Discount:</td>
                      <td height="20"><input name="party_trade_discount" class="input" id="party_trade_discount" tabindex="13" value="<?=$r_edit["party_trade_discount"]?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Credit Limit:</td>
                      <td height="20"><input name="credit_limit" class="input" id="credit_limit" tabindex="14" value="<?=$r_edit["credit_limit"]?>" size="20" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td> 
                        <input type="submit" name="bsave" value="Submit " tabindex="15" />
                        <input type="reset" name="cancelbutton" value="Cancel" onclick="JavaScript:window.location.href='party_list.php'" />
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
