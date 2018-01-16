<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");
	
	//Get item list
	//$sql_im = "select im.item_sale_rate as item_sale_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name from item_master im JOIN capacity_master cm on im.capacity_id = cm.capacity_id";
	//$sql_im = "select im.item_purchase_rate as item_purchase_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name, c.category_name as category_name from item_master im LEFT JOIN capacity_master cm on im.capacity_id = cm.capacity_id LEFT JOIN category c ON c.category_id = im.category_id";
	
	$sql_im = "select im.item_purchase_rate as item_purchase_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name, c.category_name as category_name from item_master im LEFT JOIN capacity_master cm on im.capacity_id = cm.capacity_id LEFT JOIN category c ON c.category_id = im.category_id where im.item_type != 'Work-In-Progress'";

	$tok_im = mysql_query($sql_im);
	
	
	//The unit master
	$sql_um = "select * from unit_master";
	$tok_um = mysql_query($sql_um);
	
	//All sales orders
	//$sql_po = 'select purchase_order_id, purchase_bill_no, party_id from purchase_order order by purchase_order_date DESC';
	$sql_po = 'select bill_no, party_id,sales_order_date,bill_date  from sales_order order by sales_order_date desc ,bill_date  DESC';
	$tok_po = mysql_query($sql_po);
	
	
	
	
	//The customer
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master";
	$tok_cust = mysql_query($sql_cust);
	//Chk for save case
	
	if(isset($_POST['sales_return_no']))
	{
		//echo "In here cos : " . mysql_num_rows($tok_chk_old);
		//The main SQL
		
		$sql_main = "insert into sales_return set sales_return_no = '" . $_POST['sales_return_no'] . "', sales_return_date = '" . date('Y-m-d', strtotime($_POST['sales_return_date'])) . "', sales_return_amount = '" . $_POST['sales_return_amount'] . "', party_id = '" . $_POST['party_id'] . "', invoice_no = '" . trim($_POST['invoice_no']) . "', sales_order_id = '" . trim($_POST['sales_order_id']) . "'";
					
		$tok_main = mysql_query($sql_main);
		
		$sales_return_id = mysql_insert_id();

		//Once the main SQL is saved, the details part
		foreach($_POST['item_id'] as $item_key => $item_val)
		{
			if(trim($item_val) != '')
			{
				$sql_sub = "insert into sales_return_details set sales_return_id = '" . $sales_return_id . "', item_id = '" . $item_val . "', item_qty = '" . $_POST['item_qty'][$item_key] . "', item_unit = '" . $_POST['item_unit'][$item_key] . "', item_rate = '" . $_POST['item_rate'][$item_key] . "', item_amount = '" . $_POST['item_amount'][$item_key] . "'";
				
				
				$tok_sub = mysql_query($sql_sub);

				$sql_stk = 'update item_master set item_stock = item_stock + ' . $_POST['item_qty'][$item_key] . $updt_stmt . ' where item_id = \'' . $item_val . '\'';
		
				$tok_stk = mysql_query($sql_stk);

			}
		}
		$dr_cr='C';
				$nara="Being Credit Note No :".$_POST['sales_return_no'];
				post_ledger($_POST['sales_return_no'],$_POST['sales_return_amount'],$dr_cr, $_POST['party_id'],date('Y-m-d', strtotime($_POST['sales_return_date'])),$nara);
		//Once the save is done, return to the listing page
		header("location: ./sales_return_list.php?msg=" . $msg);
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
			item_arr[<?php echo $res_im['item_id'] ?>] = new Array(7);
			item_arr[<?php echo $res_im['item_id'] ?>][0] = <?php echo $res_im['item_purchase_rate'] ?>;	//Purchase rate
			item_arr[<?php echo $res_im['item_id'] ?>][1] = <?php echo $res_im['unit_id'] ?>;			//Unit Id
			item_arr[<?php echo $res_im['item_id'] ?>][2] = '<?php echo $res_im['capacity_name'] ?>';	//Capacity
			item_arr[<?php echo $res_im['item_id'] ?>][3] = '<?php echo $res_im['item_code'] ?>';		//Item Code
			item_arr[<?php echo $res_im['item_id'] ?>][4] = '<?php echo $res_im['item_name'] ?>';		//Item Name
			item_arr[<?php echo $res_im['item_id'] ?>][5] = '<?php echo $res_im['item_id'] ?>';			//Item Id, itself
			item_arr[<?php echo $res_im['item_id'] ?>][6] = '<?php echo $res_im['category_name'] ?>';	//Item Id, itself
			<?php
		}
		//Reset the position for the item mysql resource
		mysql_data_seek($tok_im, 0);
		
		//Nxt the cust/party array
		?>
		var cust_arr = new Array();
		<?php
		while($res_cust = mysql_fetch_assoc($tok_cust))
		{
			?>
			cust_arr[<?php echo $res_cust['party_id'] ?>] = new Array(2);
			cust_arr[<?php echo $res_cust['party_id'] ?>][0] = <?php echo ($res_cust['party_vat_pcent'] == "") ? 0 :  $res_cust['party_vat_pcent'] ?>;	//VAT Percentage
			cust_arr[<?php echo $res_cust['party_id'] ?>][1] = <?php echo ($res_cust['party_cst_pcent'] == "") ? 0 :  $res_cust['party_cst_pcent'] ?>;	//CST Percentage
			<!--cust_arr[<?php //echo $res_cust['party_id'] ?>][2] = <?php //echo ($res_cust['party_tax_deposit'] == "") ? 0 :  $res_cust['party_tax_deposit'] ?>;-->	//Tax Deposit Percentage
			<?php
		}
		//Reset the position for the item mysql resource
		mysql_data_seek($tok_cust, 0);
		
		//Purchase order no to party id relation
		?>
		var po_party_rel_arr = new Array();
		<?php
		$arr_ctr = 0;
		while($data_po = mysql_fetch_assoc($tok_po))
		{
			?>
			po_party_rel_arr[<?php echo $arr_ctr ?>] = new Array(2);
			po_party_rel_arr[<?php echo $arr_ctr ?>][0] = "<?php echo $data_po['bill_no']; //The sale bill no ?>";
			po_party_rel_arr[<?php echo $arr_ctr ?>][1] = "<?php echo $data_po['party_id']; //The Party Id ?>";
			<?php
			$arr_ctr++;
		}
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
					document.getElementById("item_rate" + row_ctr).value = item_arr[i][0];
					document.getElementById("category" + row_ctr).value = item_arr[i][6];
					setRateAndUnit(row_ctr)
					break;
				}
			}
		}
	}
	
	function setRateAndUnit(row_ctr)
	{
		//Rate feild id
		var rate_fld_id = "item_rate" + row_ctr;
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
			//and thus set the total amount
			//document.getElementById("item_amount" + row_ctr).value = document.getElementById("item_qty" + row_ctr).value * item_arr[item_id][0];
			document.getElementById("item_amount" + row_ctr).value = document.getElementById("item_qty" + row_ctr).value * document.getElementById(rate_fld_id).value;
			//and the value field
			//document.getElementById("value" + row_ctr).value = document.getElementById("item_amount" + row_ctr).value;
		}
		else
		{
			document.getElementById(rate_fld_id).value = '';
			document.getElementById(unit_fld_id).value = '';
			document.getElementById(capacity_fld_id).value = '';
			document.getElementById("item_qty" + row_ctr).value = '';
			document.getElementById("item_amount" + row_ctr).value = '';
		}
		
		//Call the totals calculator
		calculatePurchaseTotals();
	}
	
	function calculatePurchaseTotals()
	{
		var gross_amnt;
		var other_additions, net_amount;
		gross_amnt = 0.0;
		for(var row_ctr = 1; row_ctr <= 60; row_ctr++)
		{
			gross_amnt = gross_amnt + Number(document.getElementById("item_amount" + row_ctr).value);
		}
		document.getElementById("sales_return_amount").value = gross_amnt;
	}
	
	function IsNill(frm)
	{
		   if(frm.sales_return_no.value.length=="")
		   {
				   alert("Please enter Sales return no");
				   frm.sales_return_no.focus();
				   return false;        
		   }
		   
		   if(frm.sales_order_id.value.length=="")
		   {
				   alert("Please enter Sales order id");
				   frm.sales_order_id.focus();
				   return false;        
		   }
		   
		   if(frm.sales_return_date.value.length=="")
		   {
				   alert("Please enter Sales return date");
				   frm.sales_return_date.focus();
				   return false;        
		   }
		   
		   if(frm.invoice_no.value.length=="")
		   {
				   alert("Please enter Invoice no");
				   frm.invoice_no.focus();
				   return false;        
		   }
		   
		   if(frm.party_id.value=="")
		   {
				   alert("Please select Party");
				   frm.party_id.focus();
				   return false;        
		   }
		   
		   
			if(confirm("Save Sales return?"))
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
					//Set to the party combo, and break
					document.getElementById("party_id").value = po_party_rel_arr[i][1];
					break;
				}
			}
		}
		function openpop(ff)
{
  
var w=window.open(ff,'mywindow',"width=1200, height=1000, top=10, left=10");
}
		function open_sale()
		{
			if(document.getElementById("sales_order_id").selectedIndex!=0)
			{
				selval=document.getElementById("sales_order_id").options[document.getElementById("sales_order_id").selectedIndex].value;
				openpop("./sale_print.php?bill_no="+selval);
				//sales_order_id
			}else{
				alert("Please select a sale bill to view");
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
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Sales Return Bill </strong></td>
                      </tr>
                      <tr>
                        <td width="20%" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?php echo $_SESSION['err_msg'] ?>&nbsp;</td><?php echo $_SESSION['err_msg']=""; ?>						
                      </tr>
                    <tr>
                      <td>
                        <label>Sales Return No .  (*): </label></td>
                      <td height="20"><input name="sales_return_no" class="input" id="sales_return_no" value="" tabindex="3" size="20" /></td>
                    </tr>
					<tr>
                      <td>
                        <label>Invoice No .  (*): </label></td>
						  <td height="20">
						  	<select name="sales_order_id" id="sales_order_id" onchange="set_party(this.value)">
								<option value="">---Select ---</option>
								<?php
								mysql_data_seek($tok_po, 0);
								while($data_po = mysql_fetch_assoc($tok_po))
								{
									?>
									<option value="<?php echo $data_po['bill_no'] ?>"><?php echo $data_po['bill_no'] ?></option>
									<?php
								}
								?>
							</select>&nbsp;<input type="button" value="view" onclick="open_sale();" />
						  </td>
                    </tr>
					<tr>
                      <td>Sales Return Date  (*):</td>
                      <td valign="middle"><input name="sales_return_date" class="input" id="sales_return_date" tabindex="2"  size="20" readonly />
                      &nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					  <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('sales_return_date'),'mm/dd/yyyy',this); return false;" align="absbottom" /></td>
                    </tr>
   					<tr>
                      <td>Party Name. (*):</td>
                      <td height="20">
					  	<select name="party_id" id="party_id" onchange="calculatePurchaseTotals()">
							<option value="">---Select Party---</option>
							<?php
							while($res_cust = mysql_fetch_assoc($tok_cust))
							{
								?>
								<option value="<?php echo $res_cust['party_id'] ?>"><?php echo $res_cust['party_name'] ?></option>
								<?php
							}
							?>
						</select>
					  </td>
                    </tr>
					<!--<tr>
                      <td>Invoice No. (*):</td>
                      <td height="20">
					  	<input type="text" name="invoice_no" id="invoice_no" />
					  </td>
                    </tr>-->
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
											&nbsp;&nbsp;&nbsp;&nbsp;
										Rate
											&nbsp;&nbsp;&nbsp;
										Amount

										</div>
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
												&nbsp;
												<input type="text" name="item_rate[]" id="item_rate<?php echo $row_ctr ?>" size="3" onkeypress="return isNumberKey(event)" onblur="javascript:setRateAndUnit(<?php echo $row_ctr ?>)" />
												&nbsp;
												<input type="text" name="item_amount[]" id="item_amount<?php echo $row_ctr ?>" size="3" onkeypress="return isNumberKey(event)" />
												
												
 												<span id="button_span_<?php echo $row_ctr ?>">
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
                      <td>Sales Return  Amount. :</td>
                      <td height="20">
					  	<input name="sales_return_amount" class="input" id="sales_return_amount" tabindex="10" value="0" readonly onkeypress="return isNumberKey(event)" size="20" />
					  </td>
                    </tr>
                   	<tr>
                      <td>&nbsp;</td>
                      <td><input type="submit" value="Save" /> &nbsp;<input type="button" value="Cancel" onclick="window.location.href='./sales_return_list.php'"></td>
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
