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

		$decimals = self::base16DecimalArrayFromHex($strippedHexString);
		return self::decimalFromBaseNDecimalArray($decimals, 16);
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


	private static function base16DecimalArrayFromHex($hexString) {
		$decimals = [];
		$stringLength = strlen($hexString);
		for ($i = 0; $i < $stringLength; $i++) {
			$character = substr($hexString, $i, 1);
			$decimals []= hexdec($character);
		}
		return $decimals;
	}

	public static function hexFromDecimal($decimalValue) {
		$base16Values = self::baseNDecimalArrayFromDecimal($decimalValue, 16);
		$hexString = "";
		foreach ($base16Values as $value) {
			$hexString .=  dechex($value);
		}
		return $hexString;
	}


	public static function decimalByteArrayFromDecimal($decimalValue) {
		return self::baseNDecimalArrayFromDecimal($decimalValue, 256);
	}

	private static function baseNDecimalArrayFromDecimal($decimalValue, $base) {
		$remainder = $decimalValue;
		$values = [];
		while($remainder != 0) {
			$value = bcmod($remainder, $base);
			array_unshift($values, $value);
			$remainder = bcdiv($remainder, $base, 0);
		}
		return $values;
	}


	public static function decimalFromDecimalByteArray(array $bytes) {
		return self::decimalFromBaseNDecimalArray($bytes, 256);
	}


	private static function decimalFromBaseNDecimalArray(array $decimals, $base) {
		$decimalValue = 0;
		$length = count($decimals);
		for ($indexFromEnd = 0; $indexFromEnd < $length; $indexFromEnd++) {
			$decimalValue = bcadd(
					$decimalValue,
					self::valueOfNthDecimalValueFromEndOfBaseNArray(
							$decimals[$length - $indexFromEnd - 1],
							$indexFromEnd,
							$base
					)
			);
		}
		return $decimalValue;
	}

	private static function valueOfNthDecimalValueFromEndOfBaseNArray($decimalVAlue, $indexFromEnd, $base) {
		return bcmul($decimalVAlue, bcpow($base, $indexFromEnd));
	}


}