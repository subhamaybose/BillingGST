<?php
	session_start();
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	
	//Get item list
	//$sql_im = "select im.item_sale_rate as item_sale_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name from item_master im JOIN capacity_master cm on im.capacity_id = cm.capacity_id";
	$sql_im = "select im.item_purchase_rate as item_purchase_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_description as item_description, im.item_id as item_id, cm.capacity_name as capacity_name, c.category_name as category_name from item_master im LEFT JOIN capacity_master cm on im.capacity_id = cm.capacity_id LEFT JOIN category c ON c.category_id = im.category_id where im.item_type <> 'Finished Goods'";
	$tok_im = mysql_query($sql_im);
	
	
	//The unit master
	$sql_um = "select * from unit_master";
	$tok_um = mysql_query($sql_um);
	
	
	if(isset($_POST['item_requisition_date']))
	{
		//The main SQL			
		$sql_main = "insert into item_requisition set item_requisition_date = '" . date('Y-m-d', strtotime($_POST['item_requisition_date'])) . "', user_id = '" . $_SESSION["login"] . "', sales_order_id = '" . $_POST['sales_order_id'] . "', direction = '" . $_POST['direction'] . "'";
		
		$tok_main = mysql_query($sql_main);
		
		$requisition_id = mysql_insert_id();

		//Once the main SQL is saved, the details part
		foreach($_POST['item_id'] as $item_key => $item_val)
		{
			if(trim($item_val) != '')
			{
				$sql_sub = "insert into item_requisition_details set item_requisition_id = '" . $requisition_id . "', item_id = '" . $item_val . "', item_qty = '" . $_POST['item_qty'][$item_key] . "', item_unit = '" . $_POST['item_unit'][$item_key] . "', capacity = '" . $_POST['capacity'][$item_key] . "'";
				$tok_sub = mysql_query($sql_sub);
				$sql_stk = "update item_master set item_stock = item_stock - " . $_POST['item_qty'][$item_key] . " where item_id = '" . $item_val . "'";
			//echo $sql_stk;
			mysql_query($sql_stk);
/*
				//Now the stock trick, check direction
				if($_POST['direction'] == 'Raw Material -> WIP')
				{
					//Raw Material stock decreases, WIP stock increases
					//To fir Raw material ghatao
					$sql_stk = 'update item_master set item_stock = item_stock - ' . $_POST['item_qty'][$item_key] . ' where item_name = \'' . $_POST['item_name'][$item_key] . '\' and item_description = \'' . $_POST['item_decription'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\') and unit_id = \'' . $_POST['item_unit'][$item_key] . '\' and category_id = (select category_id from category where category_name = \'' . $_POST['category'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\')) and item_type = \'Raw Material\'';
					//echo $sql_stk . '<br /><br />';
					$tok_stk = mysql_query($sql_stk);	
					
					//Ab WIP Stock badhao
					$sql_stk = 'update item_master set item_stock = item_stock + ' . $_POST['item_qty'][$item_key] . ' where item_name = \'' . $_POST['item_name'][$item_key] . '\' and item_description = \'' . $_POST['item_decription'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\') and unit_id = \'' . $_POST['item_unit'][$item_key] . '\' and category_id = (select category_id from category where category_name = \'' . $_POST['category'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\')) and item_type = \'Work-In-Progress\'';
					//echo $sql_stk . '<br /><br />';
					echo $sql_stk."<br>";
					$tok_stk = mysql_query($sql_stk);
					//die();
				}
				else
				{
					//WIP stock decreases, Finished good stock increases
					//To fir WIP ghatao
					$sql_stk = 'update item_master set item_stock = item_stock - ' . $_POST['item_qty'][$item_key] . ' where item_name = \'' . $_POST['item_name'][$item_key] . '\' and item_description = \'' . $_POST['item_decription'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\') and unit_id = \'' . $_POST['item_unit'][$item_key] . '\' and category_id = (select category_id from category where category_name = \'' . $_POST['category'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\')) and item_type = \'Work-In-Progress\'';
					echo $sql_stk."<br>";
					$tok_stk = mysql_query($sql_stk);
					
					//Aur Finished Goods badhao
					$sql_stk = 'update item_master set item_stock = item_stock + ' . $_POST['item_qty'][$item_key] . ' where item_name = \'' . $_POST['item_name'][$item_key] . '\' and item_description = \'' . $_POST['item_decription'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\') and unit_id = \'' . $_POST['item_unit'][$item_key] . '\' and category_id = (select category_id from category where category_name = \'' . $_POST['category'][$item_key] . '\' and capacity_id = (select capacity_id from capacity_master where capacity_name = \'' . $_POST['capacity'][$item_key] . '\')) and item_type = \'Finished Goods\'';
					$tok_stk = mysql_query($sql_stk);
				}
				*/
			}
		}
		
		
		//Once the save is done, return to the listing page
		header("location: ./item_requisition_list.php?msg=" . $msg);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A.S.M.I.</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="../js/CalendarPopup.js"></script>
	<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>

	<script type="text/javascript" src="../js/AnchorPosition.js"></script>
	<script type="text/javascript" src="../js/date.js"></script>
	<script type="text/javascript" src="../js/PopupWindow.js"></script>
<script language="javascript" src="calender/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="calender/dhtmlgoodies_calendar.css" />
	
	
	<script language="JavaScript" type="text/javascript">
	
	<?php
		//Lets generate the Item -> price -> unit array
		?>
		var item_arr = new Array();
		<?php
		while($res_im = mysql_fetch_assoc($tok_im))
		{
			?>
			item_arr[<?php echo $res_im['item_id'] ?>] = new Array(8);
			item_arr[<?php echo $res_im['item_id'] ?>][0] = '<?php echo $res_im['item_purchase_rate'] ?>';	//Purchase rate
			item_arr[<?php echo $res_im['item_id'] ?>][1] = '<?php echo $res_im['unit_id'] ?>';			//Unit Id
			item_arr[<?php echo $res_im['item_id'] ?>][2] = '<?php echo $res_im['capacity_name'] ?>';	//Capacity
			item_arr[<?php echo $res_im['item_id'] ?>][3] = '<?php echo $res_im['item_code'] ?>';		//Item Code
			item_arr[<?php echo $res_im['item_id'] ?>][4] = '<?php echo $res_im['item_name'] ?>';		//Item Name
			item_arr[<?php echo $res_im['item_id'] ?>][5] = '<?php echo $res_im['item_id'] ?>';			//Item Id, itself
			item_arr[<?php echo $res_im['item_id'] ?>][6] = '<?php echo $res_im['category_name'] ?>';	//Category
			item_arr[<?php echo $res_im['item_id'] ?>][7] = '<?php echo $res_im['item_description'] ?>';	//Description
			<?php
		}
		//Reset the position for the item mysql resource
		mysql_data_seek($tok_im, 0);
		
	?>
	
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}
	
	function setItemName(item_code_obj, row_ctr)
	{
		if(item_code_obj.value != "")
		{
			document.getElementById("item_description_" + row_ctr).value = "";
			for(var i in item_arr)
			{
				if(item_code_obj.value == item_arr[i][3])
				{
					//Matched
					document.getElementById("item_name_" + row_ctr).value = item_arr[i][4];
					document.getElementById("item_id_" + row_ctr).value = item_arr[i][5];
					document.getElementById("category" + row_ctr).value = item_arr[i][6];
					document.getElementById("item_description_" + row_ctr).value = item_arr[i][7];
					setRateAndUnit(row_ctr)
					break;
				}
			}
		}
	}
	
	function setRateAndUnit(row_ctr)
	{
		//Rate feild id
		var unit_fld_id = "item_unit" + row_ctr;
		var capacity_fld_id = "capacity" + row_ctr;
		//This can only be called for a row, when the item id is already set
		var item_id = document.getElementById("item_id_" + row_ctr).value
		//alert("Here");
		if(item_id != "")
		{
			//alert("But not here!");
			//document.getElementById(rate_fld_id).value = item_arr[item_id][0];
			
			document.getElementById(unit_fld_id).value = item_arr[item_id][1];
			document.getElementById(capacity_fld_id).value = item_arr[item_id][2];
			
			if(document.getElementById("item_qty" + row_ctr).value == '')
			{
				document.getElementById("item_qty" + row_ctr).value = "1";
				
			}
			
		}
		else
		{
			document.getElementById(unit_fld_id).value = '';
			document.getElementById(capacity_fld_id).value = '';
			document.getElementById("item_qty" + row_ctr).value = '';
		}
		

	}
		
	function IsNill(frm)
	{
		   if(frm.item_requisition_date.value.length=="")
		   {
				   alert("Please enter requisition date");
				   frm.item_requisition_date.focus();
				   return false;        
		   }
		   
		   if(frm.sales_order_id.value.length=="")
		   {
				   alert("Please Sales Order no");
				   frm.sales_order_id.focus();
				   return false;        
		   }

			if(confirm("Save requisition form?"))
			{
		   		return true;
			}
			else
			{
				return false;
			}
		}
		
		
		function addRemoveRow(row_id, cmd)
		{
			if(cmd == 'add')
			{
				//Disable the button layer
				document.getElementById("button_span_" + row_id).style.display = "none";
				row_id++;
				//alert(document.getElementById("div_" + row_id));
				document.getElementById("div_" + row_id).style.display = "block";
			}
			else
			{
				//alert(document.getElementById("div_" + row_id));
				document.getElementById("div_" + row_id).style.display = "none";
				//Enable the previous button layer
				row_id--;
				document.getElementById("button_span_" + row_id).style.display = "";
			}
		}
		
		function set_party(po_no)
		{
			for(var i in po_party_rel_arr)
			{
				if(po_no == po_party_rel_arr[i][0])
				{
					//Set to the party combo, and breal
					document.getElementById("party_id").value = po_party_rel_arr[i][1];
					break;
				}
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
        <td width="200" align="center" valign="top" bgcolor="#e6e6e6"><br />
          <?php include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
				<?php
				if(isset($msg))
				{
					echo $msg . '<br /><br />';
				}
 
if($item_id !=""){
 $sql_edit=mysql_query("SELECT * FROM item_master WHERE item_id ='$item_id '") or die(mysql_error());
	$r_edit= mysql_fetch_array($sql_edit);
}	
?>
              <table width="90%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?php echo $mode ?>" />
				  <input name="item_id" type="hidden" value="<?php echo $_REQUEST["item_id"] ?>" />
				  

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Item Requisition </strong></td>
                      </tr>
                      <tr>
                        <td width="20%" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?php echo $_SESSION['err_msg'] ?>&nbsp;</td><?php echo $_SESSION['err_msg']=""; ?>						
                      </tr>
					<tr>
                      <td>Requisition Date (*):</td>
                      <td valign="middle"><input name="item_requisition_date" class="input" id="item_requisition_date" tabindex="2"  size="20" readonly />&nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					  <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('item_requisition_date'),'mm/dd/yyyy',this); return false;" align="absbottom" /></td>
                    </tr>
   					
					<tr>
                      <td>Sales Order Referance No. (*):</td>
                      <td height="20">
					  	<input type="text" name="sales_order_id" id="sales_order_id" />
					  </td>
                    </tr>
					<tr>
                      <td>Work Flow Direction:</td>
                      <td height="20">
					  	<select name="direction" id="direction">
							<option value="Raw Material -> WIP">Raw Material -> WIP</option>
							<option value="WIP -> Finished Goods">WIP -> Finished Goods</option>
						</select>
					  </td>
                    </tr>
 					<tr>
						<td colspan="2">
							<table width="100%" cellspacing="0" cellpadding="5">
								<tr>
									<td>
									Item Description
									</td>
								</tr>
								<tr>
									<td>
										<div style="width:100%; float:left; padding-bottom:10px;">
										<!--SL No.--> 
											&nbsp;
										Item Code
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Item Name
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Description	
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Capacity
											&nbsp;&nbsp;
										Grade
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Unit
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Quantity
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
										<?php
										$row_ctr = 1;
										while($row_ctr <= 60)
										{
											$is_hidden = ($row_ctr == 1) ? "": "display:none;";
											?>
											<div id="div_<?php echo $row_ctr; ?>" style="width:100%; float:left; padding-bottom:10px;<?php echo $is_hidden ?>">
												<?php echo $row_ctr; if(strlen($row_ctr) < 2) echo "&nbsp;&nbsp;"; else echo "&nbsp;"; ?>
												<!--<select name="item_id[]" onchange="setRateAndUnit(this.value, <?php echo $row_ctr ?>)">
													<option value="">---Select Item---</option>
													<?php
													/*while($res_im = mysql_fetch_assoc($tok_im))
													{
														?>
														<option value="<?php echo $res_im['item_id'] ?>"><?php echo $res_im['item_code'] ?>&nbsp;-&nbsp;<?php echo $res_im['item_name'] ?></option>
														<?php
													}
													mysql_data_seek($tok_im, 0);*/
													?>
												</select>-->
												<input type="text" name="item_code[]" size="4" onchange="javascript:setItemName(this, <?php echo $row_ctr ?>)" />
												<input type="hidden" name="item_id[]" id="item_id_<?php echo $row_ctr ?>" />
												&nbsp;
												<input type="text" readonly="readonly" name="item_name[]" size="23" id="item_name_<?php echo $row_ctr ?>" />
												&nbsp;
												<input type="text" name="item_decription[]" size="23" id="item_description_<?php echo $row_ctr ?>" />
												&nbsp;
												<input type="text" name="capacity[]" size="3" id="capacity<?php echo $row_ctr ?>" onkeypress="return isNumberKey(event)" />
												&nbsp;
 												<input type="text" name="category[]" size="3" id="category<?php echo $row_ctr ?>" onkeypress="return isNumberKey(event)" />
												&nbsp;
												<select name="item_unit[]" style="width:70px;" id="item_unit<?php echo $row_ctr ?>">
													<option value="">---Unit---</option>
													<?php
													while($res_um = mysql_fetch_assoc($tok_um))
													{
														?>
														<option value="<?php echo $res_um['unit_id'] ?>"><?php echo $res_um['unit_name'] ?></option>
														<?php
													}
													mysql_data_seek($tok_um, 0);
													?>
												</select>
												&nbsp;
 												<input type="text" name="item_qty[]" size="3" id="item_qty<?php echo $row_ctr ?>" onkeypress="return isNumberKey(event)" onblur="javascript:setRateAndUnit(<?php echo $row_ctr ?>)" />
												&nbsp;&nbsp;<span id="button_span_<?php echo $row_ctr ?>">
												<?php
												if($row_ctr > 1)
												{
												?>
												<input type="button" name="less[]" value="-" onclick="addRemoveRow(<?php echo $row_ctr ?>, 'del')" />
												<?php
												}
												?>
												
												<?php
												if($row_ctr < 60)
												{
												?>
												<input type="button" name="more[]" value="+" onclick="addRemoveRow(<?php echo $row_ctr ?>, 'add')" />
												<?php
												}
												?>
												</span>
											</div>
											<?php
											$row_ctr++;
										}
 										?>
										
										<!--<div style="width:100%; float:left;">Line two</div>-->
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
                   	<tr>
                      <td>&nbsp;</td>
                      <td><input type="submit" value="Save" /> &nbsp;<input type="button" value="Cancel" onclick="window.location.href='./item_requisition_list.php'"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
				  </form>
				  <DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
				  </td>
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
