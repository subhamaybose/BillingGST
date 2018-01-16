<?php

	include("../includes/config.php");include("sessiontime.php");
	include('convert.php');
	
	//$conversion_obj = new Convert();
	//get bill id from 
	$the_bill_no = $_REQUEST['bill_no'];
	$sql_sale = "select * from sales_order where bill_no = '" . $the_bill_no . "'";
	$tok_sale = mysql_query($sql_sale);
	$data_sale = mysql_fetch_assoc($tok_sale);
	
	$sql_cust = "select * from party_master where party_id = '" . $data_sale['party_id'] . "'";
	$tok_cust = mysql_query($sql_cust);
	$data_cust = mysql_fetch_assoc($tok_cust);
	//print_r ($data_cust);
 
function bill_header($data_sale,$pg)
{
$strout='<table height="920" width="690" border="1" cellpadding="0" cellspacing="0">
<tr><td height="250">
<table border="1px solid black" width="100%" height="600">

		 	
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="35%">Page :'.$pg.'&nbsp; &nbsp;</td>
						<td align="center">
							<span style="font-size:20px; font-weight:bold;">TAX INVOICE</span>
						</td>
						<td width="35%" align="right">Original/Duplicate/Triplicate</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td align="center">
							<span style="font-size:27px; font-weight:bold;">Associated Scientific Mfg. Industries</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="1px solid black" width="100%">
					<tr>
						<td width="27%">
							<table width="100%">
								<tr>
									<td>VAT NO.&nbsp;&nbsp; : 19301344017</td>
								</tr>
								<tr>
									<td>C.S.T.NO. : 19301344211</td>
								</tr>
								<tr>
									<td>PAN No.&nbsp;&nbsp;&nbsp; : AFMPB6687D</td>
								</tr>
								<tr>
									<td>E.M.NO.&nbsp;&nbsp;&nbsp; : 190171105238</td>
								</tr>
							</table>
						</td>
						<td width="73%">
							<table width="100%">
								<tr>
									<td>
									<!--	<h1 style="color:#336699">Associated Scientific Mfg. Industries</h1>	-->
										<span>Spl. In: Sintered Glass Ware &amp; Micro Filtration Assembly</span><br />
										10, Bhairab Mukherjee Lane, Kolkata - 700 004 (Opp. R.G. Kar Hospital Emergency Building)<br />
										Phone : 033-2554-8602, 2543-1010 &amp; Mobile: +91 9830606770<br />
										E-mail: info@sinteredglassware.com / associatedscientificmfgind@yahoo.co.in<br />
										Web Site : www.sinteredglassware.com
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table border="1px solid black" width="100%">
					<tr height="20px;">
						<td width="50%">TAX INVOICE No. '.$data_sale['bill_no'].'</td>
						<td width="15%">DATE</td>
						<td width="15%">'.date('d-m-Y', strtotime($data_sale['bill_date'])).'</td>
 						
						<td width="10%" align="right">Pkg(s)</td>
						<td valign="top"><input name="packing_qty" class="input" id="packing_qty" tabindex="7" value="'.$data_sale["packing_qty"].'" size="20"/></td>
						
						
						
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="49%" valign="top" height="100%">
							<table width="100%" height="100%">';
								 
									$sql_cust = "select * from party_master where party_id = '" . $data_sale['party_id'] . "'";
									$tok_cust = mysql_query($sql_cust);
									$data_cust = mysql_fetch_assoc($tok_cust);
								 
								$strout.= '<tr>
									<td height="100%">
										To<br />'.
										$data_cust['party_name'].'
										<br />
										'. $data_cust['party_address'].'
										<br />
										';
											if(trim($data_cust['party_phone']) != '')
											{
												$strout.= 'Phone No.: ' . $data_cust['party_phone'] . '<br />';
											}
											
											if(trim($data_cust['party_mobile']) != '')
											{
												$strout.= 'Mobile No.: ' . $data_cust['party_mobile'];
											}
										 
											if(trim($data_cust['party_vat_no']) != '')
											{
												$strout.= '<br />';
												$strout.= 'Vat No.&nbsp;&nbsp;&nbsp;: ' . $data_cust['party_vat_no'];
											}
										 
											if(trim($data_cust['party_cst_no']) != '')
											{
												$strout.= '<br />';
												$strout.= 'CST No.&nbsp;&nbsp;&nbsp;: ' . $data_cust['party_cst_no'];
											}
										 
									$strout.= '</td>
								</tr>
							</table>
						</td>
						<td style="padding-left:5px;">
							<table width="100%">
								<tr>
									<td>
										<table border="1px solid black" width="100%" height="100%">
											<tr>
												<td width="25%">ORDER NO.</td>
												<td width="42%">'.$data_sale['sales_order_id'].'</td>
												<td width="13%">DATE</td>
												<td width="20%">'.date('d-m-Y', strtotime($data_sale['sales_order_date'])).'</td>
											</tr>
											<tr>
												<td width="25%">CHALLAN NO.</td>
												<td>'.$data_sale['challan_no'].'</td>
												<td width="13%">DATE</td>
												<td>'.date('d-m-Y', strtotime($data_sale['challan_date'])).'</td>
											</tr>
											<tr>
												<td width="25%">R.R./CN NO.</td>
												<td valign="top"><input name="r_r_cn_no" class="input" id="r_r_cn_no" tabindex="7" value="'.$data_sale["r_r_cn_no"].'" size="20"/></td>
												<td width="13%">DATE</td>
												<td valign="top">'.date('d-m-Y', strtotime($data_sale['r_r_cn_date'])).'</td>
											</tr>
											<tr>
												<td width="25%">TRANSPORTER</td>
												<td valign="top" colspan="3">';
												$sql_tra = "select transporter_name from transporter_master where transporter_id = '" . $data_sale['transporter_id'] . "'";
														$tok_tra = mysql_query($sql_tra);
														$data_tra = mysql_fetch_assoc($tok_tra);
														$strout.= $data_tra['transporter_name'];
													$strout.= '</td>
											</tr>
										</table>
									</td>
											</tr>
										</table>
										</td>
											</tr>
										</table>
										</td>
											</tr>
											 
			';
					
					echo $strout;

}

function sub_head()
{

		$strout='
					<tr>
			<td>
				<table border="1px solid black" width="100%"><tr>
						<td width="5%">SL. NO.</td>
						<td width="65%">DESCRIPTION OF MATERIALS</td>
						<td width="5%" align="center">GRADE</td>
						<td width="5%" align="center">CAPACITY</td>
						<td width="10%" align="center">QUANTITY</td>
						<td width="5%" align="center">RATE PER UNIT</td>
						<td width="5%" align="center">AMOUNT</td>
					</tr>';
					echo $strout;
}
function bill_footer($prn)
{
$strout= '
				<table width="100%" border="1px solid black;">
					<tr>
						<td width="50%" valign="bottom">
							<ol>
								<li>No Claims for shortage, damage, breakage etc will be entertained after the goods leaves our premises, if not insured.</li>
								<li>Goods once sold can not be taken back.</li>
								<li>All disputes subject to Kolkata jurisdiction only.</li>
								<li>Interest @18% will be charged if payment is not made within 30 days.</li>
							</ol>
						</td>
						<td align="center">
							E.& O.E.<br />
							<span><i>For</i> <strong>Associated Scientific Mfg. Industries</strong>
							<br />
							<br /><br /><br /><br />
							<strong>AUTHORISED SIGNATORY</strong></span>';
							if($prn==1){
							$strout.= '
							<div style="border:0px!important;position:relative">
		 <input type="button" value="Print" id="Print" onClick="javascript:saveExtra();this.style.display=\'none\';document.getElementById(\'bck\').style.display=\'none\';window.print();">&nbsp;<input id="bck" type="button" value="Back to Sales" onClick="javascript:window.location.href=\'./sales_list.php\'"> 
		 </div>';
		 }
						$strout.= '</td>
					</tr>
				</table>
			</td>
		</tr></table>';
		echo $strout;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sale Bill</title>
<script type="text/javascript">
function roundNumber(rnum, rlength) { // Arguments: number to round, number of decimal places
  var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
  return newnumber; // Output the result to the form field (change for your purposes)
}
 function saveExtra()
{
var bill_no='<?=$_REQUEST['bill_no']?>';
var vfreight=document.getElementById('freight').value;
var vr_r_cn_no=document.getElementById('r_r_cn_no').value;
var vpacking_qty=document.getElementById('packing_qty').value;
 
document.getElementById("net_amount").value=parseFloat(document.getElementById("net_amount").value)-parseFloat(document.getElementById("old_freight").value)+parseFloat(document.getElementById("freight").value);
document.getElementById("net_amount").value=roundNumber(document.getElementById("net_amount").value,2);
//document.getElementById("net_amount").value=document.getElementById("net_amount").value.toFixed(2);

var parameterString ='r_r_cn_no='+vr_r_cn_no+'&packing_qty='+vpacking_qty+'&freight='+vfreight+'&bill_no='+bill_no+'&net_amount='+document.getElementById("net_amount").value; 
		//	+	'&comments='+document.getElementById("comments").value;
var loc1='./saveextra.php';
 var newAJAX = new Ajax.Request(loc1, { 
				method: 'get', 
				parameters: parameterString,
				onSuccess: function(transport) {
				//var notice = $('maturity_amt');
				 alert("Record Saved");
				  if(document.getElementById("amtinwords"))
 					document.getElementById("amtinwords").innerHTML = transport.responseText;
					if(document.getElementById("freight").value=="0.00" || document.getElementById("freight").value=="")
					{
						document.getElementById("freight").style.visibility='hidden';
						document.getElementById("fr_label").innerHTML='';
						//document.getElementById("fr_label").style.border='1px solid black';
					}
					
					 
				 }
	  		}
	   );
  }
  
</script>

<script type="text/javascript">
function roundNumber(number,decimals) {
	var newString;// The new rounded number
	decimals = Number(decimals);
	if (decimals < 1) {
		newString = (Math.round(number)).toString();
	} else {
		var numString = number.toString();
		if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
			numString += ".";// give it one at the end
		}
		var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
		var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
		var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
		if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
			if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
				while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
					if (d1 != ".") {
						cutoff -= 1;
						d1 = Number(numString.substring(cutoff,cutoff+1));
					} else {
						cutoff -= 1;
					}
				}
			}
			d1 += 1;
		} 
		if (d1 == 10) {
			numString = numString.substring(0, numString.lastIndexOf("."));
			var roundedNum = Number(numString) + 1;
			newString = roundedNum.toString() + '.';
		} else {
			newString = numString.substring(0,cutoff) + d1.toString();
		}
	}
	if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
		newString += ".";
	}
	var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
	for(var i=0;i<decimals-decs;i++) newString += "0";
	//var newNumber = Number(newString);// make it a number if you like
	return newString; // Output the result to the form field (change for your purposes)
}

</script>
</head>

<body style="font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif;">
<table height="920" width="690" border="1" cellpadding="0" cellspacing="0">
<tr><td height="250">
	<?php bill_header($data_sale,1); ?>
</td>
</tr>
<tr><td><table border="1px solid black;" width="100%"> <?php sub_head(); ?></table></td></tr>
<tr><td height="100">
</td></tr>
<tr><td height="150">

					<table border="1px solid black;" width="100%">
					<!-- Dynamic Row END -->
					<tr height="10px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5" width="85%">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						//echo round($data_sale['sales_order_amount'], 2); 
						echo number_format(round($data_sale['sales_order_amount'],0),2,'.','');
						?></td>
					</tr>
					<?php
					 if($data_sale['trade_discount_rate'] != "" and $data_sale['trade_discount_rate'] != 0)
 					 {
 					?>
					<tr>
						<td colspan="6" align="right">
						Trade Discount <?php echo $data_sale['trade_discount_rate']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['cst'], 2); 
							echo number_format(round($data_sale['less_trade_discount'],0),2,'.','');
							//$add_on_amnt = $add_on_amnt + $data_sale['less_trade_discount'];
							?>
						</td>
					</tr>
					
					<?php
				 	}
					if($data_cust['party_cst_pcent'] != "" and $data_cust['party_cst_pcent'] != 0)
 					{
 					?>
					<tr>
						<td colspan="6" align="right">
						ADD CST @<?php echo $data_cust['party_cst_pcent']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['cst'], 2); 
							echo number_format(round($data_sale['cst'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['cst'];
							?>
						</td>
					</tr>
					<tr height="10px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
					    //echo round($add_on_amnt);
						//echo str_pad($add_on_amnt,2,"00",STR_PAD_RIGHT);
					    echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					if($data_cust['party_vat_pcent'] != "" and $data_cust['party_vat_pcent'] != 0)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						ADD VAT @<?php echo $data_cust['party_vat_pcent']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['vat'], 2); 
							echo number_format(round($data_sale['vat'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['vat'];
							?>
						</td>
					</tr>
					<tr height="10px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					if($data_cust['party_tax_deposit'] != "" and $data_cust['party_tax_deposit'] != 0)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						ADD TAX DEPOSITED @<?php echo $data_cust['party_tax_deposit']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['tax_deposit'], 2); 
							echo number_format(round($data_sale['tax_deposit'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['tax_deposit'];
							?>
						</td>
					</tr>
					<tr height="10px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						//$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					if($data_sale['discount'] != "" and $data_sale['discount'] != 0)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						LESS DISCOUNT
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['discount'], 2); 
							echo number_format(round($data_sale['discount'],0),2,'.','');
							$add_on_amnt = $add_on_amnt - $data_sale['discount'];
							?>
						</td>
					</tr>
					<tr height="10px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						//$data_sale = $data_sale['sales_order_amount'] - $add_on_amnt;
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					 
					?>
					<tr>
						<td colspan="6" align="right">
						<span id="fr_label">ADD FREIGHT</span>
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['freight'], 2); 
							//echo number_format(round($data_sale['freight'],0),2,'.',',');
							 $add_on_amnt = $add_on_amnt + $data_sale['freight'];
							?>
							
							<input name="freight" class="input" id="freight" tabindex="12"  size="10" value="<?php echo  $data_sale['freight'];?>"     style="border:none;text-align:right" />
							<input name="old_freight" type="hidden" id="old_freight" value="<?php echo  $data_sale['freight'];?>"    />
						</td>
					</tr>
					<?php
					 
					?>
					<!-- Dynamic Row END -->
					
					<tr height="10px;">
						<td colspan="5">Rupees <span id="amtinwords"> <?php $final_amount=round($add_on_amnt , 0);
					  $conversion_obj = new Convert($final_amount, $currency="Only"); $conversion_obj->display(); ?></span>.</td>
						<td align="right">Net Total</td>
						<td align="right"><input name="net_amount" class="input"  id="net_amount" tabindex="112"  size="10" value="<?php echo number_format(round($data_sale['net_amount'],0),2,'.','');?>"  onChange="calculateSaleTotals();" style="border:none;text-align:right" readonly="readonly" /> </td>
					</tr>
					
					<?php
					
					if($data_sale['remarks'] != NULL and $data_sale['remarks'] != "")
					{
						?>
						<tr height="10px;">
							<td colspan="7">REMARKS: <?php echo $data_sale['remarks'] ?></td>	
						</tr>
						<?php
					}
					?>
					
				 
</table>
</td>
</tr>
<tr><td height="60"><?php bill_footer(1); ?>
</td>
</tr>

</table>
</body>
</html>
