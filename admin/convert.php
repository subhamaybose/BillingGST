<?

Class Convert {
	var $words = array();
	var $places = array();
	var $amount_in_words;
	var $decimal;
	var $decimal_len;
	
	function Convert($amount, $currency="Pesos") {
		$this->assign();
		
		$temp = (string)$amount;
		$pos = strpos($temp,".");
		if ($pos) {
			$temp = substr($temp,0,$pos);
			$this->decimal = strstr((string)$amount,".");
			$this->decimal_len = strlen($this->decimal) - 2;
			$this->decimal = substr($this->decimal,1,$this->decimal_len+1);
		}
		//print($temp);
		//echo "<br>";
		$len = strlen($temp)-1;
		$ctr = 0;
		$arr = array();
		//print($len);
		while ($len >= 0) {
			if ($len >= 2 ) {
				$arr[$ctr++] = substr($temp, $len-2, 3);
				$len -= 3;
			} else {
				$arr[$ctr++] = substr($temp,0,$len+1);
				$len = -1;
			}
		}
		// print_r($arr);
		// echo "<br>";
		$str = "";
		for ($i=count($arr)-1; $i>=0; $i--) {
			$figure = $arr[$i];
			$sub = array(); $temp="";
			for ($y=0; $y<strlen(trim($figure)); $y++) {
				$sub[$y] = substr($figure,$y,1);
			}
			//  print_r($sub);
			 // echo "<br>$i<br>";
			$len = count($sub);
			if ($len==3) {
				if ($sub[0]!="0" && $i==0 ) {
					$temp .= ((strlen($str)>0)?" ":"") . trim($this->words[$sub[0]]) . " Hundred ";
				}else if ($sub[0]!="0" && $i==1 ) {
					$temp .= ((strlen($str)>0)?" ":"") . trim($this->words[$sub[0]]) . " ".$this->places[2];
				}
				 
				$temp .= $this->processTen($sub[1], $sub[2]);
			} elseif ($len==2) {
				$temp .= $this->processTen($sub[0], $sub[1]);
			} else {
			
				$temp .= $this->words[$sub[0]];
			}
			//echo $i."--";
			if (strlen($temp)>0) {
				$str .= $temp . $this->places[$i];
			}
		}
		$str .= " " . $currency;
		if ($this->decimal_len>0) {
			$str .= " And " . $this->decimal . "/" . $this->denominator($this->decimal_len+1) .  " ";
		}
		$this->amount_in_words = $str;
	}
	
	function denominator($x) {
		$temp = "1";
		for ($i=1; $i<=$x; $i++) {
			$temp .= "0";
		}
		return $temp;
	}
	
	function display() {
		echo $this->amount_in_words;
	}

	function processTen($sub1, $sub2) {
		if ($sub1=="0") {
			if ($sub2=="0") {
				return "";
			} else {
				return $this->words[$sub2];
			}
		} elseif ($sub1!="1") {
			if ($sub2!="0") {
				return $this->words[$sub1."0"] . $this->words[$sub2];
			} else {
				return $this->words[$sub1 . $sub2];
			}
		} else {
			if ($sub2=="0") {
				return $this->words["10"];
			} else {
				return $this->words[$sub1 . $sub2];
			}
		}
	}

	function assign() {
		$this->words["1"] = " One"; 			$this->words["2"] = " Two";
		$this->words["3"] = " Three"; 			$this->words["4"] = " Four";
		$this->words["5"] = " Five"; 			$this->words["6"] = " Six";
		$this->words["7"] = " Seven";			$this->words["8"] = " Eight";
		$this->words["9"] = " Nine";
	
		$this->words["10"] = " Ten";			$this->words["11"] = " Eleven";
		$this->words["12"] = " Twelve";			$this->words["13"] = " Thirteen";
		$this->words["14"] = " Fourteen";		$this->words["15"] = " Fifteen";
		$this->words["16"] = " Sixteen";		$this->words["17"] = " Seventeen";
		$this->words["18"] = " Eighteen";		$this->words["19"] = " Nineteen";

		$this->words["20"] = " Twenty";			$this->words["30"] = " Thirty";
		$this->words["40"] = " Forty";			$this->words["50"] = " Fifty";
		$this->words["60"] = " Sixty";			$this->words["70"] = " Seventy";
		$this->words["80"] = " Eighty";			$this->words["90"] = " Ninety";
	
		$this->places[0] = "";					$this->places[1] = " Thousand";
		$this->places[2] = " Lakhs";			$this->places[3] = " Crore";
		$this->places[4] = "  ";
	}
}
?>