<?php
/**
 * numword.php
 *
 * Converts a number to its word form
 * 
 * Copyright 2006-2009, Miles Johnson - www.milesj.me
 * Licensed under The MIT License - Modification, Redistribution allowed but must retain the above copyright notice
 * @link 		http://www.opensource.org/licenses/mit-license.php
 *
 * @package     Numword
 * @created   	December 15th 2008
 * @version     1.3
 * @link		www.milesj.me/resources/script/numword/
 * @changelog	www.milesj.me/files/logs/numword/
 *
 * - 4/5/09		v1.3	Added support up to centillion
 * - 12/16/08	v1.2	Added a block() function for converting all numbers within a block of text.
 * - 12/15/08	v1.1	Added a currency() function. Works for USD only.
 */

class Numword {

	/**
	 * Current version: www.milesj.me/files/logs/numword/
	 * @property int
	 */
	public $version = '1.2';

	/**
	 * Holds the basic numbers: 1-10
	 * @param array
	 */
	public static $digits = array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
	
	/**
	 * Holds all the teens: 11-19
	 * @param array
	 */
	public static $teens = array(
		11 => 'eleven',
		12 => 'twelve',
		13 => 'thirteen',
		14 => 'fourteen',
		15 => 'fifteen',
		16 => 'sixteen',
		17 => 'seventeen',
		18 => 'eighteen',
		19 => 'nineteen'
	);
	
	/**
	 * Holds the multiples of ten: 10, 20, - 90
	 * @param array
	 */
	public static $tens = array(
		10 => 'ten',
		20 => 'twenty',
		30 => 'thirty',
		40 => 'forty',
		50 => 'fifty',
		60 => 'sixty',
		70 => 'seventy',
		80 => 'eighty',
		90 => 'ninety'
	);
	
	/**
	 * Holds the larger numbers
	 * @param array
	 */
	public static $exponents = array(
		1	=> 'hundred',
		2 	=> 'thousand',
		3 	=> 'million',
		4 	=> 'billion',
		5 	=> 'trillion',
		6	=> 'quadrillion',
		7 	=> 'quintillion',
		8	=> 'sextillion',
		9	=> 'septillion',
		10	=> 'octillion',
		11 	=> 'nonillion', 
		12 	=> 'decillion',
		13	=> 'undecillion',
		14	=> 'duodecillion', 
		15	=> 'tredecillion', 
		16	=> 'quatordecillion',
		17	=> 'quindecillion',
		18	=> 'sexdecillion', 
		19	=> 'septendecillion', 
		20	=> 'octodecillion',
		21 	=> 'novemdecillion', 
		22	=> 'vigintillion',
		23 	=> 'centillion'
	);
	
	/**
	 * The separator between words
	 * @param string
	 */
	public static $sep = '-'; 
	
	/**
	 * Converts a single number to its word format
	 * @param int $number
	 * @return string
	 */
	public static function single($number) {
		$numberClean = trim(str_replace(',', '', $number));
		
		if (is_numeric($numberClean)) {
			return self::convert($numberClean);
		} else {
			return $number;
		}
	}
	
	/**
	 * Converts many numbers to its word format
	 * @param array $numbers
	 * @return array
	 */
	public static function multiple($numbers) {
		if (is_array($numbers)) {
			foreach ($numbers as $index => $number) {
				$numbers[$index] = self::single($number);
			}
		}
		
		return $numbers;
	}
	
	/**
	 * Converts any numeric instance in a block of text (sentence/string) to its word format
	 * @param string $string
	 * @return string
	 */
	public static function block($string) {
		$words = explode(' ', $string);
		
		foreach ($words as $index => $word) {
			if (preg_match("/[0-9]/i", $word)) {
				$fl = substr($word, 0, 1);
				$ll = substr($word, -1);
				$pre = '';
				$suf = '';
				
				if (!is_numeric($fl)) {
					$pre = $fl;
					$word = substr($word, 1, strlen($word) - 1);
				}
				
				if (!is_numeric($ll)) {
					$suf = $ll;
					$word = substr($word, 0, strlen($word) - 1);
				}
				
				$words[$index] = $pre . self::convert($word) . $suf;
			} else {
				$words[$index] = $word;
			}
		}
		
		return implode(' ', $words);
	}
	
	/**
	 * Converts american currency into its word format
	 * @param int $number
	 * @return string
	 */
	public static function currency($number) {
		$number = trim(str_replace(array('$', ','), '', $number));
		$cents  = trim(strstr($number, '.'), '.');
		$amount = substr($number, 0, strpos($number, '.'));
		
		$return = self::convert($amount) .' dollars';
		
		if ($cents != '00' && strlen($cents) == 2) {
			$return .= ' and '. self::convertDoubles($cents) .' cent';
			if ($cents > 1) {
				$return .= 's';
			}
		}
		
		return $return;
	}
	
	/**
	 * Determines numbers length then converts to words
	 * @param int $number
	 * @return string
	 */
	private static function convert($number) {
		$length = strlen($number);
		
		if ($length > 3) {
			$return = self::convertMultiples($number);
		} else if ($length == 3) {
			$return = self::convertTriples($number);
		} else if ($length == 2) {
			$return = self::convertDoubles($number);
		} else {
			$return = self::$digits[$number];
		}
		
		return $return;
	}
	
	/**
	 * Converts doubles: 10-99
	 * @param int $number
	 * @return string
	 */
	private static function convertDoubles($number) {
		$fn = substr($number, 0, 1);
		$ln = substr($number, -1);
		
		if ($fn == 1 && $ln != 0) {
			$return = self::$teens[$number];
		} else if ($ln == 0) {
			$return = self::$tens[$number];
		} else {
			$return = self::$tens[$fn .'0'] . self::$sep . self::$digits[$ln];
		}
		
		return $return;
	}
	
	/**
	 * Converts triples: 100-999
	 * @param int $number
	 * @return string
	 */
	private static function convertTriples($number) {
		$fn = substr($number, 0, 1);
		$ln = substr($number, -2);
		$return = '';
		
		if ($fn != 0) {
			$return = self::$digits[$fn] . self::$sep . self::$exponents[1];
		}
		
		if ($ln != '00') {
			$return .= ' '. self::convertDoubles($ln);
		}
		
		return $return;
	}
	
	/**
	 * Converts large numbers: 1000+
	 * @param int $number
	 * @return string
	 */
	private static function convertMultiples($number) {
		$numrev = strrev($number);
		$parts = str_split($numrev, 3);
		
		$cur = 1;
		$newParts = array();
		foreach ($parts as $index => $part) {
			$ret = self::convert(strrev($part));
			if ($index > 0 && $part != '000') {
				$ret .= self::$sep . self::$exponents[$cur];
			}
			if ($ret != '') {
				$newParts[$index] = $ret;
			}
			$cur++;
		}
		
		$parts = array_reverse($newParts);
		$return = implode(', ', $parts);
			
		return $return;
	}
	
}
