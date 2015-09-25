<?php
/**
 *  Copyright 2015 net2grid B.V.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */


namespace Net2Grid\Conversion;


use InvalidArgumentException;

class Numeric {

	public static function decimalFromHex($hexString) {
		$strippedHexString = self::strippedHexString($hexString);
		self::ensureOnlyHexCharactersIn($strippedHexString);
		$decimalValue = 0;
		self::forEachCharacterRightToLeft($strippedHexString,
			function($character, $indexFromRight) use (&$decimalValue){
				$decimalValue = bcadd(
					$decimalValue,
					self::valueOfNthHexCharacterFromRight($character, $indexFromRight)
				);
			}
		);
		return $decimalValue;
	}

	private static function strippedHexString($hexString) {
		$hexString = preg_replace("/[:\-\.]/", "", $hexString);
		$hexString = preg_replace("/^0[xX]/", "", $hexString);
		$hexString = preg_replace("/\s/", "", $hexString);
		return $hexString;
	}

	private static function ensureOnlyHexCharactersIn($strippedHexString) {
		if (! ctype_xdigit($strippedHexString))
			throw new InvalidArgumentException("Invalid hex string provided.");
	}

	private static function forEachCharacterRightToLeft($aString, $callback) {
		$stringLength = strlen($aString);
		for ($indexFromRight = 0; $indexFromRight < $stringLength; $indexFromRight++) {
			$character = substr($aString, (-1 -$indexFromRight), 1);
			$callback($character, $indexFromRight);
		}
	}

	private static function valueOfNthHexCharacterFromRight($character, $indexFromRight) {
		$value = bcmul(hexdec($character) , bcpow(16, $indexFromRight));
		return $value;
	}

	public static function hexFromDecimal($decimalValue) {
		$remainder = $decimalValue;
		$hexString = "";
		while ($remainder != 0) {
			$hexChar = dechex(bcmod($remainder, 16));
			$hexString = $hexChar . $hexString;
			$remainder = bcdiv($remainder, 16, 0);
		}
		return $hexString;
	}

}