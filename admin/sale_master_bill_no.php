<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	
	//Get item list
	//$sql_im = "select im.item_sale_rate as item_sale_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name from item_master im JOIN capacity_master cm on im.capacity_id = cm.capacity_id";
	
	/*$sql_im = "select im.item_sale_rate as item_sale_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name, c.category_name as category_name from item_master im LEFT JOIN capacity_master cm on im.capacity_id = cm.capacity_id LEFT JOIN category c ON c.category_id = im.category_id";*/
	
	$sql_im = "select im.item_sale_rate as item_sale_rate, im.item_stock as item_stock, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name, c.category_name as category_name from item_master im LEFT JOIN capacity_master cm on im.capacity_id = cm.capacity_id LEFT JOIN category c ON c.category_id = im.category_id where im.item_stock > 0";
	$tok_im = mysql_query($sql_im);
	
	
	//The unit master
	$sql_um = "select * from unit_master";
	$tok_um = mysql_query($sql_um);
	
	//The tarnsporter
	$sql_tp = "select * from transporter_master";
	$tok_tp = mysql_query($sql_tp);
	
	//The customer
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master";
	$tok_cust = mysql_query($sql_cust);
	//Chk for save case
	if(isset($_POST['bill_no']))
	{
		//Check if this bill no already exist in the system, if so redirect to existing bill
		$sql_chk_old = "select * from sales_order where bill_no = '" . trim($_POST['bill_no']) . "'";
		//echo $sql_chk_old;
		$tok_chk_old = mysql_query($sql_chk_old);
		if(mysql_num_rows($tok_chk_old) == 0)
		{
			//echo "In here cos : " . mysql_num_rows($tok_chk_old);
			//The main SQL
			$sql_main = "insert into sales_order set bill_no = '" . trim($_POST['bill_no']) . "', bill_date = '" . date('Y-m-d', strtotime($_POST['bill_date'])) . "', sales_order_id = '" . trim($_POST['sales_order_id']) . "', sales_order_date = '" . date('Y-m-d', strtotime($_POST['sales_order_date'])) . "', challan_no = '" . trim($_POST['challan_no']) . "', challan_date = '" . date('Y-m-d', strtotime($_POST['challan_date'])) . "', sales_order_amount = '" . $_POST['sales_order_amount'] . "', party_id = '" . $_POST['party_id'] . "', r_r_cn_no = '" . $_POST['r_r_cn_no'] . "', r_r_cn_date = '" . date('Y-m-d', strtotime($_POST['r_r_cn_date'])) . "', transporter_id = '" . $_POST['transporter_id'] . "', job_heading = '" . $_POST['job_heading'] . "', vat = '" . $_POST['vat'] . "', freight = '" . $_POST['freight'] . "', discount = '" . $_POST['discount'] . "', cst = '" . $_POST['cst'] . "', tax_deposit = '" . $_POST['tax_deposit'] . "', freight_to_pay = '" . $_POST['freight_to_pay'] . "', packing_qty = '" . $_POST['packing_qty'] . "', net_amount = '" . $_POST['net_amount'] . "', remarks = '" . $_POST['remarks'] . "',fin_year='".$_POST['fin_year']."',bill_int_id='".$_POST['bill_int_id']."'";
			//echo $sql_main . '<br />';
			$tok_main = mysql_query($sql_main);
			
			$sale_id = trim($_POST['bill_no']);
			/*echo '<pre>';
			print_r($_POST);*/
			//Once the main SQL is saved, the details part
			foreach($_POST['item_id'] as $item_key => $item_val)
			{
				if(trim($item_val) != '')
				{
					//Set NULL for expected NULL's
					if(trim($_POST['capacity'][$item_key]) == '')
						$_POST['capacity'][$item_key] = NULL;
						
					if(trim($_POST['value'][$item_key]) == '')
						$_POST['value'][$item_key] = NULL;
						
					if(trim($_POST['remarks'][$item_key]) == '')
						$_POST['remarks'][$item_key] = NULL;
					
					$sql_sub = "insert into sales_order_details set sales_order_id = '" . $sale_id . "', item_id = '" . $item_val . "', item_qty = '" . $_POST['item_qty'][$item_key] . "', item_unit = '" . $_POST['item_unit'][$item_key] . "', item_rate = '" . $_POST['item_rate'][$item_key] . "', item_amount = '" . $_POST['item_amount'][$item_key] . "', capacity = '" . $_POST['capacity'][$item_key] . "', category = '" . $_POST['category'][$item_key] . "', item_decription = '" . $_POST['item_decription'][$item_key] . "'";
					//echo $sql_sub . '<br />';
					
					$tok_sub = mysql_query($sql_sub);
					//echo $sql_sub . '<br />';
					
					//Adjust the stock accordingly
					$sql_stk = 'update item_master set item_stock = item_stock - ' . $_POST['item_qty'][$item_key] . ' where item_id = \'' . $item_val . '\'';
					$tok_stk = mysql_query($sql_stk);
				}
			}
			//Once the save is done, return to the listing page
			header("location: ./sale_print.php?bill_no=" . $sale_id);
		}
		else
		{
			//Bill exists
			$msg = "<h3>Bill with bill no exists.</h3> Click <a href='sale_print.php?bill_no=" . trim($_POST['bill_no']) . "'>here</a> to view it.";
		}
	}
	
	function genBillNo()
	{
		/**************************************/
		//FORMAT
		//ASMI/(Dynamic number)/(financial-year)
		/**************************************/
		list($fm,$fy)=explode("-",date("m-Y"));
		if($fm<=3)
			$fin_year=$fy-1;
		else
			$fin_year=$fy;
			$formated_fin_year=$fy."-".($fy+1);
		//Calculate fin-year
		//$sql_id = "select MAX(SUBSTRING_INDEX(SUBSTRING_INDEX( `bill_no` , '/', 2 ),'/',-1)) AS the_num from sales_order where bill_no LIKE '%2010-2011'";
		$sql_id = "select MAX(bill_int_id) AS the_num from sales_order where fin_year ='$fy'";
		$tok_id = mysql_query($sql_id);
		if(mysql_num_rows($tok_id) == 0)
		{
			//First ID
			$the_id = 'ASMI/1/'.$formated_fin_year;
		}
		else
		{
			//Successive ones
			$data_id = mysql_fetch_assoc($tok_id);
			$next_id = $data_id['the_num'] + 1;
			
			//NExt final id
			$the_id = 'ASMI/' . $next_id .'/'. $formated_fin_year;
		}
		
		return $the_id;
	}
	
	function genBillNoId()
	{
		/**************************************/
		//FORMAT
		//ASMI/(Dynamic number)/(financial-year)
		/**************************************/
		list($fm,$fy)=explode("-",date("m-Y"));
		if($fm<=3)
			$fin_year=$fy-1;
		else
			$fin_year=$fy;
			$formated_fin_year=$fy."-".($fy+1);
		//Calculate fin-year
		//$sql_id = "select MAX(SUBSTRING_INDEX(SUBSTRING_INDEX( `bill_no` , '/', 2 ),'/',-1)) AS the_num from sales_order where bill_no LIKE '%2010-2011'";
		$sql_id = "select MAX(bill_int_id) AS the_num from sales_order where fin_year ='$fy'";
		$tok_id = mysql_query($sql_id);
		if(mysql_num_rows($tok_id) == 0)
		{
			//First ID
			$the_id = 1;
		}
		else
		{
			//Successive ones
			$data_id = mysql_fetch_assoc($tok_id);
			$next_id = $data_id['the_num'] + 1;
			
			//NExt final id
			$the_id =  $next_id ;
		}
		
		return $the_id;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A.S.M.I.</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="../js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../js/AnchorPosition.js"></script>
	<script type="text/javascript" src="../js/date.js"></script>
	<script type="text/javascript" src="../js/PopupWindow.js"></script>
<script language="javascript" src="calender/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="calender/dhtmlgoodies_calendar.css" />
	
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
			item_arr[<?php echo $res_im['item_id'] ?>][0] = <?php echo $res_im['item_sale_rate'] ?>;	//Sale rate
			item_arr[<?php echo $res_im['item_id'] ?>][1] = <?php echo $res_im['unit_id'] ?>;	//Unit Id
			item_arr[<?php echo $res_im['item_id'] ?>][2] = '<?php echo $res_im['capacity_name'] ?>';	//Capacity
			item_arr[<?php echo $res_im['item_id'] ?>][3] = '<?php echo $res_im['item_code'] ?>';	//Item Code
			item_arr[<?php echo $res_im['item_id'] ?>][4] = '<?php echo $res_im['item_name'] ?>';	//Item Name
			item_arr[<?php echo $res_im['item_id'] ?>][5] = '<?php echo $res_im['item_id'] ?>';	//Item Id, itself
			item_arr[<?php echo $res_im['item_id'] ?>][6] = '<?php echo $res_im['category_name'] ?>';	//Item Id, itself
			item_arr[<?php echo $res_im['item_id'] ?>][7] = '<?php echo $res_im['item_stock'] ?>'; //Item stock
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
			cust_arr[<?php echo $res_cust['party_id'] ?>] = new Array(3);
			cust_arr[<?php echo $res_cust['party_id'] ?>][0] = <?php echo ($res_cust['party_vat_pcent'] == "") ? 0 :  $res_cust['party_vat_pcent'] ?>;	//VAT Percentage
			cust_arr[<?php echo $res_cust['party_id'] ?>][1] = <?php echo ($res_cust['party_cst_pcent'] == "") ? 0 :  $res_cust['party_cst_pcent'] ?>;	//CST Percentage
			cust_arr[<?php echo $res_cust['party_id'] ?>][2] = <?php echo ($res_cust['party_tax_deposit'] == "") ? 0 :  $res_cust['party_tax_deposit'] ?>;	//Tax Deposit Percentage
			<?php
		}
		//Reset the position for the item mysql resource
		mysql_data_seek($tok_cust, 0);
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
					document.getElementById("item_stock_" + row_ctr).value = item_arr[i][7];
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
		calculateSaleTotals();
	}
	
	function stock_check(row_ctr)
	{
		if(Number(document.getElementById('item_qty' + row_ctr).value) > Number(document.getElementById('item_stock_' + row_ctr).value))
		{
			document.getElementById('item_qty' + row_ctr).focus;
			alert('Quatity cannot be greater than ' + document.getElementById('item_stock_' + row_ctr).value + '\n which is current stock for this item');
			document.getElementById('item_qty' + row_ctr).value = 1;
		}
		else
		{
			setRateAndUnit(row_ctr);
		}
	}
	
	function calculateSaleTotals()
	{
		var gross_amnt;
		var other_additions, net_amount;
		gross_amnt = 0.0;
		for(var row_ctr = 1; row_ctr <= 60; row_ctr++)
		{
			gross_amnt = gross_amnt + Number(document.getElementById("item_amount" + row_ctr).value);
		}
		document.getElementById("sales_order_amount").value = gross_amnt;
		
		//Customer specific tasks
		var cust_id, vat_amnt, cst_amnt, tax_deposit, freight;
		cust_id = document.getElementById("party_id").value;
		//Party VAT
		vat_amnt = gross_amnt * cust_arr[cust_id][0] / 100;
		cst_amnt = gross_amnt * cust_arr[cust_id][1] / 100;
		tax_deposit = gross_amnt * cust_arr[cust_id][2] / 100;
		freight = Number(document.getElementById("freight").value);
		
		//Setvalue to respective fileds
		document.getElementById("vat").value = vat_amnt;
		document.getElementById("cst").value = cst_amnt;
		document.getElementById("tax_deposit").value = tax_deposit;
		
		//Other additions
		//other_additions = Number(document.getElementById("vat").value) + Number(document.getElementById("freight").value) + Number(document.getElementById("cst").value) + Number(document.getElementById("freight_to_pay").value) + Number(document.getElementById("freight_to_pay").value);
		other_additions = vat_amnt + cst_amnt + tax_deposit + freight;
		
		net_amount = Number(gross_amnt) + Number(other_additions) - Number(document.getElementById("discount").value);
		document.getElementById("net_amount").value = net_amount;
	}
	
	function IsNill(frm)
	{
	
		   if(frm.bill_no.value.length=="")
		   {
				   alert("Please enter Bill No");
				   frm.bill_no.focus();
				   return false;        
		   }
		   
		   if(frm.bill_date.value.length=="")
		   {
				   alert("Please enter Bill date");
				   frm.bill_date.focus();
				   return false;        
		   }
		   
		   if(frm.sales_order_id.value.length=="")
		   {
				   alert("Please enter order no");
				   frm.sales_order_id.focus();
				   return false;        
		   }
		   
		   if(frm.sales_order_date.value.length=="")
		   {
				   alert("Please enter sales order date");
				   frm.sales_order_date.focus();
				   return false;        
		   }
	
		   if(frm.challan_no.value.length=="")
		   {
				   alert("Please enter challan no");
				   frm.challan_no.focus();
				   return false;        
		   }
		   
		   if(frm.challan_date.value.length=="")
		   {
				   alert("Please enter challan date");
				   frm.challan_date.focus();
				   return false;        
		   }
		   
		   /*if(frm.r_r_cn_no.value.length=="")
		   {
				   alert("Please enter R.R/CN no");
				   frm.r_r_cn_no.focus();
				   return false;        
		   }
		   
		   if(frm.r_r_cn_date.value.length=="")
		   {
				   alert("Please enter R.R/CN date");
				   frm.r_r_cn_date.focus();
				   return false;        
		   }*/
		   
		   if(frm.transporter_id.value=="")
		   {
				   alert("Please select transporter");
				   frm.transporter_id.focus();
				   return false;        
		   }
		   
		   if(frm.party_id.value=="")
		   {
				   alert("Please select Party");
				   frm.party_id.focus();
				   return false;        
		   }
		   
		   
			if(frm.net_amount.value == "0")
			{
				alert("Please enter net Amount");
				frm.net_amount.focus();
				return false;        
			}

			if(confirm("Save and Print Bill?"))
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
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Sale Bill </strong></td>
                      </tr>
                      <tr>
                        <td width="20%" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?php echo $_SESSION['err_msg'] ?>&nbsp;</td><?php echo $_SESSION['err_msg']=""; ?>						
                      </tr>
                    <tr>
                      <td>
                        <label>Bill No.  : </label></td>
                      <td><input name="bill_no" class="input" id="bill_no" value="<?php echo genBillNo(); ?>" tabindex="1" size="20" />
					  <?php
					  list($fm,$fy)=explode("-",date("m-Y"));
						if($fm<=3)
							$fin_year=$fy-1;
						else
							$fin_year=$fy;
					  ?>
					  <input name="fin_year" type="hidden" id="fin_year" value="<?php echo $fin_year; ?>"  />
					  <input name="bill_int_id" type="hidden" id="bill_int_id" value="<?php echo genBillNoId(); ?>"  />
					  </td>
                    </tr>
					<tr>
                      <td>Bill Date (*):</td>
                      <td valign="middle"><input name="bill_date" class="input" id="bill_date" tabindex="2" value="<?php echo $r_edit["bill_date"]?>" size="20" readonly />&nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					 <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('bill_date'),'mm/dd/yyyy',this); return false;" align="absbottom" /></td>
                    </tr>
					
					<tr>
                      <td>
                        <label>Order No.  (*): </label></td>
                      <td height="20"><input name="sales_order_id" class="input" id="sales_order_id" value="<?php echo $r_edit["sales_order_id"] ?>" tabindex="3" size="20" /></td>
                    </tr>
					<tr>
                      <td>Order Date (*):</td>
                      <td height="20"><input name="sales_order_date" class="input" id="sales_order_date" tabindex="4" value="<?php echo $r_edit["sales_order_date"]?>" size="20"  readonly />
					  &nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					 <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('sales_order_date'),'mm/dd/yyyy',this); return false;" align="absbottom" />
					  </td>
                    </tr>
					
					<tr>
                      <td>
                        <label>Challan No.  (*): </label></td>
                      <td height="20"><input name="challan_no" class="input" id="challan_no" value="<?php echo $r_edit["challan_no"] ?>" tabindex="5" size="20" /></td>
                    </tr>
					<tr>
                      <td>Challan Date (*):</td>
                      <td height="20"><input name="challan_date" class="input" id="challan_date" tabindex="6" value="<?php echo $r_edit["challan_date"]?>" size="20"  readonly />
					  &nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					  <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('challan_date'),'mm/dd/yyyy',this); return false;" align="absbottom" />
					  </td>
                    </tr>
					
					<tr>
                      <td>R.R/CN No. (*):</td>
                      <td height="20"><input name="r_r_cn_no" class="input" id="r_r_cn_no" tabindex="7" value="<?php echo $r_edit["r_r_cn_no"]?>" size="20" /></td>
                    </tr>
					
					<tr>
                      <td>R.R/CN Date. (*):</td>
                      <td height="20"><input name="r_r_cn_date" class="input" id="r_r_cn_date" tabindex="8" value="<?php echo $r_edit["r_r_cn_date"]?>" size="20" readonly />
					  &nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					  <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('r_r_cn_date'),'mm/dd/yyyy',this); return false;" align="absbottom" />
					  </td>
                    </tr>
					
					<tr>
                      <td>Transporter. (*):</td>
                      <td height="20">
					  	<select name="transporter_id" id="transporter_id">
							<option value="">---Select Transporter---</option>
							<?php
							while($res_tp = mysql_fetch_assoc($tok_tp))
							{
								?>
								<option value="<?php echo $res_tp['transporter_id'] ?>"><?php echo $res_tp['transporter_name'] ?></option>
								<?php
							}
							?>
						</select>
					  </td>
                    </tr>
					
					<tr>
                      <td>Customer Name. (*):</td>
                      <td height="20">
					  	<select name="party_id" id="party_id" onchange="calculateSaleTotals()">
							<option value="">---Select Customer---</option>
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
					
					<tr>
                      <td>Job Heading. :</td>
                      <td height="20">
					  	<input name="job_heading" class="input" id="job_heading" tabindex="9" value="<?php echo $r_edit["job_heading"]?>" size="20" />
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
 												<?php /*?><input type="text" name="item_qty[]" size="3" id="item_qty<?php echo $row_ctr ?>" onkeypress="return isNumberKey(event)" onblur="javascript:setRateAndUnit(<?php echo $row_ctr ?>)" /><?php */?>
												<input type="text" name="item_qty[]" size="3" id="item_qty<?php echo $row_ctr ?>" onkeypress="return isNumberKey(event)" onblur="javascript:stock_check(<?php echo $row_ctr ?>);" />
												<input type="hidden" name="item_stock[]" id="item_stock_<?php echo $row_ctr ?>" />
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
                      <td>Total Amount. :</td>
                      <td height="20">
					  	<input name="sales_order_amount" class="input" id="sales_order_amount" tabindex="10" value="<?php echo $r_edit["sales_order_amount"]?>" readonly onkeypress="return isNumberKey(event)" size="20" />
					  </td>
                    </tr>
					
					<tr>
                      <td>Vat. :</td>
                      <td height="20">
					  	<input name="vat" class="input" id="vat" tabindex="11" value="0" size="20" onkeypress="return isNumberKey(event)" onchange="calculateSaleTotals();" readonly="readonly"/>
					  </td>
                    </tr>
					
					<tr>
                      <td>CST. :</td>
                      <td height="20">
					  	<input name="cst" class="input" id="cst" tabindex="14" value="0" size="20" onkeypress="return isNumberKey(event)" onchange="calculateSaleTotals();" readonly="readonly"/>
					  </td>
                    </tr>
					
					<tr>
                      <td>Tax Deposited. :</td>
                      <td height="20">
					  	<input name="tax_deposit" class="input" id="tax_deposit" tabindex="14" value="0" size="20" onkeypress="return isNumberKey(event)" onchange="calculateSaleTotals();" readonly="readonly"/>
					  </td>
                    </tr>
					
 					<tr>
                      <td>Freight. :</td>
                      <td height="20">
					  	<input name="freight" class="input" id="freight" tabindex="12" value="0" size="20" onkeypress="return isNumberKey(event)" onchange="calculateSaleTotals();" />
					  </td>
                    </tr>
					
					<tr>
                      <td>Discount. :</td>
                      <td height="20">
					  	<input name="discount" class="input" id="discount" tabindex="13" value="0" onkeypress="return isNumberKey(event)" size="20" onchange="calculateSaleTotals();" />
					  </td>
                    </tr>
					
 					<tr>
                      <td>Freight to Pay. :</td>
                      <td height="20">
					  	<input name="freight_to_pay" class="input" id="freight_to_pay" tabindex="15" value="0" onkeypress="return isNumberKey(event)" size="20" onchange="calculateSaleTotals();" />
					  </td>
                    </tr>
					<tr>
                      <td>Net Amount. :</td>
                      <td height="20">
					  	<input name="net_amount" class="input" id="net_amount" tabindex="15" value="<?php echo $r_edit["net_amount"]?>" onkeypress="return isNumberKey(event)" readonly size="20"/>
					  </td>
                    </tr>
					
					<tr>
                      <td>Packing Details. :</td>
                      <td height="20">
					  	<input name="packing_qty" class="input" id="packing_qty" tabindex="16" value="<?php echo $r_edit["packing_qty"]?>" size="20" />
					  </td>
                    </tr>
					
 					<tr>
                      <td>Remarks. :</td>
                      <td height="20">
					  	<textarea name="remarks" tabindex="17" cols="20" rows="3" style="width:250px;"><?=$r_edit["remarks"]?></textarea>
					  </td>
                    </tr>
					
                   	<tr>
                      <td>&nbsp;</td>
                      <td><input type="submit" value="Save" /> &nbsp;<input type="button" value="Cancel" onclick="window.location.href='./sales_list.php'"></td>
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
